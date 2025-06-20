<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Symfony\Component\Process\Process;
use WireUi\Actions\Dialog;
use WireUi\Traits\WireUiActions;

class DevZone extends Component
{
    use WireUiActions;

    public $env, $afterDeployCommands = 'composer install', $currentCommit;

    public function mount()
    {
        if (! auth()->user()->sudo) {
            abort(405);
        }
        $envPath = base_path('.env'); // Obtiene la ruta completa al archivo .env
        if (file_exists($envPath)) {
            $this->env = file_get_contents($envPath); // Lee el contenido del archivo
        }

        $this->currentCommit = Cache::remember('currentCommit', \now()->addMinute(), function () {
            $version = '??';
            try {
                $process = Process::fromShellCommandline(<<<'BASH'
                echo "$(git describe --all --dirty) $(git rev-parse --short HEAD)"
                BASH, base_path());
                $process->run();

                $version = \trim($process->getOutput()) ?? '??';
            } finally {
                return $version;
            }
        }) ?? '??';
    }

    public function changeEnv($confirmation = false)
    {
        if (! $confirmation) {
            $this->dialog()->confirm([
                'icon' => 'warning',
                'title' => \__('Are you sure?'),
                'description' => \__('This could make the system to stop working properly'),
                'accept'      => [
                    'label'  => 'Yes',
                    'method' => 'changeEnv',
                    'params' => [true],
                ],
            ]);
            return;
        }

        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            $this->notification()->warning('DotEnv file doesn\'t exists');
            return;
        }

        \file_put_contents($envPath, $this->env);

        Artisan::call('config:clear');

        $this->notification()->success(
            \__('Successfully changed DotEnv'),
            \__('Refresh the page to ensure everything\'s okay')
        );
    }

    public function deploy()
    {
        $stdout = [];
        $stderr = [];
        /** Collects the STDOUT & STDERR from a CMD process */
        $stdCollection = function ($type, $buffer) use (&$stdout, &$stderr): void {
            if (Process::ERR === $type) {
                array_push($stderr, $buffer);
            } else {
                array_push($stdout, $buffer);
            }
        };

        $branch = $this->resolveBranch();
        $remote = $this->resolveHttpsRemoteVersion();

        $envs = [
            'HOME' => \env('HOME'),
            'COMPOSER_HOME' => \env('COMPOSER_HOME'),
            // ! Keep in mind that these credentials should be removed as best practice
            'GIT_USER' => \env('GIT_USER', 'skalero01'),
            'GIT_PASSWORD' => \env('GIT_PASSWORD', ''),
            'GIT_TERMINAL_PROMPT' => 0,
            'GIT_REMOTE' => $remote,
            'GIT_BRANCH' => $branch,
        ];

        $pullProcess = Process::fromShellCommandline(<<<'BASH'
        git -c credential.helper='!f() { sleep 1; echo "username=${GIT_USER}"; echo "password=${GIT_PASSWORD}"; }; f' pull "${GIT_REMOTE}" "${GIT_BRANCH}"
        BASH, base_path())->setTimeout(10);

        try {
            $pullProcess->run($stdCollection, $envs);
        } catch (\Throwable $th) {
            $this->deployError($th, $stdout, $stderr);
            return;
        }

        $this->notification()->success(__('Successfully pulled latest commit!'));

        $commands = \collect(\explode("\n", $this->afterDeployCommands ?? ''))->map(fn($v) => \trim($v))->filter();

        if ($commands->isEmpty()) {
            return;
        }

        $customProcess = Process::fromShellCommandline($commands->implode(' && '), base_path())->setTimeout(10);

        try {
            $customProcess->run($stdCollection, $envs);
        } catch (\Throwable $th) {
            $this->deployError($th, $stdout, $stderr);
            return;
        }

        $this->notification()->success(__('Successfully executed custom commands!'));
        $this->log($stdout, $stderr, isError: \false);
    }

    private function resolveBranch(): string
    {
        $resolveBranch = Process::fromShellCommandline('git rev-parse --abbrev-ref HEAD', \base_path());
        $resolveBranch->run();

        return \trim($resolveBranch->getOutput());
    }

    private function resolveHttpsRemoteVersion(): string
    {
        $resolveRemote = Process::fromShellCommandline('git remote', \base_path());
        $resolveRemote->run();
        $remote = \trim($resolveRemote->getOutput());

        $resolveUrl = Process::fromShellCommandline(
            'git remote get-url ${GIT_REMOTE}',
            \base_path(),
            ['GIT_REMOTE' => $remote]
        );
        $resolveUrl->run();
        $url = \trim($resolveUrl->getOutput());

        return str($url)
            ->replaceMatches('/^git@([^:]+):/', 'https://$1/')
            ->replaceMatches("/\.git$/", '')
            ->toString();
    }

    private function deployError(\Throwable $exception, array $stdout, array $stderr)
    {
        $error = str($exception->getMessage())->trim()->replace("\n", ' ')->limit(100)->toString();
        $this->notification()->error('There has been an error on deploy. Please check logs', $error);

        $this->log($stdout, $stderr, isError: \true);
        \report($exception);
    }

    private function log(array $stdout, array $stderr, $isError = true)
    {
        $data = json_encode([
            'stderr' => implode("\n", $stderr),
            'stdout' => implode("\n", $stdout),
        ], \JSON_PRETTY_PRINT);

        if ($isError) {
            \logger()->error('[DevZone Deployment Error]:' . $data);
            return;
        }

        \logger()->info('[DevZone Deployment]:' . $data);
    }

    public function render()
    {
        return view('livewire.admin.dev-zone')->layout('layouts.app', [
            'breadcrumb' => [
            ['label' => __('Dev Zone')]
            ]
        ]);
    }
}

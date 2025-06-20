<?php

namespace App\Livewire\Admin\Ai;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AiServiceConfiguration;
use App\Models\AiProvider;
use App\Models\AiPrompt;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $activeTab = 'configurations';

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $configurations = AiServiceConfiguration::with('provider')
            ->orderBy('service_type')
            ->orderBy('name')
            ->paginate(10);

        $providers = AiProvider::orderBy('name')->paginate(10);

        $prompts = AiPrompt::orderBy('service_type')
            ->orderBy('prompt_type')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.ai.index', compact('configurations', 'providers', 'prompts'));
    }
}

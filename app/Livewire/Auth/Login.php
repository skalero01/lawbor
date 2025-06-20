<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Login extends Component
{
    use WireUiActions, Traits\NeedsVerification;

    public $email, $password, $code, $user_code, $user;
    public $remember = false, $step = 1;

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean'
        ];
    }

    public function loginTwoSteps()
    {
        if ($this->step == 1) {
            // Email validation
            $this->validate([
                'email' => 'required|email'
            ]);

            $this->user = User::where('email', $this->email)->first();

            if (is_null($this->user)) {
                if (config('auth.enable_register')) {
                    return redirect()->route('register', ['email' => $this->email]);
                }

                return $this->notification()->error(__('Wrong credentials'), __('Please, try again'));
            }

            if (is_null($this->user->email_verified_at)) {
                $this->verifyEmail('loginTwoSteps');
                $this->user->refresh();
                return;
            }

            if (is_null($this->user->password)) {
                $this->step = 3;
                return;
            }

            $this->step = 2;
        } elseif ($this->step == 2) {
            // Password validation
            $this->validate(['password' => 'required']);

            $this->login();
        } elseif ($this->step == 3) {
            // Create password
            $this->validate(['password' => ['required', \password_rule()]]);

            $this->user->update(['password' => $this->password]);
            $this->notification()->success(__('Password created'), __('Now you can login'));
            $this->login();
        }
    }

    public function login()
    {
        $this->validate();

        $attempt = Auth::attemptWhen([
            'email' => $this->email,
            'password' => $this->password
        ], function ($user) {
            // Validate email before login if hasnt been validated yet
            if (
                (config('auth.approach') == 'LoginValidation' || config('auth.approach') == 'CreationValidation') &&
                $this->view == 'normal' &&
                is_object($user) &&
                is_null($user->email_verified_at) &&
                config('auth.enable_validation')
            ) {
                $this->user = $user;
                $this->verifyEmail('login');
                return false;
            }
            return true;
        }, $this->remember);

        if ($attempt) {
            $this->reset('password');
            session()->put('auth.password_confirmed_at', time());
            session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $this->notification()->error(__('Wrong credentials'), __('Please, try again'));
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.auth');
    }
}

<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Livewire\Component;

class Register extends Component
{
    use Traits\NeedsVerification;

    public $user, $password, $password_confirmation, $email;
    public $terms = false;
    public $queryString = ['email'];

    public function rules()
    {
        return [
            'user.name' => ['required', 'string', 'max:255'],
            'user.email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                \password_rule(),
            ],
            'terms' => ['accepted'],
        ];
    }

    public function mount()
    {
        abort_unless(config('auth.enable_register'), 404);

        $this->user = new User;
        $this->user->email = $this->email;
    }

    public function register()
    {
        $this->validate();
        if (config('auth.approach') == 'CreationValidation' && is_null($this->user->email_verified_at) && $this->view == 'normal') {
            return $this->verifyEmail('register', false);
        }

        $this->user->password = $this->password;
        $this->user->save();

        if ($role = config('app.default_role')) {
            $this->user->assignRole($role);
        }

        auth()->login($this->user);

        $this->reset(['password', 'password_confirmation']);

        return redirect(RouteServiceProvider::HOME);
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.auth');
    }
}

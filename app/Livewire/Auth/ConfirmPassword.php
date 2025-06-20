<?php

namespace App\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Livewire\Component;

class ConfirmPassword extends Component
{
    public $password;

    public function rules()
    {
        return [
            'password' => 'required|current_password:web',
        ];
    }

    public function confirm()
    {
        $this->validate();
        session()->put('auth.password_confirmed_at', time());
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function render()
    {
        return view('livewire.auth.confirm-password')->layout('layouts.auth');
    }
}

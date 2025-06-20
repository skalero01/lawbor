<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use WireUi\Actions\Dialog;
use WireUi\Traits\WireUiActions;

class ForgotPassword extends Component
{
    use WireUiActions;

	public $email, $origin;

    public function rules()
    {
        return [
            'email' => 'required|email'
        ];
    }

    public function mount()
    {
        $this->origin = url()->previous();
    }

    public function request()
    {
        $this->validate();

        $response = Password::broker()->sendResetLink([
            'email' => $this->email
        ]);

        if ($response !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
            return;
        }

        $this->dialog()->confirm([
            'icon' => 'success',
            'title' => __('Reset link sent!'),
            'description' => __('Please, check your email for further instructions'),
            'accept'      => [
                'label'  => 'Okay',
                'method' => 'back',
                'params' => [],
            ],
        ]);
    }

    public function back()
    {
        return redirect($this->origin);
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('layouts.auth');
    }
}

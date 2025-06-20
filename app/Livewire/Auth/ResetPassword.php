<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ResetPassword extends Component
{
    use WireUiActions;

    public $token, $email, $password, $password_confirmation;

    public function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', \password_rule()],
        ];
    }

    public function mount(string $token)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $this->validate();

        $response = Password::broker()->reset([
            'token' => $this->token,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ], function (User $user, $password) {
            $user->password = $password;
            $user->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
            auth()->guard()->login($user);
        });

        if ($response !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
            return;
        }

        $this->reset(['password', 'password_confirmation']);
        $this->dialog()->success(__('Success!'), __('Your password has been changed'));
        return redirect(RouteServiceProvider::HOME);
    }

    public function render()
    {
        return view('livewire.auth.reset-password')->layout('layouts.auth');
    }
}

<?php

namespace App\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class PasswordRequest extends Component
{
    use WireUiActions;

    public $_confirmed, $password, $password_confirmation, $current_password;
    // All Livewire properties are insecure by default
    // So, in this case we encrypt and decrypt the actual boolean
    // value between request, so the client can't tamper with it

    public function getConfirmedProperty()
    {
        return decrypt(base64_decode($this->_confirmed)) === true;
    }

    public function mount()
    {
        abort_unless(auth()->user()->shouldEnforcePasswordRequest(), 400);

        $this->_confirmed = base64_encode(encrypt(false));
    }

    public function handle()
    {
        if (! $this->getConfirmedProperty()) {
            if (Hash::check($this->current_password, auth()->user()->password)) {
                return $this->_confirmed = base64_encode(encrypt(true));
            } else {
                return $this->notification()->error(__('Wrong credentials'), __('Please, try again'));
            }
        }

        $this->validate([
            'password' => [
                'required',
                'confirmed',
                \password_rule(),
            ],
        ]);

        if (Hash::check($this->password, auth()->user()->password)) {
            return $this->addError('password', __('Password must be different.'));
        }

        $user = auth()->user();
        $user->password = $this->password;
        $user->save();
        event(new PasswordReset($user));

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function render()
    {
        return view('livewire.auth.password-request')->layout('layouts.auth');
    }
}

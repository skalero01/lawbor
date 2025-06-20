<?php

namespace App\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Verification extends Component
{
    use WireUiActions;

	public $userId, $hash;
    public $resent = false;
    public $valid = false;

    public function mount(?int $id = null, ?string $hash = null)
    {
        $this->userId = $id;
        $this->hash = $hash;

        if (! $id && ! $hash) {
            return;
        }

        $user = auth()->user();

        throw_unless(
            hash_equals($hash, sha1($user->getEmailForVerification())),
            AuthorizationException::class
        );

        if ($user->hasVerifiedEmail()) {
            $this->valid = true;
            return $this->redirectTo();
        }

        throw_unless($user->markEmailAsVerified());
        event(new Verified($user));
        $this->valid = true;
        return $this->redirectTo();
    }

    public function request()
    {
        $user = auth()->user();
        if ($user->hasVerifiedEmail()) {
            return;
        }

        $this->notification()->success(__('Verification email resent'));
        $user->sendEmailVerificationNotification();
    }

    public function redirectTo()
    {
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function render()
    {
        return view('livewire.auth.verification')->layout('layouts.auth');
    }
}

<?php

namespace App\Listeners;

use Illuminate\Auth\Events\PasswordReset;

class UpdateUserPasswordResetAt
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        if (method_exists($event->user, 'updatePasswordResetAt')) {
            $event->user->updatePasswordResetAt(now());
            $event->user->save();
        }
    }
}

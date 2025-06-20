<?php

namespace App\Providers;

use App\Listeners\UpdateUserPasswordResetAt;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PasswordReset::class => [
            UpdateUserPasswordResetAt::class
        ]
    ];

    public function boot()
    {
        //
    }
}

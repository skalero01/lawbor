<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait UserBase
{
    /*
     * Functions
     */

    public function channelNotifications()
    {
        $channels = ['database'];
        if ($this->send_mail) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    /*
     * Attributes
     */

    protected function sudo(): Attribute
    {
        return Attribute::get(
            fn() => in_array($this->email, array_map('strtolower', config('app.sudo')))
        )->shouldCache();
    }
}

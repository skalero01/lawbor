<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\DatabaseNotification as Model;

class DatabaseNotification extends Model
{
    /*
     * Attributes
     */

    protected function isRead(): Attribute
    {
        return Attribute::get(fn() => $this->read_at != null);
    }
}

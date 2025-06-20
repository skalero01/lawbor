<?php

namespace App\Models;

use App\Observers\UserObserver;
use App\Traits\CanActAsOthers;
use App\Traits\EnforcesPasswordRequests;
use App\Traits\UserBase;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Permission\Traits\HasRoles;
use WeblaborMx\TallUtils\Models\WithActivityLog;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, SearchableTrait, SoftDeletes, WithActivityLog, HasRoles, CanActAsOthers, EnforcesPasswordRequests, UserBase;

    protected $guarded = [];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'requested_password_reset_at' => 'datetime',
        'password_reset_at' => 'datetime',
        'send_mail' => 'boolean',
    ];
    protected $searchable = [
        'columns' => [
            'name' => 10,
            'email' => 10,
        ],
    ];

    /*
     * Relationships
     */

    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')
            ->orderByRaw('read_at IS NULL DESC')
            ->orderBy('created_at', 'desc');
    }

    /*
     * Attributes
     */

    protected function email(): Attribute
    {
        // Always ensure normalization of emails
        return Attribute::make(
            get: fn($value) => strtolower(trim($value)),
            set: fn($value) => strtolower(trim($value))
        );
    }

    protected function avatar(): Attribute
    {
        return Attribute::get(function () {
            if ($this->photo) {
                return $this->photo;
            }
            $md5 = md5($this->email);

            return "https://api.dicebear.com/9.x/thumbs/png?seed={$md5}&size=120";
        })->shouldCache();
    }

    public function setPasswordAttribute($value)
    {
        if (is_null($value)) {
            return;
        }
        $this->attributes['password'] = Hash::make($value);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}

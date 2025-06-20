<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as Model;
use WeblaborMx\TallUtils\Models\WithActivityLog;

class Permission extends Model
{
	use SoftDeletes, WithActivityLog;

    protected $guarded = [];

    /*
     * Relationships
     */

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }
}

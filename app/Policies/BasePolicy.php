<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

abstract class BasePolicy
{
    use HandlesAuthorization;

    protected string $name;

    public function before(User $user)
    {
        if ($user->sudo) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo("retrieve {$this->name}");
    }

    public function view(User $user, Model $instance)
    {
        return $user->hasPermissionTo("retrieve {$this->name}");
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo("create {$this->name}");
    }

    public function update(User $user, Model $instance)
    {
        return $user->hasPermissionTo("update {$this->name}");
    }

    public function delete(User $user, Model $instance)
    {
        return $user->hasPermissionTo("delete {$this->name}");
    }

    public function restore(User $user, Model $instance)
    {
        return false;
    }

    public function forceDelete(User $user, Model $instance)
    {
        return false;
    }
}

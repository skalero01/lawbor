<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Permission $model)
    {
        return true;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Permission $model)
    {
        return false;
    }

    public function delete(User $user, Permission $model)
    {
        return false;
    }

    public function restore(User $user, Permission $model)
    {
        return false;
    }

    public function forceDelete(User $user, Permission $model)
    {
        return false;
    }
}

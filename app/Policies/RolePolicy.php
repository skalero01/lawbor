<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class RolePolicy extends BasePolicy
{
    protected string $name = 'role';

    public function update(User $user, Model $role)
    {
        return parent::update($user, $role) &&
            $role->name !== config('app.admin_role');
    }

    public function delete(User $user, Model $role)
    {
        return parent::delete($user, $role) &&
            ! in_array($role->name, [config('app.admin_role'), config('app.default_role')]);
    }
}

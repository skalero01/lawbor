<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserPolicy extends BasePolicy
{
    protected string $name = 'user';

    public function update(User $user, Model $instance)
    {
        return parent::update($user, $instance) && ! $instance->sudo;
    }

    public function delete(User $user, Model $instance)
    {
        return parent::delete($user, $instance)
            && $instance->id !== $instance->id
            && ! $instance->sudo;
    }
}

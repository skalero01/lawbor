<?php

namespace App\Front\Actions;

use App\Models\User;
use App\Providers\RouteServiceProvider;

class ActingAs extends Action
{
    public $title = 'Act as';
    public $icon = 'user-circle';
    public $show_on_index = true;

    public function hasPermissions($object)
    {
        /** @var User */
        $user = auth()->user();
        return $user->canActAs($object);
    }

    public function handle(User $object)
    {
        /** @var User */
        $user = auth()->user();
        if ($user->isActing()) {
            flash('You\'re already acting as an user');
            return redirect()->back();
        }

        $user->actAs($object);
        return redirect()->to(RouteServiceProvider::HOME);
    }
}

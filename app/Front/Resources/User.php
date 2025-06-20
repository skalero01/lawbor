<?php

namespace App\Front\Resources;

use App\Front\Actions\ActingAs;
use App\Front\Inputs as Custom;
use App\Models\Role;
use App\Models\User as Model;
use App\Notifications\User\CreatedUserNotification;
use App\Notifications\User\RoleUpdateNotification;
use WeblaborMx\Front\Inputs;

class User extends Resource
{
    public $base_url = '/admin/users';
    public $model = Model::class;
    public $title = 'name';
    public $icon = 'users';

    public function indexQuery($query)
    {
        return $query->with('roles')->orderBy('name');
    }

    public function fields()
    {
        return [
            Inputs\ID::make(),
            Inputs\Text::make('Name')->rules('required'),
            Inputs\Text::make('Email')->rules(['required', 'email', 'regex:/^[^áéíóúÁÉÍÓÚñÑ]+$/u']),
            Custom\Password::make('Password')->rules(['string', 'min:8', 'nullable'])->creationRules('required'),
            Inputs\BelongsToMany::make('Role')->show(
                ! $this->sourceIsForm() || auth()->user()->hasRole(config('app.admin_role'))
            ),
            Inputs\DateTime::make('Email Verified At')->onlyOnDetail(),
            Inputs\DateTime::make('Created At')->onlyOnDetail(),
            Inputs\DateTime::make('Updated At')->onlyOnDetail(),
        ];
    }

    public function actions()
    {
        return [
            ActingAs::class
        ];
    }

    public function beforeUpdate($object, $request)
    {
        if (! auth()->user()->hasRole(config('app.admin_role'))) {
            return;
        }

        $roles = $object->roles->pluck('id');
        $removeRol = $roles->diff($request->roles_mtm);
        $addRol = collect($request->roles_mtm)->diff($roles->toArray());

        if (count($removeRol) > 0 || count($addRol) > 0) {
            $oldRol = Role::whereIn('id', $removeRol->toArray())->pluck('name');
            $newRol = Role::whereIn('id', $addRol->toArray())->pluck('name');

            // Send notification to user when role is updated
            $object->notify(new RoleUpdateNotification($object->name, $oldRol->implode(', '), $newRol->implode(', ')));
        }
    }

    public function processAfterSave($object, $request)
    {
        if ($object->wasRecentlyCreated) {
            $object->notify(new CreatedUserNotification($object, $request->password));
            $object->requestForNewPassword();
        }
        parent::processAfterSave($object, $request);
    }
}

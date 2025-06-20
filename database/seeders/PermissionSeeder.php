<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $allowDeletion = config('app.allow_permisisons_deletion', false);

        if (config('app.discover_front_permissions', true)) {
            $permissions = $this->getFrontPermissions()->mapWithKeys(function ($v) {
                return [$v => config('auth.defaults.guard')];
            });
        } else {
            $permissions = collect();
        }

        $permissions = $permissions
            ->merge(config('app.permissions'))
            ->filter(fn($v, $k) => ! is_int($k));

        $models = $permissions->map(function ($guard, $permission) {
            $attributes = [
                'name' => $permission,
                'guard_name' => $guard,
            ];

            $newValues = [
                'deleted_at' => null
            ];

            return Permission::query()
                ->withTrashed()
                ->updateOrCreate($attributes, $newValues);
        });

        if ($allowDeletion) {
            Permission::whereNotIn('id', $models->pluck('id'))->delete();
        }
    }

    protected function getFrontPermissions()
    {
        $permissions = front_resources()->filter(function($item) {
            $resource = new $item;
            return !method_exists($resource, 'model') && !is_null($resource->model);
        })->map(function ($resourceClass) {
            $resource = new $resourceClass;
            $name = Str::singular($resource->model::tableName());
            $actions = collect($resource->actions);

            $crud = collect();
            if ($actions->contains('create')) {
                $crud->add('create');
            }
            if ($actions->contains('show')) {
                $crud->add('retrieve');
            }
            if ($actions->contains('update')) {
                $crud->add('update');
            }
            if ($actions->contains('destroy')) {
                $crud->add('delete');
            }
            return $crud->map(function ($action) use ($name) {
                return "$action $name";
            });
        })->flatten(1);

        return $permissions;
    }
}

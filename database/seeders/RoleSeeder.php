<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $this->ensureAdminExists();
        $this->ensureDefaultExists();
    }

    protected function ensureAdminExists()
    {
        $adminRole = trim(config('app.admin_role'));

        throw_unless($adminRole, 'No admin role defined.');

        $role = Role::updateOrCreate([
            'name' => config('app.admin_role')
        ], [
            'guard_name' => config('auth.defaults.guard')
        ]);

        $role->syncPermissions(Permission::pluck('id'));
    }

    protected function ensureDefaultExists()
    {
        $defaultRole = config('app.default_role');

        if (! $defaultRole) return;

        /** @var Role */
        $role = Role::updateOrCreate([
            'name' => $defaultRole,
        ], [
            'guard_name' => config('auth.defaults.guard')
        ]);

        if ($role->wasRecentlyCreated) {
            $role->syncPermissions(
                Permission::whereIn('name', config('app.default_role_permissions', []))
                    ->pluck('id')
            );
        }
    }
}

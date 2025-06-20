<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminSeeder::class,
            AiTablesSeeder::class,
        ]);

        // * Run development seeders
        if (! app()->isProduction()) {
            $this->call([
                UserSeeder::class,
            ]);
        }
    }
}

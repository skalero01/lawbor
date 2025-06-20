<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        collect(config('app.sudo'))->each(function ($email) {
            $email = str($email);
            $name = $email->before('@')->headline()->toString();
            $email = $email->lower();
            $password = $email
                ->before('@')
                ->reverse()
                ->toString();

            /** @var User */
            $user = User::query()->firstOrCreate([
                'email' => $email->toString(),
            ], [
                'name' => $name,
                'email_verified_at' => now(),
                'password' => $password,
                'remember_token' => str()->random(),
            ]);

            if (Hash::check($password, $user->password)) {
                if (method_exists($user, 'requestForNewPassword')) {
                    $user->requestForNewPassword();
                }
            }

            $user->assignRole(config('app.admin_role'));
        });
    }
}

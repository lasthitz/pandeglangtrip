<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('DEFAULT_ADMIN_EMAIL', 'admin@pandeglangtrip.test');
        $password = env('DEFAULT_ADMIN_PASSWORD', 'Admin12345!');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => Hash::make($password),
                'role' => 'admin',
            ]
        );
    }
}

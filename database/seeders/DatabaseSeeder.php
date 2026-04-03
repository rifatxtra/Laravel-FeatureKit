<?php

namespace Database\Seeders;

use App\Features\Auth\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->createMany(
            [
                [
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'is_active' => true,
                    'profile_image' => null,
                    'last_login_at' => null,
                    'last_login_ip' => null,
                ],
                [
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'is_active' => true,
                    'profile_image' => null,
                    'last_login_at' => null,
                    'last_login_ip' => null,
                ]
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user — change password after first deploy!
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@ma-tools.com')],
            [
                'name'     => 'Admin',
                'password' => bcrypt(env('ADMIN_PASSWORD', 'change-me-immediately')),
                'is_admin' => true,
            ]
        );
    }
}

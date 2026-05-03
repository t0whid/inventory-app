<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RootSuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['phone' => config('app.root_super_admin_phone')],
            [
                'name' => 'Towhid',
                'email' => 'towhid@example.com',
                'password' => '12345678',
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );
    }
}
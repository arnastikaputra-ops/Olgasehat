<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Check if superadmin already exists
        $existing = User::where('email', 'admin@gmail.com')->first();
        if (!$existing) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'), // secure password
                'role' => 'superadmin',
            ]);
        }
    }
}

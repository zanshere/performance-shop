<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin MotorSpareParts',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Create a regular user for testing
        User::create([
            'name' => 'Customer Test',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
    }
}

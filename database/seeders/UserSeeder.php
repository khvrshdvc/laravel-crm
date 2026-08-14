<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // creates sample users with different roles for development and testing
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => UserRole::Admin,
        ]);

        User::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => 'password',
            'role' => UserRole::Manager,
        ]);

        User::create([
            'name' => 'Employee',
            'email' =>  'employee@example.com',
            'password' => 'password',
            'role' => UserRole::Employee,
        ]);
    }
}

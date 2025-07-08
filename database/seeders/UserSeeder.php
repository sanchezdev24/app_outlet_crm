<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuarios específicos
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@outlet.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Sales User',
            'email' => 'sales@outlet.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Crear usuarios adicionales
        User::factory(5)->create();
    }
}
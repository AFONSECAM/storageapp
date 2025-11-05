<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@storageapp.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456789'),
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@storageapp.com'],
            [
                'name' => 'Usuario Demo',
                'password' => Hash::make('123456'),
                'role' => 'user',
            ]
        );
    }
}

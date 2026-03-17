<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем Диспетчера
        User::create([
            'name' => 'Диспетчер Иван',
            'email' => 'admin@test.com',
            'password' => Hash::make('12345678'),
            'role' => 'dispatcher',
        ]);

        // Создаем Мастера 1
        User::create([
            'name' => 'Мастер Петр',
            'email' => 'master1@test.com',
            'password' => Hash::make('12345678'),
            'role' => 'master',
        ]);

        // Создаем Мастера 2
        User::create([
            'name' => 'Мастер Андрей',
            'email' => 'master2@test.com',
            'password' => Hash::make('12345678'),
            'role' => 'master',
        ]);
    }
}
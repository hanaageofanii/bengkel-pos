<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bengkel.com'],
            [
                'name' => 'Admin Bengkel',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
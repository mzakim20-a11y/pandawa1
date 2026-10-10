<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed admin user untuk login ke dashboard.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pandawa.com'],
            [
                'name' => 'Admin Pandawa',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}

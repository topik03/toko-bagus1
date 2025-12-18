<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah admin sudah ada
        if (User::where('email', 'admin@tokobagus.com')->exists()) {
            $this->command->info('Admin user already exists. Skipping...');
            return;
        }

        // Buat admin
        User::create([
            'name' => 'Admin Toko Bagus',
            'email' => 'admin@tokobagus.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@tokobagus.com');
        $this->command->info('Password: admin123');
    }
}

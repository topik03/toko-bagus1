<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User biasa 1
        User::create([
            'name' => 'Customer Demo',
            'email' => 'customer@tokobagus.com',
            'password' => Hash::make('customer123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        // User biasa 2
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        // Tambahkan user dummy lainnya jika perlu
        User::factory()->count(5)->create([
            'is_admin' => false,
        ]);

        $this->command->info('User seeders created successfully!');
    }
}

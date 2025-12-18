<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SellerSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Toko Bagus Admin',
            'email' => 'admin@tokobagus.com',
            'password' => Hash::make('password123'),
            'role' => 'seller',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Beras Sejahtera',
            'email' => 'beras@tokobagus.com',
            'password' => Hash::make('password123'),
            'role' => 'seller',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Minyak Gemilang',
            'email' => 'minyak@tokobagus.com',
            'password' => Hash::make('password123'),
            'role' => 'seller',
            'email_verified_at' => now(),
        ]);
    }
}

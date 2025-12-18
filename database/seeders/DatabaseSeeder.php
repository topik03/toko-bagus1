<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil semua seeder yang diperlukan
        $this->call([
            AdminSeeder::class,    // Buat admin user
            UserSeeder::class,     // Buat user biasa
            // CategorySeeder::class,  // Uncomment jika ada
            // ProductSeeder::class,   // Uncomment jika ada
            // AddressSeeder::class,   // Uncomment jika ada
        ]);

        // OPSIONAL: Jika masih ingin buat factory user
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // OPSIONAL: Buat dummy users dengan factory
        // User::factory(5)->create(); // 5 user dummy
    }
}

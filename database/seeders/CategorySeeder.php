<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// ↓↓↓ TAMBAHKAN INI ↓↓↓
use App\Models\Category;
// ↑↑↑ IMPORT MODEL CATEGORY ↑↑↑

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Beras & Beras Analog',
                'slug' => 'beras',
                'description' => 'Beras premium dan beras analog'
            ],
            [
                'name' => 'Minyak Goreng',
                'slug' => 'minyak-goreng',
                'description' => 'Minyak goreng berbagai merek'
            ],
            [
                'name' => 'Gula & Pemanis',
                'slug' => 'gula',
                'description' => 'Gula pasir, gula merah, pemanis'
            ],
            [
                'name' => 'Telur & Susu',
                'slug' => 'telur-susu',
                'description' => 'Telur ayam, susu, produk olahan'
            ],
            [
                'name' => 'Bumbu Dapur',
                'slug' => 'bumbu-dapur',
                'description' => 'Bumbu masak lengkap'
            ],
            [
                'name' => 'Mie & Pasta',
                'slug' => 'mie-pasta',
                'description' => 'Mie instan, pasta, bihun'
            ],
            [
                'name' => 'Snack & Minuman',
                'slug' => 'snack-minuman',
                'description' => 'Snack ringan dan minuman'
            ],
        ];

        foreach ($categories as $category) {
            // Sekarang Category::create() akan dikenali
            Category::create($category);
        }

        $this->command->info('✅ Categories seeded successfully!');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class SyncProductImages extends Command
{
    protected $signature = 'images:sync';
    protected $description = 'Sync product images between storage and uploads';

    public function handle()
    {
        $products = Product::with('images')->get();

        foreach ($products as $product) {
            foreach ($product->images as $image) {
                $currentPath = $image->image_path;

                // Jika path adalah storage/, copy ke uploads/
                if (strpos($currentPath, 'storage/') === 0) {
                    $oldPath = public_path($currentPath);
                    $newPath = str_replace('storage/', 'uploads/', $currentPath);
                    $newFullPath = public_path($newPath);

                    // Buat directory jika belum ada
                    $dir = dirname($newFullPath);
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }

                    // Copy file
                    if (file_exists($oldPath) && !file_exists($newFullPath)) {
                        copy($oldPath, $newFullPath);
                        $this->info("Copied: {$oldPath} -> {$newFullPath}");
                    }

                    // Update database
                    $image->update(['image_path' => $newPath]);
                }
            }
        }

        $this->info('Image sync completed!');
    }
}

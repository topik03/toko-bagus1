<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductControllerAdmin extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with('category', 'images')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        Log::info('=== ADMIN UPLOAD START ===');
        Log::info('All form data:', $request->except(['images']));

        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'is_featured' => 'boolean',
        ]);

        Log::info('Validation passed');

        // Generate slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        Log::info('Generated slug: ' . $slug);

        // Buat produk
        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'is_featured' => $request->is_featured ?? false,
            'is_active' => true,
        ]);

        Log::info('Product created with ID: ' . $product->id);

        // Handle upload gambar - SIMPAN KE DUA LOKASI
        if ($request->hasFile('images')) {
            Log::info('Number of images: ' . count($request->file('images')));

            foreach ($request->file('images') as $index => $image) {
                Log::info('Processing image ' . $index . ': ' . $image->getClientOriginalName());

                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                Log::info('Generated filename: ' . $imageName);

                // 1. SIMPAN DI UPLOADS (untuk user/frontend)
                $uploadPath = public_path('uploads/products');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                    Log::info('Created uploads directory: ' . $uploadPath);
                }

                try {
                    // Simpan ke uploads
                    $image->move($uploadPath, $imageName);
                    Log::info('Saved to uploads: ' . $uploadPath . '/' . $imageName);
                    Log::info('Uploads file exists: ' . (file_exists($uploadPath . '/' . $imageName) ? 'YES' : 'NO'));

                    // 2. SIMPAN DI STORAGE (untuk admin/backup)
                    $storagePath = storage_path('app/public/products');
                    if (!file_exists($storagePath)) {
                        mkdir($storagePath, 0777, true);
                        Log::info('Created storage directory: ' . $storagePath);
                    }

                    // Copy dari uploads ke storage
                    copy($uploadPath . '/' . $imageName, $storagePath . '/' . $imageName);
                    Log::info('Copied to storage: ' . $storagePath . '/' . $imageName);
                    Log::info('Storage file exists: ' . (file_exists($storagePath . '/' . $imageName) ? 'YES' : 'NO'));

                    // SIMPAN PATH UPLOADS KE DATABASE (bisa diakses user)
                    $product->images()->create([
                        'image_path' => 'uploads/products/' . $imageName,
                        'is_primary' => $index === 0,
                    ]);

                    Log::info('Database record created with path: uploads/products/' . $imageName);

                } catch (\Exception $e) {
                    Log::error('Upload error: ' . $e->getMessage());
                    Log::error('Trace: ' . $e->getTraceAsString());
                }
            }
        } else {
            Log::warning('No images in request');
        }

        Log::info('=== ADMIN UPLOAD END ===');

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load('category', 'images', 'reviews.user');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Update slug jika nama berubah
        if ($product->name != $request->name) {
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;

            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $product->slug = $slug;
        }

        // Update data produk
        $product->update([
            'name' => $request->name,
            'slug' => $product->slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'is_featured' => $request->is_featured ?? $product->is_featured,
            'is_active' => $request->is_active ?? $product->is_active,
        ]);

        // Handle upload gambar baru - SIMPAN KE DUA LOKASI
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // 1. Simpan di uploads
                $uploadPath = public_path('uploads/products');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                $image->move($uploadPath, $imageName);

                // 2. Simpan di storage
                $storagePath = storage_path('app/public/products');
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0777, true);
                }
                copy($uploadPath . '/' . $imageName, $storagePath . '/' . $imageName);

                // Simpan path uploads ke database
                $product->images()->create([
                    'image_path' => 'uploads/products/' . $imageName,
                    'is_primary' => false,
                ]);
            }
        }

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete associated images dari kedua lokasi
        foreach ($product->images as $image) {
            // Delete dari uploads
            $uploadsPath = public_path($image->image_path);
            if (file_exists($uploadsPath)) {
                unlink($uploadsPath);
                Log::info('Deleted from uploads: ' . $uploadsPath);
            }

            // Delete dari storage (jika ada di storage path)
            $storagePath = str_replace('uploads/', 'storage/', $image->image_path);
            $storageFullPath = public_path($storagePath);
            if (file_exists($storageFullPath)) {
                unlink($storageFullPath);
                Log::info('Deleted from storage: ' . $storageFullPath);
            }

            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Update product status (active/inactive)
     */
    public function updateStatus(Request $request, Product $product)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $product->update([
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status produk berhasil diperbarui.',
            'is_active' => $product->is_active,
        ]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Product $product)
    {
        $product->update([
            'is_featured' => !$product->is_featured,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status featured berhasil diubah.',
            'is_featured' => $product->is_featured,
        ]);
    }
}

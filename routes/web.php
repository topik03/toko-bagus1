<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderControllerAdmin;
use App\Http\Controllers\Admin\UserControllerAdmin;
use App\Http\Controllers\ProductControllerAdmin;
use App\Http\Controllers\Admin\CategoryControllerAdmin;


// ... routes lainnya


Route::get('/', function () {
    return view('welcome');
});

// Cart Routes
Route::middleware('web')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// Order History Routes (untuk user)
Route::middleware(['auth'])->prefix('my-orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'history'])->name('history');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    Route::post('/{order}/return', [OrderController::class, 'requestReturn'])->name('return');
    Route::get('/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('invoice');
    Route::get('/{order}/track', [OrderController::class, 'track'])->name('track');
});

// Profile Dashboard Routes
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    // Dashboard utama
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');

    // Alamat pengiriman
    Route::get('/addresses', [ProfileController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [ProfileController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [ProfileController::class, 'deleteAddress'])->name('addresses.delete');
    Route::get('/addresses/create', [ProfileController::class, 'createAddress'])->name('addresses.create');

    // Chat dengan penjual
    Route::get('/chats', [ProfileController::class, 'chats'])->name('chats');
    Route::get('/chats/{chat}', [ProfileController::class, 'showChat'])->name('chats.show');
    Route::post('/chats', [ProfileController::class, 'sendMessage'])->name('profile.chats.end');
    Route::get('/profile/chats', [ProfileController::class, 'chat'])->name('profile.chats');

    // Pengaturan akun
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    Route::put('/settings/update-email', [ProfileController::class, 'updateEmail'])->name('update-email');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    });


// Checkout routes
Route::middleware('web')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/order/{order}', [CheckoutController::class, 'show'])->name('order.show');
});

// Product catalog
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'catalog'])->name('products.catalog');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');

// Categories
Route::get('/kategori/{slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/dashboard', function () {
    $user = Auth::user(); // Gunakan Auth facade

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->is_admin) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('profile.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Breeze routes (default auth routes)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route group untuk admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Products routes untuk admin menggunakan ProductControllerAdmin
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ProductControllerAdmin::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\ProductControllerAdmin::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\ProductControllerAdmin::class, 'store'])->name('store');
        Route::get('/{product}', [\App\Http\Controllers\Admin\ProductControllerAdmin::class, 'show'])->name('show');
        Route::get('/{product}/edit', [\App\Http\Controllers\Admin\ProductControllerAdmin::class, 'edit'])->name('edit');
        Route::put('/{product}', [\App\Http\Controllers\Admin\ProductControllerAdmin::class, 'update'])->name('update');
        Route::delete('/{product}', [\App\Http\Controllers\Admin\ProductControllerAdmin::class, 'destroy'])->name('destroy');

        // Additional routes
        Route::put('/{product}/status', [\App\Http\Controllers\Admin\ProductControllerAdmin::class, 'updateStatus'])->name('update-status');
        Route::post('/{product}/toggle-featured', [\App\Http\Controllers\Admin\ProductControllerAdmin::class, 'toggleFeatured'])->name('toggle-featured');
    });

    // Categories routes untuk admin
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CategoryControllerAdmin::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\CategoryControllerAdmin::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\CategoryControllerAdmin::class, 'store'])->name('store');
        Route::get('/{category}/edit', [\App\Http\Controllers\Admin\CategoryControllerAdmin::class, 'edit'])->name('edit');
        Route::put('/{category}', [\App\Http\Controllers\Admin\CategoryControllerAdmin::class, 'update'])->name('update');
        Route::delete('/{category}', [\App\Http\Controllers\Admin\CategoryControllerAdmin::class, 'destroy'])->name('destroy');
    });

    // Orders routes untuk admin
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\OrderControllerAdmin::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Admin\OrderControllerAdmin::class, 'show'])->name('show');
        Route::put('/{order}/status', [\App\Http\Controllers\Admin\OrderControllerAdmin::class, 'updateStatus'])->name('update-status');
        Route::get('/{order}/edit', [\App\Http\Controllers\Admin\OrderControllerAdmin::class, 'edit'])->name('edit');
        Route::get('/statistics', [\App\Http\Controllers\Admin\OrderControllerAdmin::class, 'statistics'])->name('statistics');
        Route::put('/{order}', [\App\Http\Controllers\Admin\OrderControllerAdmin::class, 'update'])->name('update');
        Route::get('/{order}/invoice', [\App\Http\Controllers\Admin\OrderControllerAdmin::class, 'printInvoice'])->name('invoice');
        Route::get('/{order}/invoice/download', [\App\Http\Controllers\Admin\OrderControllerAdmin::class, 'downloadInvoice'])->name('invoice.download');
        Route::get('/create', [\App\Http\Controllers\Admin\OrderControllerAdmin::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\OrderControllerAdmin::class, 'store'])->name('store');
        Route::delete('/{order}', [\App\Http\Controllers\Admin\OrderControllerAdmin::class, 'destroy'])->name('destroy');
    });

    Route::get('/upload-test', function() {
    return view('upload-test');
});

Route::post('/upload-test', function(Request $request) {
    \Log::info('=== UPLOAD TEST START ===');
    \Log::info('Request has files: ' . ($request->hasFile('test_image') ? 'YES' : 'NO'));

    if ($request->hasFile('test_image')) {
        $file = $request->file('test_image');
        \Log::info('File name: ' . $file->getClientOriginalName());
        \Log::info('File size: ' . $file->getSize());
        \Log::info('File type: ' . $file->getMimeType());

        // Test simpan ke beberapa lokasi
        $testPaths = [
            'storage_public' => storage_path('app/public/test'),
            'public_uploads' => public_path('uploads/test'),
            'storage_app_public_products' => storage_path('app/public/products'),
        ];

        $results = [];
        foreach ($testPaths as $name => $path) {
            // Buat folder jika belum ada
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                \Log::info('Created directory: ' . $path);
            }

            $filename = 'test_' . time() . '_' . $name . '.' . $file->getClientOriginalExtension();
            $fullPath = $path . '/' . $filename;

            try {
                // Coba simpan
                $file->move($path, $filename);
                $results[$name] = [
                    'success' => true,
                    'path' => $fullPath,
                    'exists' => file_exists($fullPath),
                    'url' => $name === 'public_uploads'
                        ? asset('uploads/test/' . $filename)
                        : ($name === 'storage_app_public_products'
                            ? asset('storage/products/' . $filename)
                            : null),
                ];
                \Log::info('Saved to ' . $name . ': ' . $fullPath);
            } catch (\Exception $e) {
                $results[$name] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
                \Log::error('Error saving to ' . $name . ': ' . $e->getMessage());
            }
        }

        \Log::info('=== UPLOAD TEST END ===');
        return $results;
    }

    return ['error' => 'No file uploaded'];
});

Route::get('/storage-test', function() {
    // Cek storage link
    $storagePath = public_path('storage');
    $targetPath = storage_path('app/public');

    return [
        'storage_link_exists' => is_link($storagePath),
        'storage_path' => $storagePath,
        'target_path' => $targetPath,
        'is_link' => is_link($storagePath),
        'link_target' => is_link($storagePath) ? readlink($storagePath) : 'Not a link',
        'target_exists' => file_exists($targetPath),

        // Test tulis file
        'can_write_to_storage' => is_writable($targetPath),
        'can_write_to_public_storage' => is_writable($storagePath),

        // Test dengan file kecil
        'test_write' => function() use ($targetPath) {
            $testFile = $targetPath . '/test_write.txt';
            $result = file_put_contents($testFile, 'test');
            if ($result !== false) {
                unlink($testFile);
                return 'WRITE OK';
            }
            return 'WRITE FAILED';
        },
    ];
});

    Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserControllerAdmin::class, 'index'])->name('index');
    Route::get('/create', [UserControllerAdmin::class, 'create'])->name('create');
    Route::post('/', [UserControllerAdmin::class, 'store'])->name('store');
    Route::get('/statistics', [UserControllerAdmin::class, 'statistics'])->name('statistics');
    Route::get('/{user}', [UserControllerAdmin::class, 'show'])->name('show');
    Route::get('/{user}/edit', [UserControllerAdmin::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserControllerAdmin::class, 'update'])->name('update');
    Route::delete('/{user}', [UserControllerAdmin::class, 'destroy'])->name('destroy');
    Route::put('/{user}/toggle-admin', [UserControllerAdmin::class, 'toggleAdmin'])->name('toggle-admin');
    Route::put('/{user}/toggle-active', [UserControllerAdmin::class, 'toggleActive'])->name('toggle-active');
    Route::post('/{user}/impersonate', [UserControllerAdmin::class, 'impersonate'])->name('impersonate');
    Route::post('/stop-impersonate', [UserControllerAdmin::class, 'stopImpersonate'])->name('stop-impersonate');
});
});

require __DIR__.'/auth.php';

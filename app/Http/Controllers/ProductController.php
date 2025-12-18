<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductImage;
use App\Models\CartItem;
use App\Models\Cart;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource (Homepage).
     */
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->limit(8)
            ->get();

$bestSellerProducts = Product::with('category')
    ->where('is_active', true)
    ->where('sold_count', '>', 0)
    ->orderBy('sold_count', 'desc')
    ->limit(8)
    ->get();
        $categories = Category::where('is_active', true)->get();

        return view('home', compact('featuredProducts', 'bestSellerProducts', 'categories'));
    }

    /**
     * Show product catalog with filters.
     */
    public function catalog(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        // Filter by category slug
        if ($request->has('category') && $request->category != 'all') {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search products
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Price range filter
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('sold_count', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('products.catalog', compact('products', 'categories'));
    }

    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'reviews'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment view count
        $product->increment('views');

        // Related products (same category)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // For admin only - will implement later
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // For admin only - will implement later
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // For admin only - will implement later
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, CartItem $item)
{
    $request->validate([
        'quantity' => 'required|integer|min:1|max:' . $item->product->stock,
    ]);

    $item->update(['quantity' => $request->quantity]);

    // Refresh cart untuk menghitung ulang
    $cart = $item->cart;
    $cart->refresh();

    // Jika request AJAX
    if ($request->ajax() || $request->has('ajax')) {
        return response()->json([
            'success' => true,
            'subtotal' => number_format($item->quantity * $item->price, 0, ',', '.'),
            'total_items' => $cart->total_items,
            'total_price' => number_format($cart->total_price, 0, ',', '.'),
        ]);
    }

    return back()->with('success', 'Keranjang diperbarui!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // For admin only - will implement later
    }

    /**
 * Remove item from cart.
 */
public function remove(CartItem $item)
{
    $cart = $item->cart;
    $item->delete();

    // Refresh cart
    $cart->refresh();

    // Jika AJAX request
    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Produk dihapus dari keranjang',
            'total_items' => $cart->total_items,
            'total_price' => number_format($cart->total_price, 0, ',', '.'),
        ]);
    }

    return back()->with('success', 'Produk dihapus dari keranjang!');
}

/**
 * Clear all items from cart.
 */
public function clear()
{
    $cart = Cart::getOrCreateCart();
    $cart->items()->delete();
    $cart->refresh();

    // Jika AJAX request
    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan',
            'total_items' => 0,
            'total_price' => '0',
        ]);
    }

    return back()->with('success', 'Keranjang berhasil dikosongkan!');
}


}

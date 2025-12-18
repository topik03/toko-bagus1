<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::getOrCreateCart();
        $items = $cart->items()->with('product')->get();

        return view('cart.index', compact('cart', 'items'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1|max:' . $product->stock,
        ]);

        $quantity = $request->quantity ?? 1;
        $cart = Cart::getOrCreateCart();

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->discount_price ?? $product->price,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, $item)
    {
        // Cari item berdasarkan ID
        $cartItem = CartItem::findOrFail($item);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock,
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        // Jika request AJAX
        if ($request->ajax() || $request->has('ajax')) {
            $cart = $cartItem->cart;
            $cart->refresh();

            return response()->json([
                'success' => true,
                'item_subtotal' => $cartItem->quantity * $cartItem->price,
                'total_items' => $cart->total_items,
                'total_price' => number_format($cart->total_price, 0, ',', '.'),
            ]);
        }

        return back()->with('success', 'Jumlah diperbarui!');
    }

    public function remove(Request $request, $item)
    {
        // Cari item berdasarkan ID
        $cartItem = CartItem::findOrFail($item);
        $cart = $cartItem->cart;

        $cartItem->delete();
        $cart->refresh();

        // Jika request AJAX
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari keranjang',
                'total_items' => $cart->total_items,
                'total_price' => number_format($cart->total_price, 0, ',', '.'),
            ]);
        }

        return back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function clear(Request $request)
    {
        $cart = Cart::getOrCreateCart();
        $cart->items()->delete();
        $cart->refresh();

        // Jika request AJAX
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang dikosongkan',
                'total_items' => 0,
                'total_price' => '0',
            ]);
        }

        return back()->with('success', 'Keranjang berhasil dikosongkan!');
    }
}

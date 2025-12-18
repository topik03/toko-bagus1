<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Display checkout page.
     */
    public function index()
    {
        $cart = Cart::getOrCreateCart();
        $items = $cart->items()->with('product')->get();

        // Cek jika cart kosong
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        // Cek stok semua produk
        foreach ($items as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')->with('error',
                    'Stok ' . $item->product->name . ' tidak cukup! Stok tersedia: ' . $item->product->stock);
            }
        }

        // Hitung total
        $subtotal = $cart->total_price;
        $shippingCost = 10000; // Flat rate untuk sekarang
        $total = $subtotal + $shippingCost;

        // Prefill data user jika login
        $userData = [];
        if (Auth::check()) {
            $user = Auth::user();
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ];
        }

        // Payment method options untuk view
        $paymentMethods = [
            'cod' => 'COD (Bayar di Tempat)',
            'bank_transfer' => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
        ];

        return view('checkout.index', compact(
            'cart', 'items', 'subtotal', 'shippingCost', 'total',
            'userData', 'paymentMethods'
        ));
    }

    /**
     * Process checkout.
     */
    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'payment_method' => 'required|in:bank_transfer,cod,ewallet',
            'notes' => 'nullable|string',
        ]);

        $cart = Cart::getOrCreateCart();
        $cartItems = $cart->items()->with('product')->get();

        // Validasi cart
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang belanja kosong!')->withInput();
        }

        // Validasi stok
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error',
                    'Stok ' . $item->product->name . ' tidak cukup! Stok tersedia: ' . $item->product->stock)->withInput();
            }
        }

        // Hitung total dengan konversi ke float
        $subtotal = (float) $cart->total_price;
        $shippingCost = 10000; // Flat rate
        $total = $subtotal + $shippingCost;

        // Tentukan payment_status berdasarkan payment_method
        $paymentStatus = ($request->payment_method == 'cod') ? 'pending' : 'pending';

        // Order status selalu pending saat checkout
        $orderStatus = 'pending';

        // Mulai transaction
        DB::beginTransaction();

        try {
            // Buat order dengan SEMUA FIELD yang diperlukan
            $orderData = [
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postal_code' => $request->shipping_postal_code,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,  // ← TAMBAHKAN
                'order_status' => $orderStatus,      // ← TAMBAHKAN
                'notes' => $request->notes,
            ];

            \Log::info('Creating order with data:', $orderData);

            $order = Order::create($orderData);

            // Buat order items dan kurangi stok
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->quantity * $cartItem->price,
                ]);

                // Kurangi stok produk
                $product = Product::find($cartItem->product_id);
                $product->decrement('stock', $cartItem->quantity);
                $product->increment('sold_count', $cartItem->quantity);
            }

            // Kosongkan cart
            $cart->items()->delete();

            // Commit transaction
            DB::commit();

            \Log::info('Order created successfully: ' . $order->order_number);

            // Redirect ke halaman order success
            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Checkout failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display order success page.
     */
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items.product')
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    /**
     * Display order details.
     */
    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items.product')
            ->firstOrFail();

        // Authorization: cek jika user adalah pemilik order atau admin
        if (Auth::check() && $order->user_id != Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        return view('checkout.show', compact('order'));
    }
}

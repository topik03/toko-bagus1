<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderControllerAdmin extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(15);

        // Statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $completedOrders = Order::whereIn('order_status', ['completed', 'delivered'])->count();
        $totalRevenue = Order::whereIn('order_status', ['completed', 'delivered'])->sum('total');

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue'
        ));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $users = User::where('is_admin', false)->get();
        $products = Product::where('is_active', true)->where('stock', '>', 0)->get();

        return view('admin.orders.create', compact('users', 'products'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        Log::info('=== ORDER CREATE START ===');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'payment_method' => 'required|in:bank_transfer,cod,ewallet',
            'payment_status' => 'required|in:pending,paid,failed',
            'order_status' => 'required|in:pending,processing,shipped,delivered,completed,cancelled,refunded',
            'shipping_cost' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Hitung subtotal dari items
        $subtotal = 0;
        $orderItems = [];

        foreach ($request->items as $itemData) {
            $product = Product::find($itemData['product_id']);

            if (!$product) {
                return back()->withErrors(['items' => 'Produk tidak ditemukan.'])->withInput();
            }

            if ($product->stock < $itemData['quantity']) {
                return back()->withErrors(['items' => 'Stok produk ' . $product->name . ' tidak mencukupi.'])->withInput();
            }

            $itemSubtotal = $product->price * $itemData['quantity'];
            $subtotal += $itemSubtotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $itemData['quantity'],
                'subtotal' => $itemSubtotal,
            ];

            // Kurangi stok produk
            $product->decrement('stock', $itemData['quantity']);
            Log::info('Reduced stock for product ' . $product->id . ' by ' . $itemData['quantity']);
        }

        $total = $subtotal + $request->shipping_cost;

        // Buat order dengan transaction
        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $request->user_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postal_code' => $request->shipping_postal_code,
                'subtotal' => $subtotal,
                'shipping_cost' => $request->shipping_cost,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'order_status' => $request->order_status,
                'notes' => $request->notes,
            ]);

            Log::info('Order created with ID: ' . $order->id . ', Order number: ' . $order->order_number);

            // Simpan order items
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            DB::commit();
            Log::info('=== ORDER CREATE END ===');

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());

            // Kembalikan stok jika gagal
            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                if ($product) {
                    $product->increment('stock', $itemData['quantity']);
                }
            }

            return back()->withErrors(['error' => 'Gagal membuat pesanan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product.images']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $order->load(['user', 'items.product']);

        // Status options
        $statuses = [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Sampai',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
        ];

        // Payment status options
        $paymentStatuses = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'failed' => 'Gagal',
        ];

        // Payment method options
        $paymentMethods = [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
        ];

        return view('admin.orders.edit', compact('order', 'statuses', 'paymentStatuses', 'paymentMethods'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        Log::info('=== ORDER UPDATE START ===');
        Log::info('Order ID: ' . $order->id . ', Old status: ' . $order->order_status);
        Log::info('Request data received:', $request->all());

    try {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,completed,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed',
            'payment_method' => 'required|in:bank_transfer,cod,ewallet',
            'tracking_number' => 'nullable|string|max:100',
            'shipping_carrier' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

           Log::info('Validation passed');

             } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed:', $e->errors());
        Log::error('Request data was:', $request->all());
        throw $e; // Re-throw agar error ditampilkan di form
    }

        $oldStatus = $order->order_status;
        $newStatus = $request->order_status;

        // Handle status change to cancelled/refunded - kembalikan stok
        if (($oldStatus !== 'cancelled' && $oldStatus !== 'refunded') &&
            ($newStatus === 'cancelled' || $newStatus === 'refunded')) {

            Log::info('Order cancelled/refunded, returning stock...');

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                    Log::info('Returned stock for product ' . $item->product_id . ': ' . $item->quantity . ' units');
                }
            }
        }

        // Handle status change from cancelled/refunded - kurangi stok lagi
        if (($oldStatus === 'cancelled' || $oldStatus === 'refunded') &&
            ($newStatus !== 'cancelled' && $newStatus !== 'refunded')) {

            Log::info('Order reactivated, deducting stock...');

            foreach ($order->items as $item) {
                if ($item->product && $item->product->stock >= $item->quantity) {
                    $item->product->decrement('stock', $item->quantity);
                    Log::info('Deducted stock for product ' . $item->product_id . ': ' . $item->quantity . ' units');
                } else {
                    Log::warning('Insufficient stock for product ' . $item->product_id);
                }
            }
        }

        $order->update([
            'order_status' => $newStatus,
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'tracking_number' => $request->tracking_number,
            'shipping_carrier' => $request->shipping_carrier,
            'notes' => $request->notes,
        ]);

        // Log status change
        if ($oldStatus != $newStatus) {
            Log::info('Order status changed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'admin_id' => auth::id(),
            ]);
        }

        Log::info('=== ORDER UPDATE END ===');

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        Log::info('=== ORDER DELETE START ===');
        Log::info('Deleting order ID: ' . $order->id . ', Status: ' . $order->order_status);

        // Kembalikan stok jika order belum dibatalkan
        if ($order->order_status !== 'cancelled' && $order->order_status !== 'refunded') {
            Log::info('Returning stock for order items...');

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                    Log::info('Returned stock for product ' . $item->product_id . ': ' . $item->quantity . ' units');
                }
            }
        }

        // Hapus order items terlebih dahulu
        $order->items()->delete();

        // Hapus order
        $orderNumber = $order->order_number;
        $order->delete();

        Log::info('=== ORDER DELETE END ===');

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan ' . $orderNumber . ' berhasil dihapus.');
    }

    /**
     * Update order status via AJAX
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,completed,cancelled,refunded',
        ]);

        $oldStatus = $order->order_status;
        $newStatus = $request->order_status;

        // Handle stock adjustment
        if (($oldStatus !== 'cancelled' && $oldStatus !== 'refunded') &&
            ($newStatus === 'cancelled' || $newStatus === 'refunded')) {

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }

        $order->update(['order_status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
            'status' => $order->order_status,
            'status_label' => $order->order_status_label['label'],
            'status_color' => $order->order_status_label['color'],
        ]);
    }

    /**
     * Print invoice
     */
    public function printInvoice(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Show order statistics
     */
    public function statistics()
    {
        // Daily statistics for last 30 days
        $dailyOrders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as revenue')
            ->where('created_at', '>=', now()->subDays(30))
            ->whereIn('order_status', ['completed', 'delivered'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly statistics
        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count, SUM(total) as revenue')
            ->where('created_at', '>=', now()->subYear())
            ->whereIn('order_status', ['completed', 'delivered'])
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Status distribution
        $statusDistribution = Order::selectRaw('order_status, COUNT(*) as count')
            ->groupBy('order_status')
            ->get();

        // Top customers
        $topCustomers = User::select('users.id', 'users.name', 'users.email')
            ->selectSub(function($query) {
                $query->selectRaw('COUNT(*)')
                    ->from('orders')
                    ->whereColumn('user_id', 'users.id')
                    ->whereIn('order_status', ['completed', 'delivered']);
            }, 'orders_count')
            ->selectSub(function($query) {
                $query->selectRaw('COALESCE(SUM(total), 0)')
                    ->from('orders')
                    ->whereColumn('user_id', 'users.id')
                    ->whereIn('order_status', ['completed', 'delivered']);
            }, 'total_spent')
            ->where('is_admin', false)
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        return view('admin.orders.statistics', compact(
            'dailyOrders',
            'monthlyOrders',
            'statusDistribution',
            'topCustomers'
        ));
    }
}

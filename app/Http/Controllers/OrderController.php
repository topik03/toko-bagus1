<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display order history for authenticated user.
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        $query = $user->orders()->with(['items.product'])->latest();

        // Filter by status
        if ($request->status && $request->status != 'all') {
            $query->where('order_status', $request->status);
        }

        // Filter by date
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(10);

        // Stats
        $stats = [
            'total' => $user->orders()->count(),
            'processing' => $user->orders()->where('order_status', 'processing')->count(),
            'shipped' => $user->orders()->where('order_status', 'shipped')->count(),
            'delivered' => $user->orders()->where('order_status', 'delivered')->count(),
            'cancelled' => $user->orders()->where('order_status', 'cancelled')->count(),
        ];

        return view('orders.history', compact('orders', 'stats'));
    }

    /**
     * Display order details.
     */
    public function show($orderNumber)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Authorization: cek jika user adalah pemilik order atau admin
        if (Auth::id() !== $order->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel order (user only).
     */
    public function cancel(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hanya bisa cancel jika status masih processing
        if ($order->order_status != 'processing') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        $order->update([
            'order_status' => 'cancelled',
            'payment_status' => 'failed',
        ]);

        // Kembalikan stok produk
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
                $item->product->decrement('sold_count', $item->quantity);
            }
        }

        return redirect()->route('orders.history')->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Request return/refund.
     */
    public function requestReturn(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validasi: hanya bisa request return untuk order yang sudah delivered
        if ($order->order_status != 'delivered') {
            return back()->with('error', 'Hanya bisa request return untuk pesanan yang sudah diterima.');
        }

        // Dalam implementasi real, ini akan buat return request record
        // Untuk sekarang, kita update status saja
        $order->update([
            'order_status' => 'return_requested',
        ]);

        return back()->with('success', 'Permintaan return telah dikirim. Admin akan menghubungi Anda.');
    }

    /**
     * Download invoice (PDF).
     */
    public function downloadInvoice($orderNumber)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if (Auth::id() !== $order->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        // Untuk sekarang, redirect ke halaman invoice
        // Nanti bisa implementasi PDF generation
        return redirect()->route('orders.show', $orderNumber)->with('info', 'Fitur download invoice akan segera tersedia.');
    }

    /**
     * Track order (simple version).
     */
    public function track($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Status tracking steps
        $trackingSteps = [
            'processing' => [
                'title' => 'Pesanan Diproses',
                'description' => 'Pesanan Anda sedang diproses oleh penjual',
                'completed' => in_array($order->order_status, ['processing', 'shipped', 'delivered']),
                'active' => $order->order_status == 'processing',
            ],
            'shipped' => [
                'title' => 'Pesanan Dikirim',
                'description' => 'Pesanan Anda sedang dalam pengiriman',
                'completed' => in_array($order->order_status, ['shipped', 'delivered']),
                'active' => $order->order_status == 'shipped',
            ],
            'delivered' => [
                'title' => 'Pesanan Diterima',
                'description' => 'Pesanan Anda sudah diterima',
                'completed' => $order->order_status == 'delivered',
                'active' => $order->order_status == 'delivered',
            ],
        ];

        return view('orders.track', compact('order', 'trackingSteps'));
    }
}

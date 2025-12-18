<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;    // ← TAMBAHKAN INI
use App\Models\User;     // ← TAMBAHKAN INI (jika ada model User)

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),    // ← TAMBAHKAN INI
            'total_users' => User::count(),
            'recent_orders' => Order::latest()->take(5)->get(),
            'recent_products' => Product::latest()->take(5)->get(),   // ← TAMBAHKAN INI
            // tambahkan semua key yang digunakan di view
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

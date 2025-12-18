<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // Import yang benar!
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Constructor - hanya admin yang boleh akses
     */
    public function __construct()
    {
        $this->middleware('admin'); // Sekarang akan berfungsi
    }
    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_users' => User::where('is_admin', false)->count(),
            'recent_orders' => Order::latest()->take(5)->get(),
            'recent_products' => Product::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

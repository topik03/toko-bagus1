@extends('admin.layouts.admin')

@section('title', 'Dashboard Admin - Toko Bagus')
@section('page-title', 'Dashboard')

@section('content')
<div class="p-6">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-emerald-800 mb-2">
            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard Admin
        </h1>
        <p class="text-emerald-600">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Product Card -->
        <div class="bg-white border border-emerald-200 rounded-lg p-6 hover:shadow-md transition duration-300">
            <div class="flex items-center">
                <div class="p-3 bg-emerald-100 rounded-lg mr-4">
                    <i class="fas fa-box text-emerald-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-emerald-600 text-sm font-medium">Total Produk</p>
                    <p class="text-2xl font-bold text-emerald-800">{{ \App\Models\Product::count() }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-emerald-100">
                <a href="{{ route('admin.products.index') }}" class="text-emerald-700 hover:text-emerald-900 text-sm font-medium inline-flex items-center">
                    Lihat semua <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Order Card -->
        <div class="bg-white border border-blue-200 rounded-lg p-6 hover:shadow-md transition duration-300">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-blue-600 text-sm font-medium">Total Pesanan</p>
                    <p class="text-2xl font-bold text-blue-800">{{ \App\Models\Order::count() }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-blue-100">
                <a href="{{ route('admin.orders.index') }}" class="text-blue-700 hover:text-blue-900 text-sm font-medium inline-flex items-center">
                    Lihat semua <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>

        <!-- User Card -->
        <div class="bg-white border border-purple-200 rounded-lg p-6 hover:shadow-md transition duration-300">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg mr-4">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-purple-600 text-sm font-medium">Total Pengguna</p>
                    <p class="text-2xl font-bold text-purple-800">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-purple-100">
                <a href="{{ route('admin.users.index') }}" class="text-purple-700 hover:text-purple-900 text-sm font-medium inline-flex items-center">
                    Lihat semua <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Category Card -->
        <div class="bg-white border border-amber-200 rounded-lg p-6 hover:shadow-md transition duration-300">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 rounded-lg mr-4">
                    <i class="fas fa-tags text-amber-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-amber-600 text-sm font-medium">Total Kategori</p>
                    <p class="text-2xl font-bold text-amber-800">{{ \App\Models\Category::count() }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-amber-100">
                <a href="{{ route('admin.categories.index') }}" class="text-amber-700 hover:text-amber-900 text-sm font-medium inline-flex items-center">
                    Lihat semua <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white border border-emerald-200 rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-emerald-800">Quick Actions</h2>
            <span class="text-sm text-emerald-600">Akses cepat</span>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.products.index') }}"
               class="bg-emerald-500 hover:bg-emerald-600 text-white text-center py-4 rounded-lg transition duration-300 transform hover:-translate-y-1 shadow-sm">
                <div class="mb-3">
                    <i class="fas fa-box text-2xl"></i>
                </div>
                <p class="font-medium">Kelola Produk</p>
            </a>

            <a href="{{ route('admin.products.create') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white text-center py-4 rounded-lg transition duration-300 transform hover:-translate-y-1 shadow-sm">
                <div class="mb-3">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <p class="font-medium">Tambah Produk</p>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="bg-purple-500 hover:bg-purple-600 text-white text-center py-4 rounded-lg transition duration-300 transform hover:-translate-y-1 shadow-sm">
                <div class="mb-3">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <p class="font-medium">Pesanan</p>
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="bg-amber-500 hover:bg-amber-600 text-white text-center py-4 rounded-lg transition duration-300 transform hover:-translate-y-1 shadow-sm">
                <div class="mb-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <p class="font-medium">Pengguna</p>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-100">
        <div class="px-6 py-4 border-b border-emerald-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-emerald-800">Pesanan Terbaru</h2>
                <span class="text-sm text-emerald-600">5 pesanan terakhir</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-emerald-100">
                <thead class="bg-emerald-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-emerald-100">
                    @forelse(\App\Models\Order::with('user')->latest()->take(5)->get() as $order)
                    <tr class="hover:bg-emerald-50 transition duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-emerald-600 hover:text-emerald-800 font-medium inline-flex items-center">
                                #{{ $order->id }}
                                <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-emerald-900">{{ $order->user->name ?? 'Guest' }}</div>
                            <div class="text-xs text-emerald-600">{{ $order->user->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-emerald-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'shipped' => 'bg-purple-100 text-purple-800',
                                    'delivered' => 'bg-emerald-100 text-emerald-800',
                                    'completed' => 'bg-emerald-100 text-emerald-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs rounded-full font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-emerald-700">{{ $order->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-emerald-500">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center">
                            <div class="text-emerald-300 mb-3">
                                <i class="fas fa-shopping-cart text-4xl"></i>
                            </div>
                            <p class="text-emerald-600">Belum ada pesanan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(\App\Models\Order::count() > 0)
        <div class="px-6 py-4 border-t border-emerald-100 bg-emerald-50">
            <a href="{{ route('admin.orders.index') }}" class="text-emerald-600 hover:text-emerald-800 font-medium inline-flex items-center">
                Lihat semua pesanan <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

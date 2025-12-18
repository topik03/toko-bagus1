@extends('admin.layouts.admin')

@section('title', 'Kelola Pesanan - Toko Bagus')
@section('page-title', 'Kelola Pesanan')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-green-800">Daftar Pesanan</h2>
            <p class="text-green-600">Kelola semua pesanan pelanggan</p>
        </div>
        <div class="flex items-center space-x-4 mt-4 md:mt-0">
            <div class="text-right">
                <p class="text-sm text-gray-500">Total Pendapatan</p>
                <p class="text-lg font-bold text-green-700">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <i class="fas fa-shopping-cart text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Pesanan</p>
                    <p class="text-xl font-bold text-blue-700">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Menunggu</p>
                    <p class="text-xl font-bold text-yellow-700">{{ $pendingOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Selesai</p>
                    <p class="text-xl font-bold text-green-700">{{ $completedOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rata-rata</p>
                    <p class="text-xl font-bold text-purple-700">
                        Rp {{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 0, ',', '.') : 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-green-600 hover:text-green-800">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                {{ $order->items->count() }} items
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-green-700">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <!-- Ganti: -->
<span class="px-3 py-1 text-xs rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">

<!-- Menjadi: -->
@php
    $statusColors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'processing' => 'bg-blue-100 text-blue-800',
        'shipped' => 'bg-purple-100 text-purple-800',
        'delivered' => 'bg-green-100 text-green-800',
        'completed' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-red-100 text-red-800',
        'refunded' => 'bg-red-100 text-red-800',
    ];
@endphp
<span class="px-3 py-1 text-xs rounded-full {{ $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800' }}">
    {{ $order->order_status_label['label'] ?? ucfirst($order->order_status) }}
</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-green-600 hover:text-green-800" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order) }}"
                                   class="text-blue-600 hover:text-blue-800" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="text-gray-400 mb-2">
                                <i class="fas fa-shopping-cart text-4xl"></i>
                            </div>
                            <p class="text-gray-500">Belum ada pesanan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

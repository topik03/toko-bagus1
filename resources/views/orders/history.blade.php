@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Toko Bagus')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Pesanan</h1>
        <p class="text-gray-600">Lihat dan kelola semua pesanan Anda</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-500">Total Pesanan</div>
        </div>
        <div class="bg-blue-50 rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['processing'] }}</div>
            <div class="text-sm text-blue-500">Diproses</div>
        </div>
        <div class="bg-purple-50 rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['shipped'] }}</div>
            <div class="text-sm text-purple-500">Dikirim</div>
        </div>
        <div class="bg-green-50 rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-green-600">{{ $stats['delivered'] }}</div>
            <div class="text-sm text-green-500">Sampai</div>
        </div>
        <div class="bg-red-50 rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</div>
            <div class="text-sm text-red-500">Dibatalkan</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <form method="GET" action="{{ route('orders.history') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border rounded-lg px-3 py-2">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Sampai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="border rounded-lg px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="border rounded-lg px-3 py-2">
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Filter
                </button>
                <a href="{{ route('orders.history') }}"
                   class="ml-2 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @foreach($orders as $order)
            <div class="border-b hover:bg-gray-50">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <!-- Order Info -->
                        <div class="mb-4 md:mb-0">
                            <div class="flex items-center mb-2">
                                <h3 class="font-semibold text-lg">Order #{{ $order->order_number }}</h3>
                                <span class="ml-3 px-2 py-1 text-xs rounded-full
                                    {{ $order->order_status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->order_status == 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $order->order_status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->order_status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $order->order_status_label['label'] }}
                                </span>
                            </div>

                            <div class="text-sm text-gray-600">
                                <p>Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
                                <p>Total: <span class="font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</span></p>
                                <p>Metode Pembayaran: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                            </div>
                        </div>

                        <!-- Order Items Preview -->
                        <div class="mb-4 md:mb-0">
                            <div class="flex -space-x-2">
                                @foreach($order->items->take(3) as $item)
                                <div class="w-10 h-10 bg-gray-100 rounded-full border-2 border-white flex items-center justify-center">
                                    @if($item->product->main_image ?? false)
                                        <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                             alt="{{ $item->product_name }}"
                                             class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-box text-gray-400"></i>
                                    @endif
                                </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                <div class="w-10 h-10 bg-gray-200 rounded-full border-2 border-white flex items-center justify-center text-xs font-bold">
                                    +{{ $order->items->count() - 3 }}
                                </div>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mt-2">{{ $order->items->count() }} item</p>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('orders.show', $order->order_number) }}"
                               class="bg-green-600 text-white px-4 py-2 rounded text-center hover:bg-green-700 text-sm">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </a>

                            @if($order->order_status == 'processing')
                            <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST"
                                  onsubmit="return confirm('Batalkan pesanan ini?')">
                                @csrf
                                <button type="submit"
                                        class="bg-red-100 text-red-600 px-4 py-2 rounded text-center hover:bg-red-200 text-sm w-full">
                                    <i class="fas fa-times mr-1"></i> Batalkan
                                </button>
                            </form>
                            @endif

                            @if($order->order_status == 'delivered')
                            <form action="{{ route('orders.return', $order->order_number) }}" method="POST"
                                  onsubmit="return confirm('Request return untuk pesanan ini?')">
                                @csrf
                                <button type="submit"
                                        class="bg-yellow-100 text-yellow-600 px-4 py-2 rounded text-center hover:bg-yellow-200 text-sm w-full">
                                    <i class="fas fa-exchange-alt mr-1"></i> Return
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada pesanan</h3>
            <p class="text-gray-500 mb-6">Mulai belanja dan pesanan Anda akan muncul di sini</p>
            <a href="{{ route('products.catalog') }}"
               class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                <i class="fas fa-store mr-2"></i> Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection

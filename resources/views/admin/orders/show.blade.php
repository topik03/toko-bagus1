@extends('admin.layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number . ' - Toko Bagus')
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="p-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
        <p class="text-green-800">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-red-800">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
        </p>
    </div>
    @endif

    <!-- Header dengan Action Buttons -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-emerald-800">Detail Pesanan</h2>
            <p class="text-emerald-600">#{{ $order->order_number }}</p>
        </div>
        <div class="flex space-x-3 mt-4 md:mt-0">
            <a href="{{ route('admin.orders.edit', $order) }}"
               class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                <i class="fas fa-edit mr-2"></i> Edit Pesanan
            </a>
            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-print mr-2"></i> Cetak Invoice
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="px-4 py-2 border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Order Status Badge -->
    <div class="mb-6">
        <div class="inline-flex items-center px-4 py-2 rounded-full
            @if($order->order_status == 'completed' || $order->order_status == 'delivered') bg-green-100 text-green-800
            @elseif($order->order_status == 'pending') bg-yellow-100 text-yellow-800
            @elseif($order->order_status == 'processing') bg-blue-100 text-blue-800
            @elseif($order->order_status == 'shipped') bg-purple-100 text-purple-800
            @elseif($order->order_status == 'cancelled' || $order->order_status == 'refunded') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800 @endif">
            <span class="font-medium">{{ ucfirst($order->order_status) }}</span>
        </div>

        <div class="inline-flex items-center px-4 py-2 rounded-full ml-3
            @if($order->payment_status == 'paid') bg-green-100 text-green-800
            @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
            @else bg-red-100 text-red-800 @endif">
            <span class="font-medium">{{ ucfirst($order->payment_status) }}</span>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-user mr-2"></i> Informasi Pelanggan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-emerald-600">Nama Lengkap</p>
                        <p class="font-medium text-emerald-800">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Email</p>
                        <p class="font-medium text-emerald-800">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Telepon</p>
                        <p class="font-medium text-emerald-800">{{ $order->customer_phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Tanggal Pesanan</p>
                        <p class="font-medium text-emerald-800">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-truck mr-2"></i> Informasi Pengiriman
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-emerald-600">Alamat Lengkap</p>
                        <p class="font-medium text-emerald-800">{{ $order->shipping_address }}</p>
                        <p class="text-sm text-emerald-600">
                            {{ $order->shipping_city }} - {{ $order->shipping_postal_code }}
                        </p>
                    </div>

                    @if($order->tracking_number)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-emerald-600">Nomor Resi</p>
                            <p class="font-medium text-emerald-800">{{ $order->tracking_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-emerald-600">Kurir</p>
                            <p class="font-medium text-emerald-800">{{ $order->shipping_carrier ?? 'Belum ditentukan' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-shopping-cart mr-2"></i> Item Pesanan
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        @if($item->product && $item->product->images->count() > 0)
                                            <div class="flex-shrink-0 h-12 w-12 mr-3">
                                                <img src="{{ asset($item->product->images->first()->image_path) }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="h-12 w-12 object-cover rounded">
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-emerald-800">{{ $item->product_name ?? $item->product->name ?? 'Produk' }}</p>
                                            @if($item->product)
                                                <p class="text-xs text-gray-500">SKU: {{ $item->product->sku ?? 'N/A' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 font-medium text-emerald-800">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Order Summary & Actions -->
        <div class="space-y-6">
            <!-- Order Summary -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-receipt mr-2"></i> Ringkasan Pembayaran
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-3">
                        <span>Total</span>
                        <span class="text-emerald-800">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="font-medium">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status Pembayaran</span>
                        <span class="font-medium
                            @if($order->payment_status == 'paid') text-green-600
                            @elseif($order->payment_status == 'pending') text-yellow-600
                            @else text-red-600 @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->notes)
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-sticky-note mr-2"></i> Catatan Pesanan
                </h3>
                <p class="text-gray-700">{{ $order->notes }}</p>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    @if($order->order_status == 'pending')
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="inline-block w-full">
                        @csrf @method('PUT')
                        <input type="hidden" name="order_status" value="processing">
                        <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-play mr-2"></i> Proses Pesanan
                        </button>
                    </form>
                    @endif

                    @if($order->order_status == 'processing')
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="inline-block w-full">
                        @csrf @method('PUT')
                        <input type="hidden" name="order_status" value="shipped">
                        <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                        <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            <i class="fas fa-shipping-fast mr-2"></i> Tandai Sudah Dikirim
                        </button>
                    </form>
                    @endif

                    @if($order->order_status == 'shipped')
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="inline-block w-full">
                        @csrf @method('PUT')
                        <input type="hidden" name="order_status" value="delivered">
                        <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-check-circle mr-2"></i> Tandai Sudah Sampai
                        </button>
                    </form>
                    @endif

                    @if($order->order_status == 'delivered')
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="inline-block w-full">
                        @csrf @method('PUT')
                        <input type="hidden" name="order_status" value="completed">
                        <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                        <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                            <i class="fas fa-flag-checkered mr-2"></i> Tandai Selesai
                        </button>
                    </form>
                    @endif

                    @if(!in_array($order->order_status, ['cancelled', 'refunded', 'completed']))
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')"
                          class="inline-block w-full">
                        @csrf @method('PUT')
                        <input type="hidden" name="order_status" value="cancelled">
                        <input type="hidden" name="payment_status" value="failed">
                        <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-times mr-2"></i> Batalkan Pesanan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh status badge color
function updateStatusColor() {
    const status = "{{ $order->order_status }}";
    const paymentStatus = "{{ $order->payment_status }}";

    // Update order status badge
    const orderBadge = document.querySelector('.inline-flex.items-center:first-of-type');
    if (orderBadge) {
        orderBadge.className = orderBadge.className.replace(/bg-\w+-\d+ text-\w+-\d+/, '');
        orderBadge.classList.add(
            status === 'completed' || status === 'delivered' ? 'bg-green-100 text-green-800' :
            status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
            status === 'processing' ? 'bg-blue-100 text-blue-800' :
            status === 'shipped' ? 'bg-purple-100 text-purple-800' :
            status === 'cancelled' || status === 'refunded' ? 'bg-red-100 text-red-800' :
            'bg-gray-100 text-gray-800'
        );
    }

    // Update payment status text
    const paymentText = document.querySelector('.font-medium.text-green-600, .font-medium.text-yellow-600, .font-medium.text-red-600');
    if (paymentText) {
        paymentText.className = paymentText.className.replace(/text-\w+-\d+/, '');
        paymentText.classList.add(
            paymentStatus === 'paid' ? 'text-green-600' :
            paymentStatus === 'pending' ? 'text-yellow-600' :
            'text-red-600'
        );
    }
}

// Jalankan saat halaman load
document.addEventListener('DOMContentLoaded', updateStatusColor);
</script>
@endpush

@extends('layouts.app')

@section('title', 'Detail Pesanan - Toko Bagus')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('orders.history') }}" class="text-green-600 hover:text-green-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat
        </a>
    </div>

    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="p-6 border-b">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Order #{{ $order->order_number }}</h1>
                    <p class="text-gray-600">Tanggal: {{ $order->created_at->format('d F Y, H:i') }}</p>
                </div>

                <div class="mt-4 md:mt-0">
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $order->order_status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->order_status == 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $order->order_status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->order_status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            Status: {{ $order->order_status_label['label'] }}
                        </span>

                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            Pembayaran: {{ $order->payment_status == 'paid' ? 'Lunas' : 'Menunggu' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Actions -->
        <div class="p-4 bg-gray-50 border-b">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('orders.track', $order->order_number) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    <i class="fas fa-shipping-fast mr-1"></i> Lacak Pengiriman
                </a>

                <a href="{{ route('orders.invoice', $order->order_number) }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                    <i class="fas fa-file-invoice mr-1"></i> Download Invoice
                </a>

                @if($order->order_status == 'processing')
                <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST"
                      class="inline" onsubmit="return confirm('Batalkan pesanan ini?')">
                    @csrf
                    <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
                        <i class="fas fa-times mr-1"></i> Batalkan Pesanan
                    </button>
                </form>
                @endif

                @if($order->order_status == 'delivered')
                <form action="{{ route('orders.return', $order->order_number) }}" method="POST"
                      class="inline" onsubmit="return confirm('Request return untuk pesanan ini?')">
                    @csrf
                    <button type="submit"
                            class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 text-sm">
                        <i class="fas fa-exchange-alt mr-1"></i> Request Return
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Order Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
            <!-- Customer Info -->
            <div>
                <h3 class="font-semibold text-lg mb-3">Informasi Pelanggan</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Nama:</span> {{ $order->customer_name }}</p>
                    <p><span class="font-medium">Email:</span> {{ $order->customer_email }}</p>
                    <p><span class="font-medium">Telepon:</span> {{ $order->customer_phone }}</p>
                </div>
            </div>

            <!-- Shipping Address -->
            <div>
                <h3 class="font-semibold text-lg mb-3">Alamat Pengiriman</h3>
                <div class="space-y-2">
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
                </div>
            </div>

            <!-- Payment Info -->
            <div>
                <h3 class="font-semibold text-lg mb-3">Informasi Pembayaran</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Metode:</span> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                    <p><span class="font-medium">Status:</span>
                        <span class="{{ $order->payment_status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $order->payment_status == 'paid' ? 'Lunas' : 'Menunggu Pembayaran' }}
                        </span>
                    </p>
                    @if($order->notes)
                    <p><span class="font-medium">Catatan:</span> {{ $order->notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="border-t">
            <div class="p-6">
                <h3 class="font-semibold text-lg mb-4">Detail Produk</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="py-3 px-4 text-left">Produk</th>
                                <th class="py-3 px-4 text-left">Harga</th>
                                <th class="py-3 px-4 text-left">Jumlah</th>
                                <th class="py-3 px-4 text-left">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center mr-3">
                                            @if($item->product->main_image ?? false)
                                                <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                                     alt="{{ $item->product_name }}"
                                                     class="max-h-full max-w-full object-contain">
                                            @else
                                                <i class="fas fa-box text-gray-400"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-medium">{{ $item->product_name }}</h4>
                                            <p class="text-sm text-gray-500">SKU: {{ $item->product->sku ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4">
                                    {{ $item->quantity }}
                                </td>
                                <td class="py-4 px-4 font-semibold">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 p-6">
                <div class="max-w-md ml-auto">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ongkos Kirim</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        @if($order->payment_method == 'cod')
                        <div class="flex justify-between">
                            <span class="text-gray-600">Biaya COD</span>
                            <span>Rp 5,000</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold border-t pt-3">
                            <span>Total</span>
                            <span class="text-green-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Instructions (jika pending) -->
    @if($order->payment_method == 'bank_transfer' && $order->payment_status == 'pending')
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <h3 class="font-semibold text-yellow-800 mb-3">Instruksi Pembayaran</h3>
        <div class="bg-white p-4 rounded border">
            <p class="mb-3">Silakan transfer ke rekening berikut dalam 24 jam:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div class="border rounded p-3">
                    <p class="font-semibold">Bank BCA</p>
                    <p class="text-lg font-bold">1234-5678-9012</p>
                    <p class="text-sm">a.n. Toko Bagus</p>
                </div>
                <div class="border rounded p-3">
                    <p class="font-semibold">Bank Mandiri</p>
                    <p class="text-lg font-bold">9876-5432-1012</p>
                    <p class="text-sm">a.n. Toko Bagus</p>
                </div>
            </div>
            <p class="text-sm text-gray-600">Setelah transfer, konfirmasi via WhatsApp ke 0812-3456-7890 dengan menyertakan bukti transfer dan nomor order.</p>
        </div>
    </div>
    @endif
</div>
@endsection

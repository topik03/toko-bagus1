@extends('layouts.app')

@section('title', 'Pesanan Berhasil - Toko Bagus')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
            <i class="fas fa-check text-green-600 text-3xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Pesanan Berhasil!</h1>
        <p class="text-gray-600">Terima kasih telah berbelanja di Toko Bagus</p>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Order Summary -->
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">Order #{{ $order->order_number }}</h2>
                    <p class="text-gray-500">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                        {{ $order->payment_status_label['label'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-2">Informasi Pelanggan</h3>
                <div class="text-gray-600">
                    <p>{{ $order->customer_name }}</p>
                    <p>{{ $order->customer_email }}</p>
                    <p>{{ $order->customer_phone }}</p>
                </div>
            </div>

            <div>
                <h3 class="font-semibold mb-2">Alamat Pengiriman</h3>
                <div class="text-gray-600">
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="border-t">
            <div class="p-6">
                <h3 class="font-semibold mb-4">Detail Pesanan</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center mr-3">
                                @if($item->product->main_image ?? false)
                                    <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                         alt="{{ $item->product_name }}"
                                         class="max-h-full max-w-full object-contain">
                                @else
                                    <i class="fas fa-shopping-basket text-gray-400"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-medium">{{ $item->product_name }}</h4>
                                <p class="text-sm text-gray-500">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="font-semibold">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Totals -->
            <div class="bg-gray-50 p-6">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-2 mt-2">
                        <span>Total</span>
                        <span class="text-green-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        @if($order->payment_method == 'bank_transfer' && $order->payment_status == 'pending')
        <div class="border-t p-6 bg-yellow-50">
            <h3 class="font-semibold mb-3">Instruksi Pembayaran</h3>
            <div class="bg-white p-4 rounded border">
                <p class="mb-2">Silakan transfer ke rekening berikut:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="font-semibold">Bank BCA</p>
                        <p>1234-5678-9012</p>
                        <p>a.n. Toko Bagus</p>
                    </div>
                    <div>
                        <p class="font-semibold">Bank Mandiri</p>
                        <p>9876-5432-1012</p>
                        <p>a.n. Toko Bagus</p>
                    </div>
                </div>
                <p class="mt-3 text-sm text-gray-600">Lakukan pembayaran dalam 24 jam untuk menghindari pembatalan otomatis.</p>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="border-t p-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('order.show', $order->order_number) }}"
                   class="flex-1 bg-green-600 text-white text-center py-3 rounded-lg hover:bg-green-700 font-semibold">
                    <i class="fas fa-file-invoice mr-2"></i> Lihat Detail Pesanan
                </a>
                <a href="{{ route('products.catalog') }}"
                   class="flex-1 border border-green-600 text-green-600 text-center py-3 rounded-lg hover:bg-green-50 font-semibold">
                    <i class="fas fa-store mr-2"></i> Lanjut Belanja
                </a>
            </div>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="font-semibold text-blue-800 mb-3">Apa Selanjutnya?</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-envelope text-blue-600"></i>
                </div>
                <p class="text-sm text-blue-700">Konfirmasi email akan dikirim</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-shipping-fast text-blue-600"></i>
                </div>
                <p class="text-sm text-blue-700">Pesanan akan diproses dalam 1x24 jam</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-headset text-blue-600"></i>
                </div>
                <p class="text-sm text-blue-700">Hubungi kami jika ada pertanyaan</p>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Lacak Pesanan - Toko Bagus')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('orders.show', $order->order_number) }}" class="text-green-600 hover:text-green-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Pesanan
        </a>
    </div>

    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Lacak Pesanan #{{ $order->order_number }}</h1>
                <p class="text-gray-600">Status saat ini:
                    <span class="font-semibold {{ $order->order_status == 'delivered' ? 'text-green-600' : 'text-blue-600' }}">
                        {{ $order->order_status_label['label'] }}
                    </span>
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="text-sm text-gray-500">
                    <p>Tanggal Pesanan: {{ $order->created_at->format('d F Y') }}</p>
                    <p>Estimasi Sampai: {{ $order->created_at->addDays(3)->format('d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Timeline -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold">Status Pengiriman</h2>
            <p class="text-gray-600">Lacak perjalanan pesanan Anda</p>
        </div>

        <!-- Timeline -->
        <div class="p-6">
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                @foreach($trackingSteps as $key => $step)
                <div class="relative mb-10 last:mb-0">
                    <!-- Circle -->
                    <div class="absolute left-6 -translate-x-1/2 w-5 h-5 rounded-full border-4 border-white z-10
                        {{ $step['completed'] ? 'bg-green-500' : ($step['active'] ? 'bg-blue-500' : 'bg-gray-300') }}">
                    </div>

                    <!-- Content -->
                    <div class="ml-16">
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold {{ $step['completed'] ? 'text-green-700' : ($step['active'] ? 'text-blue-700' : 'text-gray-500') }}">
                                {{ $step['title'] }}
                            </h3>
                            <div class="flex items-center mt-1 md:mt-0">
                                @if($step['completed'])
                                <span class="inline-flex items-center text-sm text-green-600">
                                    <i class="fas fa-check-circle mr-1"></i> Selesai
                                </span>
                                @elseif($step['active'])
                                <span class="inline-flex items-center text-sm text-blue-600">
                                    <i class="fas fa-sync-alt mr-1 animate-spin"></i> Sedang Berlangsung
                                </span>
                                @else
                                <span class="inline-flex items-center text-sm text-gray-400">
                                    <i class="far fa-clock mr-1"></i> Menunggu
                                </span>
                                @endif
                            </div>
                        </div>

                        <p class="text-gray-600 mb-3">{{ $step['description'] }}</p>

                        <!-- Status Details -->
                        @if($key == 'processing' && $step['active'])
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                <span class="font-medium text-blue-700">Pesanan sedang diproses</span>
                            </div>
                            <ul class="text-sm text-blue-600 space-y-1 ml-6">
                                <li>• Pengecekan ketersediaan stok</li>
                                <li>• Packing produk dengan aman</li>
                                <li>• Menunggu kurir pickup</li>
                            </ul>
                        </div>
                        @endif

                        @if($key == 'shipped' && $step['active'])
                        <div class="bg-purple-50 border border-purple-100 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-shipping-fast text-purple-500 mr-2"></i>
                                <span class="font-medium text-purple-700">Pesanan dalam pengiriman</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-purple-600">Kurir</p>
                                    <p class="text-lg">JNE Express</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-purple-600">No. Resi</p>
                                    <p class="text-lg font-mono">JNE{{ $order->id }}{{ date('Ymd') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($key == 'delivered' && $step['completed'])
                        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="font-medium text-green-700">Pesanan sudah diterima</span>
                            </div>
                            <div class="text-sm text-green-600">
                                <p>Pesanan telah diterima pada: {{ $order->updated_at->format('d F Y, H:i') }}</p>
                                <p>Lokasi penerimaan: {{ $order->shipping_city }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Estimated Time -->
                        @if($step['active'])
                        <div class="mt-3 pt-3 border-t">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="far fa-clock mr-2"></i>
                                <span>Estimasi:
                                    @if($key == 'processing')
                                        Selesai dalam 1-2 jam
                                    @elseif($key == 'shipped')
                                        Sampai dalam 1-2 hari
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Delivery Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Shipping Address -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg mb-4">Alamat Pengiriman</h3>
            <div class="space-y-2">
                <div class="flex items-start">
                    <i class="fas fa-user mt-1 mr-3 text-gray-400"></i>
                    <div>
                        <p class="font-medium">{{ $order->customer_name }}</p>
                        <p class="text-gray-600">{{ $order->customer_phone }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-map-marker-alt mt-1 mr-3 text-gray-400"></i>
                    <div>
                        <p class="text-gray-800">{{ $order->shipping_address }}</p>
                        <p class="text-gray-600">{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg mb-4">Ringkasan Pesanan</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">No. Pesanan</span>
                    <span class="font-medium">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Pesan</span>
                    <span>{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Metode Pengiriman</span>
                    <span>JNE Regular</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Metode Pembayaran</span>
                    <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                </div>
                <div class="flex justify-between font-semibold border-t pt-3">
                    <span>Total Pembayaran</span>
                    <span class="text-green-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Need Help Section -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-headset text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-semibold text-blue-800">Butuh Bantuan?</h3>
                <p class="text-blue-600">Tim kami siap membantu Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-blue-700 mb-2">Hubungi Customer Service:</p>
                <a href="https://wa.me/6281234567890?text=Halo%20Toko%20Bagus,%20saya%20butuh%20bantuan%20untuk%20pesanan%20{{ $order->order_number }}"
                   target="_blank"
                   class="inline-flex items-center bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    <i class="fab fa-whatsapp mr-2"></i> WhatsApp: 0812-3456-7890
                </a>
            </div>
            <div>
                <p class="text-sm text-blue-700 mb-2">Atau kirim email ke:</p>
                <a href="mailto:cs@tokobagus.com?subject=Bantuan Pesanan {{ $order->order_number }}"
                   class="inline-flex items-center border border-blue-300 text-blue-600 px-4 py-2 rounded hover:bg-blue-50">
                    <i class="fas fa-envelope mr-2"></i> cs@tokobagus.com
                </a>
            </div>
        </div>

        <div class="mt-4 text-sm text-blue-600">
            <p><i class="fas fa-info-circle mr-1"></i> Siapkan nomor pesanan Anda saat menghubungi customer service.</p>
        </div>
    </div>
</div>
@endsection

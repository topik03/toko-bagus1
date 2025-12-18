@extends('layouts.app')

@section('title', 'Checkout - Toko Bagus')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Customer Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Informasi Pelanggan</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" name="customer_name"
                                   value="{{ old('customer_name', $userData['name'] ?? '') }}"
                                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                   required>
                            @error('customer_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Email *</label>
                            <input type="email" name="customer_email"
                                   value="{{ old('customer_email', $userData['email'] ?? '') }}"
                                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                   required>
                            @error('customer_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">No. Telepon *</label>
                            <input type="text" name="customer_phone"
                                   value="{{ old('customer_phone', $userData['phone'] ?? '') }}"
                                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                   required>
                            @error('customer_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Alamat Pengiriman</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-2">Alamat Lengkap *</label>
                            <textarea name="shipping_address" rows="3"
                                      class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                      required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 mb-2">Kota *</label>
                                <input type="text" name="shipping_city"
                                       value="{{ old('shipping_city') }}"
                                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                       required>
                                @error('shipping_city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2">Kode Pos *</label>
                                <input type="text" name="shipping_postal_code"
                                       value="{{ old('shipping_postal_code') }}"
                                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                       required>
                                @error('shipping_postal_code')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Metode Pembayaran</h2>

                    <div class="space-y-3">
                        <label class="flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="bank_transfer"
                                   class="h-5 w-5 text-green-600"
                                   {{ old('payment_method', 'bank_transfer') == 'bank_transfer' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="font-medium">Transfer Bank</span>
                                <p class="text-sm text-gray-500">BCA, Mandiri, BRI, BNI</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="cod"
                                   class="h-5 w-5 text-green-600"
                                   {{ old('payment_method') == 'cod' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="font-medium">Cash on Delivery (COD)</span>
                                <p class="text-sm text-gray-500">Bayar saat barang sampai</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="ewallet"
                                   class="h-5 w-5 text-green-600"
                                   {{ old('payment_method') == 'ewallet' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="font-medium">E-Wallet</span>
                                <p class="text-sm text-gray-500">Dana, OVO, Gopay, LinkAja</p>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Notes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Catatan Pesanan (Optional)</h2>
                    <textarea name="notes" rows="3" placeholder="Contoh: Tolong dikirim sebelum jam 3 sore"
                              class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-4">Ringkasan Pesanan</h2>

                    <!-- Order Items -->
                    <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                        @foreach($items as $item)
                        <div class="flex items-center border-b pb-4">
                            <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center mr-3">
                                @if($item->product->main_image)
                                    <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                         alt="{{ $item->product->name }}"
                                         class="max-h-full max-w-full object-contain">
                                @else
                                    <i class="fas fa-shopping-basket text-gray-400"></i>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-medium">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="font-semibold">
                                Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Order Totals -->
                    <div class="space-y-3 border-t pt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Ongkos Kirim</span>
                            <span class="font-medium">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between text-lg font-bold border-t pt-3">
                            <span>Total</span>
                            <span class="text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Terms and Submit -->
                    <div class="mt-6">
                        <div class="flex items-center mb-4">
                            <input type="checkbox" id="terms" class="h-4 w-4 text-green-600" required>
                            <label for="terms" class="ml-2 text-sm text-gray-600">
                                Saya setuju dengan <a href="#" class="text-green-600 hover:underline">Syarat & Ketentuan</a>
                            </label>
                        </div>

                        <button type="submit"
                                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-semibold text-lg transition">
                            Buat Pesanan
                        </button>

                        <p class="text-sm text-gray-500 text-center mt-3">
                            Dengan melanjutkan, Anda menyetujui kebijakan kami
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('terms').addEventListener('change', function() {
    document.querySelector('button[type="submit"]').disabled = !this.checked;
});

// Set submit button disabled awal
document.querySelector('button[type="submit"]').disabled = true;
</script>
@endpush
@endsection

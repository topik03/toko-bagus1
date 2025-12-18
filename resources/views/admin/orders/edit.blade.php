@extends('admin.layouts.admin')

@section('title', 'Edit Pesanan #' . $order->order_number . ' - Toko Bagus')
@section('page-title', 'Edit Pesanan')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.orders.show', $order) }}"
           class="inline-flex items-center text-emerald-600 hover:text-emerald-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Pesanan
        </a>
    </div>

    <!-- TAMPILKAN ERROR JIKA ADA -->
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <h3 class="font-bold text-red-800 mb-2">
            <i class="fas fa-exclamation-triangle mr-2"></i> Terjadi Kesalahan Validasi
        </h3>
        <ul class="text-sm text-red-600 list-disc pl-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-200">
        <div class="px-6 py-4 border-b border-emerald-100">
            <h2 class="text-lg font-semibold text-emerald-800">
                <i class="fas fa-edit mr-2"></i> Edit Pesanan #{{ $order->order_number }}
            </h2>
            <p class="text-sm text-emerald-600 mt-1">Update status dan informasi pesanan</p>
        </div>

        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- ==== HIDDEN INPUT UNTUK PAYMENT_METHOD ==== -->
            <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
            <!-- ========================================== -->

            <!-- Order Summary -->
            <div class="mb-8 bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <h3 class="font-semibold text-emerald-800 mb-3">Ringkasan Pesanan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-emerald-600">Pelanggan</p>
                        <p class="font-medium text-emerald-800">{{ $order->customer_name }}</p>
                        <p class="text-sm text-emerald-600">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Tanggal Pesanan</p>
                        <p class="font-medium text-emerald-800">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Total</p>
                        <p class="font-medium text-emerald-800">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="mb-6">
                <label for="order_status" class="block text-sm font-medium text-emerald-700 mb-2">
                    Status Pesanan <span class="text-red-500">*</span>
                </label>
                <select name="order_status" id="order_status"
                        class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ $order->order_status == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-emerald-500 mt-1">Status saat ini:
                    <span class="font-medium {{ $order->order_status_label['color'] == 'green' ? 'text-green-600' :
                                              ($order->order_status_label['color'] == 'red' ? 'text-red-600' :
                                              ($order->order_status_label['color'] == 'yellow' ? 'text-yellow-600' :
                                              ($order->order_status_label['color'] == 'blue' ? 'text-blue-600' :
                                              ($order->order_status_label['color'] == 'purple' ? 'text-purple-600' : 'text-gray-600')))) }}">
                        {{ $order->order_status_label['label'] }}
                    </span>
                </p>
            </div>

            <!-- Payment Status -->
            <div class="mb-6">
                <label for="payment_status" class="block text-sm font-medium text-emerald-700 mb-2">
                    Status Pembayaran <span class="text-red-500">*</span>
                </label>
                <select name="payment_status" id="payment_status"
                        class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @foreach($paymentStatuses as $value => $label)
                        <option value="{{ $value }}" {{ $order->payment_status == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-emerald-500 mt-1">Status saat ini:
                    <span class="font-medium {{ $order->payment_status_label['color'] == 'green' ? 'text-green-600' :
                                              ($order->payment_status_label['color'] == 'red' ? 'text-red-600' :
                                              ($order->payment_status_label['color'] == 'yellow' ? 'text-yellow-600' : 'text-gray-600')) }}">
                        {{ $order->payment_status_label['label'] }}
                    </span>
                </p>
            </div>

            <!-- Shipping Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="tracking_number" class="block text-sm font-medium text-emerald-700 mb-2">
                        Nomor Resi
                    </label>
                    <input type="text"
                           name="tracking_number"
                           id="tracking_number"
                           value="{{ old('tracking_number', $order->tracking_number) }}"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           placeholder="Contoh: JNE123456789">
                </div>

                <div>
                    <label for="shipping_carrier" class="block text-sm font-medium text-emerald-700 mb-2">
                        Kurir Pengiriman
                    </label>
                    <input type="text"
                           name="shipping_carrier"
                           id="shipping_carrier"
                           value="{{ old('shipping_carrier', $order->shipping_carrier) }}"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           placeholder="Contoh: JNE, TIKI, POS, GoSend">
                </div>
            </div>

            <!-- Customer & Shipping Info -->
            <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-emerald-800 mb-3">Informasi Pengiriman</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-emerald-600">Nama Penerima</p>
                        <p class="font-medium text-emerald-800">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Telepon</p>
                        <p class="font-medium text-emerald-800">{{ $order->customer_phone }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-emerald-600">Alamat Pengiriman</p>
                        <p class="font-medium text-emerald-800">{{ $order->shipping_address }}</p>
                        <p class="text-sm text-emerald-600">
                            {{ $order->shipping_city }} - {{ $order->shipping_postal_code }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-6">
                <h3 class="font-semibold text-emerald-800 mb-3">Item Pesanan</h3>
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
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        @if($item->product && $item->product->images->count() > 0)
                                            <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                <img src="{{ asset($item->product->images->first()->image_path) }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="h-10 w-10 object-cover rounded">
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-emerald-800">{{ $item->product_name ?? $item->product->name ?? 'Produk' }}</p>
                                            @if($item->product)
                                                <p class="text-xs text-gray-500">{{ $item->product->sku ?? 'N/A' }}</p>
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
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-medium">Subtotal:</td>
                                <td class="px-4 py-3 font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-medium">Ongkos Kirim:</td>
                                <td class="px-4 py-3 font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold text-lg">Total:</td>
                                <td class="px-4 py-3 font-bold text-lg text-emerald-800">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-emerald-700 mb-2">
                    Catatan Tambahan (Opsional)
                </label>
                <textarea name="notes"
                          id="notes"
                          rows="3"
                          class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                          placeholder="Catatan untuk pesanan ini...">{{ old('notes', $order->notes) }}</textarea>
                @if($order->notes)
                    <p class="text-xs text-emerald-500 mt-1">
                        <span class="font-medium">Catatan saat ini:</span> {{ $order->notes }}
                    </p>
                @endif
            </div>

            <!-- Payment Info -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-emerald-800 mb-3">Informasi Pembayaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-emerald-600">Metode Pembayaran</p>
                        <p class="font-medium text-emerald-800">{{ ucfirst($order->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Status Pembayaran</p>
                        <span class="px-3 py-1 text-xs rounded-full
                            {{ $order->payment_status_label['color'] == 'green' ? 'bg-green-100 text-green-800' :
                              ($order->payment_status_label['color'] == 'red' ? 'bg-red-100 text-red-800' :
                              ($order->payment_status_label['color'] == 'yellow' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ $order->payment_status_label['label'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-emerald-100">
                <a href="{{ route('admin.orders.show', $order) }}"
                   class="px-6 py-2 border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-red-200">
            <h3 class="text-lg font-semibold text-red-800">
                <i class="fas fa-exclamation-triangle mr-2"></i> Zona Berbahaya
            </h3>
            <p class="text-sm text-red-600 mt-1">Aksi ini tidak dapat dibatalkan</p>
        </div>
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="font-medium text-red-800">Batalkan Pesanan</h4>
                    <p class="text-sm text-red-600 mt-1">Pesanan akan dibatalkan dan stok produk akan dikembalikan</p>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="order_status" value="cancelled">
                    <input type="hidden" name="payment_status" value="failed">
                    <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                    <button type="submit"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center">
                        <i class="fas fa-ban mr-2"></i> Batalkan Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Update status color preview
    document.getElementById('order_status').addEventListener('change', function() {
        const statusColors = {
            'pending': 'text-yellow-600',
            'processing': 'text-blue-600',
            'shipped': 'text-purple-600',
            'delivered': 'text-green-600',
            'completed': 'text-green-600',
            'cancelled': 'text-red-600',
            'refunded': 'text-red-600'
        };

        const statusLabels = {
            'pending': 'Menunggu',
            'processing': 'Diproses',
            'shipped': 'Dikirim',
            'delivered': 'Sampai',
            'completed': 'Selesai',
            'cancelled': 'Dibatalkan',
            'refunded': 'Dikembalikan'
        };

        const selectedStatus = this.value;
        const previewElement = document.querySelector('#order_status + p span');

        if (previewElement) {
            previewElement.className = 'font-medium ' + (statusColors[selectedStatus] || 'text-gray-600');
            previewElement.textContent = statusLabels[selectedStatus] || selectedStatus;
        }
    });

    // Update payment status color preview
    document.getElementById('payment_status').addEventListener('change', function() {
        const paymentColors = {
            'pending': 'text-yellow-600',
            'paid': 'text-green-600',
            'failed': 'text-red-600'
        };

        const paymentLabels = {
            'pending': 'Menunggu Pembayaran',
            'paid': 'Sudah Dibayar',
            'failed': 'Gagal'
        };

        const selectedPayment = this.value;
        const previewElement = document.querySelector('#payment_status + p span');

        if (previewElement) {
            previewElement.className = 'font-medium ' + (paymentColors[selectedPayment] || 'text-gray-600');
            previewElement.textContent = paymentLabels[selectedPayment] || selectedPayment;
        }
    });
</script>
@endsection

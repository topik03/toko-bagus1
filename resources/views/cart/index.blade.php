@extends('layouts.app')

@section('title', 'Keranjang Belanja - Toko Bagus')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Keranjang Belanja</h1>

    @if($items->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Cart Items -->
        <div class="divide-y" id="cart-items-container">
            @foreach($items as $item)
            <div class="p-6 flex items-center cart-item" id="cart-item-{{ $item->id }}">
                <!-- Product Image -->
                <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center mr-6">
                    @if($item->product->images && $item->product->images->count() > 0)
                        <img src="{{ asset($item->product->images->first()->image_path) }}"
                             alt="{{ $item->product->name }}"
                             class="max-h-full max-w-full object-contain">
                    @elseif($item->product->main_image)
                        <img src="{{ asset('storage/' . $item->product->main_image) }}"
                             alt="{{ $item->product->name }}"
                             class="max-h-full max-w-full object-contain">
                    @else
                        <i class="fas fa-shopping-basket text-gray-400 text-2xl"></i>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="flex-grow">
                    <h3 class="font-semibold text-lg">{{ $item->product->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $item->product->category->name ?? 'Tidak ada kategori' }}</p>

                    <!-- Price -->
                    <div class="mt-2">
                        <span class="text-lg font-bold text-green-600">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </span>
                        <span class="text-gray-500 text-sm ml-2">per {{ $item->product->unit }}</span>
                    </div>
                </div>

                <!-- Quantity Controls -->
                <div class="mx-8">
                    <div class="flex items-center border rounded-lg">
                        <button type="button"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 decrease-btn"
                                onclick="updateQuantity({{ $item->id }}, 'decrease')">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="w-16 text-center py-2" id="quantity-{{ $item->id }}">
                            {{ $item->quantity }}
                        </span>
                        <button type="button"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 increase-btn"
                                onclick="updateQuantity({{ $item->id }}, 'increase')"
                                data-max="{{ $item->product->stock }}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Stok: {{ $item->product->stock }}</p>
                </div>

                <!-- Subtotal & Remove -->
                <div class="text-right">
                    <p class="text-lg font-bold" id="subtotal-{{ $item->id }}">
                        Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                    </p>
                    <!-- SIMPLE DELETE BUTTON -->
                    <button type="button"
                            class="mt-2 text-red-500 hover:text-red-700 delete-btn"
                            onclick="deleteItem({{ $item->id }}, '{{ $item->product->name }}')">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Cart Summary -->
        <div class="bg-gray-50 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <!-- SIMPLE CLEAR BUTTON -->
                    <button type="button"
                            class="text-red-500 hover:text-red-700"
                            onclick="clearCart()">
                        <i class="fas fa-trash mr-1"></i> Kosongkan Keranjang
                    </button>
                </div>

                <div class="text-right">
                    <p class="text-gray-600">Total Items: <span id="total-items">{{ $cart->total_items }}</span></p>
                    <p class="text-2xl font-bold mt-2">
                        Total: <span id="total-price">Rp {{ number_format($cart->total_price, 0, ',', '.') }}</span>
                    </p>
                    <a href="{{ route('checkout') }}"
                       class="inline-block mt-4 bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 font-semibold">
                        <i class="fas fa-credit-card mr-2"></i> Lanjut ke Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Continue Shopping -->
    <div class="mt-8 text-center">
        <a href="{{ route('products.catalog') }}" class="text-green-600 hover:text-green-700">
            <i class="fas fa-arrow-left mr-2"></i> Lanjutkan Belanja
        </a>
    </div>
    @else
    <!-- Empty Cart -->
    <div class="text-center py-12 bg-white rounded-lg shadow">
        <i class="fas fa-shopping-cart text-gray-400 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Keranjang Belanja Kosong</h3>
        <p class="text-gray-500 mb-6">Tambahkan produk ke keranjang untuk mulai belanja</p>
        <a href="{{ route('products.catalog') }}"
           class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
            <i class="fas fa-store mr-2"></i> Lihat Produk
        </a>
    </div>
    @endif
</div>

<!-- SIMPLE JAVASCRIPT YANG PASTI BEKERJA -->
<script>
// Function untuk update quantity
async function updateQuantity(itemId, action) {
    const quantityElement = document.getElementById(`quantity-${itemId}`);
    const currentQuantity = parseInt(quantityElement.textContent);
    const maxStock = document.querySelector(`button[onclick*="${itemId}"].increase-btn`).getAttribute('data-max');
    let newQuantity = currentQuantity;

    // Tentukan quantity baru
    if (action === 'increase' && currentQuantity < maxStock) {
        newQuantity = currentQuantity + 1;
    } else if (action === 'decrease' && currentQuantity > 1) {
        newQuantity = currentQuantity - 1;
    } else {
        if (action === 'increase') {
            alert(`Stok maksimal: ${maxStock}`);
        }
        return;
    }

    // Update UI langsung
    quantityElement.textContent = newQuantity;

    // Kirim ke server
    try {
        const response = await fetch(`/cart/update/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                quantity: newQuantity,
                ajax: true
            })
        });

        const data = await response.json();

        if (data.success) {
            // Update subtotal
            const price = {{ $item->price ?? 0 }}; // Ganti dengan cara dapatkan harga
            const subtotalElement = document.getElementById(`subtotal-${itemId}`);
            if (subtotalElement) {
                const subtotal = price * newQuantity;
                subtotalElement.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            }

            // Update totals jika ada di response
            if (data.total_items !== undefined) {
                document.getElementById('total-items').textContent = data.total_items;
            }
            if (data.total_price) {
                document.getElementById('total-price').textContent = 'Rp ' + data.total_price;
            }

            showNotification('Jumlah berhasil diperbarui', 'success');
        } else {
            // Revert UI jika gagal
            quantityElement.textContent = currentQuantity;
            showNotification('Gagal memperbarui jumlah', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        quantityElement.textContent = currentQuantity;
        showNotification('Terjadi kesalahan', 'error');
    }
}

// Function untuk hapus item
async function deleteItem(itemId, productName) {
    if (!confirm(`Hapus "${productName}" dari keranjang?`)) {
        return;
    }

    const button = document.querySelector(`button[onclick*="deleteItem(${itemId},"]`);
    const originalHtml = button.innerHTML;

    // Tampilkan loading
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menghapus...';

    try {
        const response = await fetch(`/cart/remove/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ajax: true })
        });

        const data = await response.json();

        if (data.success) {
            // Hapus item dari DOM
            const itemElement = document.getElementById(`cart-item-${itemId}`);
            if (itemElement) {
                itemElement.style.opacity = '0.5';
                setTimeout(() => itemElement.remove(), 300);
            }

            // Update totals
            if (data.total_items !== undefined) {
                document.getElementById('total-items').textContent = data.total_items;
            }
            if (data.total_price) {
                document.getElementById('total-price').textContent = 'Rp ' + data.total_price;
            }

            showNotification(data.message || 'Produk berhasil dihapus', 'success');

            // Jika keranjang kosong, reload
            if (data.total_items === 0) {
                setTimeout(() => location.reload(), 1000);
            }
        } else {
            button.disabled = false;
            button.innerHTML = originalHtml;
            showNotification(data.message || 'Gagal menghapus produk', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        button.disabled = false;
        button.innerHTML = originalHtml;
        showNotification('Terjadi kesalahan sistem', 'error');
    }
}

// Function untuk kosongkan keranjang
async function clearCart() {
    if (!confirm('Kosongkan seluruh keranjang belanja?')) {
        return;
    }

    try {
        const response = await fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ajax: true })
        });

        const data = await response.json();

        if (data.success) {
            showNotification(data.message || 'Keranjang berhasil dikosongkan', 'success');

            // Kosongkan DOM
            document.getElementById('cart-items-container').innerHTML = `
                <div class="p-8 text-center">
                    <i class="fas fa-shopping-cart text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600">Keranjang belanja kosong</p>
                </div>
            `;

            // Update totals
            document.getElementById('total-items').textContent = '0';
            document.getElementById('total-price').textContent = 'Rp 0';

            // Update navbar counter
            updateCartCounter(0);
        } else {
            showNotification(data.message || 'Gagal mengosongkan keranjang', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan sistem', 'error');
    }
}

// Helper functions
function showNotification(message, type = 'success') {
    // Hapus notifikasi lama
    const oldNotifications = document.querySelectorAll('.cart-notification');
    oldNotifications.forEach(notification => notification.remove());

    // Buat notifikasi baru
    const notification = document.createElement('div');
    notification.className = `cart-notification fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3"></i>
            <div>
                <p class="font-medium">${message}</p>
            </div>
            <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Auto remove setelah 3 detik
    setTimeout(() => notification.remove(), 3000);
}

function updateCartCounter(count) {
    const counters = document.querySelectorAll('.cart-counter, .cart-count');
    counters.forEach(counter => {
        counter.textContent = count;
    });
}

// Tambahkan CSS untuk notifikasi
const style = document.createElement('style');
style.textContent = `
    .cart-notification {
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection

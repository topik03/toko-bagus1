@extends('layouts.app')

@section('title', $product->name . ' - Toko Bagus')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm">
        <ol class="flex space-x-2">
            <li><a href="{{ route('home') }}" class="text-green-600 hover:text-green-700">Beranda</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li><a href="{{ route('products.catalog') }}" class="text-green-600 hover:text-green-700">Produk</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li><a href="{{ route('products.catalog') }}?category={{ $product->category->slug }}"
                   class="text-green-600 hover:text-green-700">{{ $product->category->name }}</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li class="text-gray-500">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Detail -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <!-- Product Images -->
            <div class="md:w-1/2 p-8">
                <div class="bg-gray-100 rounded-lg h-96 flex items-center justify-center">
                    @if($product->images && $product->images->count() > 0)
                        <img src="{{ asset($product->images->first()->image_path) }}"
                             alt="{{ $product->name }}"
                             class="max-h-full max-w-full object-contain">
                    @else
                        <i class="fas fa-shopping-basket text-gray-400 text-8xl"></i>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="md:w-1/2 p-8">
                <!-- Category -->
                <span class="inline-block bg-gray-100 text-gray-600 text-sm px-3 py-1 rounded-full mb-4">
                    {{ $product->category->name }}
                </span>

                <!-- Product Name -->
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>

                <!-- SKU -->
                <p class="text-gray-500 text-sm mb-4">SKU: {{ $product->sku ?? 'N/A' }}</p>

                <!-- Price -->
                <div class="mb-6">
                    @if($product->has_discount)
                        <div class="flex items-center mb-2">
                            <span class="text-3xl font-bold text-red-600">
                                Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                            </span>
                            <span class="text-xl text-gray-400 line-through ml-4">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <span class="ml-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                Hemat {{ $product->discount_percentage }}%
                            </span>
                        </div>
                    @else
                        <div class="text-3xl font-bold text-gray-800">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                    @endif
                    <p class="text-gray-500 text-sm">Per {{ $product->unit }} ({{ $product->weight }}g)</p>
                </div>

                <!-- Stock Status -->
                <div class="mb-6">
                    @if($product->stock > 0)
                        <span class="text-green-600 font-semibold">
                            <i class="fas fa-check-circle mr-2"></i>Stok Tersedia: {{ $product->stock }}
                        </span>
                    @else
                        <span class="text-red-600 font-semibold">
                            <i class="fas fa-times-circle mr-2"></i>Stok Habis
                        </span>
                    @endif
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h3 class="font-semibold text-lg mb-2">Deskripsi Produk</h3>
                    <p class="text-gray-600">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                </div>

                <!-- ========== BAGIAN ADD TO CART YANG DIPERBAIKI ========== -->
                <!-- Add to Cart -->
                <div class="flex space-x-4">
                    <!-- Quantity Controls -->
                    <div class="flex items-center border rounded-lg">
                        <button type="button"
                                class="px-4 py-3 text-gray-600 hover:text-gray-800 quantity-minus">-</button>
                        <input type="number"
                               id="quantity-input"
                               value="1"
                               min="1"
                               max="{{ $product->stock }}"
                               class="w-16 text-center border-x py-3">
                        <button type="button"
                                class="px-4 py-3 text-gray-600 hover:text-gray-800 quantity-plus">+</button>
                    </div>

                    <!-- Add to Cart Form (SAMA dengan product-card) -->
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="quantity" id="form-quantity" value="1">

                        <button type="submit"
                                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-semibold"
                                {{ $product->stock == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-cart-plus mr-2"></i> Tambah ke Keranjang
                        </button>
                    </form>

                    <!-- Wishlist Button -->
                    <button type="button"
                            class="px-6 border border-green-600 text-green-600 rounded-lg hover:bg-green-50">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                <!-- ========== END BAGIAN ADD TO CART ========== -->

                <!-- Product Stats -->
                <div class="mt-8 pt-8 border-t grid grid-cols-3 text-center">
                    <div>
                        <div class="text-2xl font-bold text-gray-800">{{ $product->sold_count }}</div>
                        <div class="text-gray-500 text-sm">Terjual</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">{{ $product->views }}</div>
                        <div class="text-gray-500 text-sm">Dilihat</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">4.8</div>
                        <div class="text-gray-500 text-sm">Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
                @include('components.product-card', ['product' => $relatedProduct])
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- JavaScript untuk quantity control -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity-input');
    const formQuantity = document.getElementById('form-quantity');
    const minusBtn = document.querySelector('.quantity-minus');
    const plusBtn = document.querySelector('.quantity-plus');

    // Update form quantity ketika input berubah
    if (quantityInput && formQuantity) {
        quantityInput.addEventListener('change', function() {
            formQuantity.value = this.value;
        });

        // Minus button
        if (minusBtn) {
            minusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                    formQuantity.value = quantityInput.value;
                }
            });
        }

        // Plus button
        if (plusBtn) {
            plusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                let maxStock = parseInt(quantityInput.max);
                if (currentValue < maxStock) {
                    quantityInput.value = currentValue + 1;
                    formQuantity.value = quantityInput.value;
                }
            });
        }
    }
});
</script>
@endsection

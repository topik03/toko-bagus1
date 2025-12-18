<div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
    <!-- Product Image -->
    <div class="relative h-48 bg-gray-200 overflow-hidden">
        @if($product->images && $product->images->count() > 0)
            <img src="{{ asset($product->images->first()->image_path) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover hover:scale-105 transition duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <i class="fas fa-shopping-basket text-gray-400 text-4xl"></i>
            </div>
        @endif

        <!-- Discount Badge -->
        @if($product->has_discount)
            <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                -{{ $product->discount_percentage }}%
            </div>
        @endif

        <!-- Featured Badge -->
        @if($product->is_featured)
            <div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded text-xs font-bold">
                <i class="fas fa-star mr-1"></i> Unggulan
            </div>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-4">
        <!-- Category -->
        <div class="mb-2">
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                {{ $product->category->name ?? 'Uncategorized' }}
            </span>
        </div>

        <!-- Product Name -->
        <h3 class="font-semibold text-gray-800 mb-2 truncate">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-green-600">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Price -->
        <div class="flex items-center mb-3">
            @if($product->has_discount)
                <span class="text-red-600 font-bold text-lg">
                    Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                </span>
                <span class="text-gray-400 line-through text-sm ml-2">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
            @else
                <span class="text-gray-800 font-bold text-lg">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
            @endif
            <span class="text-gray-500 text-sm ml-2">/{{ $product->unit }}</span>
        </div>

        <!-- Stock & Weight -->
        <div class="flex justify-between text-sm text-gray-500 mb-4">
            <span>
                <i class="fas fa-box mr-1"></i> Stok: {{ $product->stock }}
            </span>
            <span>
                <i class="fas fa-weight mr-1"></i> {{ $product->weight }}g
            </span>
        </div>


        <!-- Action Buttons -->
        <div class="flex space-x-2 mt-2">
            <!-- Detail Button -->
            <a href="{{ route('products.show', $product->slug) }}"
               class="flex-1 bg-gray-100 text-gray-800 text-center py-2 rounded hover:bg-gray-200 transition">
                <i class="fas fa-eye mr-2"></i> Detail
            </a>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition
                               {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $product->stock == 0 ? 'disabled' : '' }}>
                    <i class="fas fa-cart-plus"></i>
                </button>
            </form>
        </div>
    </div>
</div>

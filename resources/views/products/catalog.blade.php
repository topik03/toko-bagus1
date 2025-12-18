@extends('layouts.app')

@section('title', 'Katalog Produk - Toko Bagus')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Sidebar Filters -->
    <div class="lg:w-1/4">
        <div class="bg-white p-6 rounded-lg shadow sticky top-4">
            <h3 class="font-bold text-lg mb-4">Filter Produk</h3>

            <!-- Categories Filter -->
            <div class="mb-6">
                <h4 class="font-semibold mb-2">Kategori</h4>
                <div class="space-y-2">
                    <a href="{{ route('products.catalog') }}"
                       class="block text-gray-700 hover:text-green-600 {{ !request('category') ? 'font-bold text-green-600' : '' }}">
                        Semua Kategori
                    </a>
                    @foreach($categories as $category)
                    <a href="{{ route('products.catalog') }}?category={{ $category->slug }}"
                       class="block text-gray-700 hover:text-green-600 {{ request('category') == $category->slug ? 'font-bold text-green-600' : '' }}">
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Price Range -->
            <div class="mb-6">
                <h4 class="font-semibold mb-2">Rentang Harga</h4>
                <form method="GET" action="{{ route('products.catalog') }}">
                    <div class="flex space-x-2 mb-2">
                        <input type="number" name="min_price" placeholder="Min"
                               value="{{ request('min_price') }}"
                               class="w-1/2 border rounded px-2 py-1">
                        <input type="number" name="max_price" placeholder="Max"
                               value="{{ request('max_price') }}"
                               class="w-1/2 border rounded px-2 py-1">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                        Terapkan Filter
                    </button>
                </form>
            </div>

            <!-- Sorting -->
            <div class="mb-6">
                <h4 class="font-semibold mb-2">Urutkan</h4>
                <div class="space-y-2">
                    <a href="{{ route('products.catalog', array_merge(request()->query(), ['sort' => 'newest'])) }}"
                       class="block text-gray-700 hover:text-green-600 {{ request('sort', 'newest') == 'newest' ? 'font-bold text-green-600' : '' }}">
                        Terbaru
                    </a>
                    <a href="{{ route('products.catalog', array_merge(request()->query(), ['sort' => 'price_low'])) }}"
                       class="block text-gray-700 hover:text-green-600 {{ request('sort') == 'price_low' ? 'font-bold text-green-600' : '' }}">
                        Harga Terendah
                    </a>
                    <a href="{{ route('products.catalog', array_merge(request()->query(), ['sort' => 'price_high'])) }}"
                       class="block text-gray-700 hover:text-green-600 {{ request('sort') == 'price_high' ? 'font-bold text-green-600' : '' }}">
                        Harga Tertinggi
                    </a>
                    <a href="{{ route('products.catalog', array_merge(request()->query(), ['sort' => 'popular'])) }}"
                       class="block text-gray-700 hover:text-green-600 {{ request('sort') == 'popular' ? 'font-bold text-green-600' : '' }}">
                        Terlaris
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Product List -->
    <div class="lg:w-3/4">
        <!-- Search Bar -->
        <div class="mb-6">
            <form method="GET" action="{{ route('products.catalog') }}" class="flex">
                <input type="text" name="search" placeholder="Cari produk..."
                       value="{{ request('search') }}"
                       class="flex-grow border border-r-0 rounded-l-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                <button type="submit" class="bg-green-600 text-white px-6 rounded-r-lg hover:bg-green-700">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Results Info -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Semua Produk</h1>
                @if(request('search'))
                <p class="text-gray-600">Hasil pencarian: "{{ request('search') }}"</p>
                @endif
            </div>
            <p class="text-gray-600">{{ $products->total() }} produk ditemukan</p>
        </div>

        <!-- Product Grid -->
        @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
        @else
        <div class="text-center py-12 bg-gray-100 rounded-lg">
            <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Produk tidak ditemukan</h3>
            <p class="text-gray-500">Coba gunakan kata kunci lain atau hapus filter</p>
            <a href="{{ route('products.catalog') }}" class="inline-block mt-4 text-green-600 hover:text-green-700">
                Lihat semua produk
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

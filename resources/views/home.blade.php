@extends('layouts.app')

@section('title', 'Toko Bagus - Toko Sembako Online Terpercaya')

@section('content')
<!-- Hero Section - PAKAI CLASS CUSTOM -->
<section class="hero-gradient text-white py-12 mb-8 rounded-lg">
    <div class="container px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Selamat Datang di Toko Bagus</h1>
        <p class="text-xl mb-8">Toko sembako online dengan harga terjangkau dan kualitas terbaik</p>
        <a href="{{ route('products.catalog') }}"
           class="bg-white text-green-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition inline-block">
            Belanja Sekarang <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>

<!-- Categories Section -->
<section class="mb-12">
    <div class="container px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Kategori Produk</h2>
            <a href="{{ route('products.catalog') }}" class="text-green-600 hover:text-green-700">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('products.catalog') }}?category={{ $category->slug }}"
               class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition text-center group">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-green-200">
                    <i class="fas fa-shopping-basket text-green-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 group-hover:text-green-600">{{ $category->name }}</h3>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="mb-12">
    <div class="container px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Produk Unggulan</h2>

        @if($featuredProducts->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
        @else
        <div class="text-center py-8 bg-gray-100 rounded-lg">
            <p class="text-gray-500">Belum ada produk unggulan</p>
        </div>
        @endif
    </div>
</section>

<!-- Best Seller Products -->
<section class="mb-12">
    <div class="container px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Produk Terlaris</h2>
            <a href="{{ route('products.catalog') }}?sort=popular" class="text-green-600 hover:text-green-700">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @if($bestSellerProducts->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($bestSellerProducts as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
        @else
        <div class="text-center py-8 bg-gray-100 rounded-lg">
            <p class="text-gray-500">Belum ada produk terlaris</p>
        </div>
        @endif
    </div>
</section>

<!-- Why Choose Us -->
<section class="bg-gray-100 py-8 rounded-lg">
    <div class="container px-4">
        <h2 class="text-2xl font-bold text-center mb-8">Kenapa Memilih Toko Bagus?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tags text-green-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Harga Terjangkau</h3>
                <p class="text-gray-600">Harga kompetitif dengan kualitas terbaik</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shipping-fast text-green-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Pengiriman Cepat</h3>
                <p class="text-gray-600">Pengiriman tepat waktu ke seluruh wilayah</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-green-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Customer Service 24/7</h3>
                <p class="text-gray-600">Siap membantu Anda kapan saja</p>
            </div>
        </div>
    </div>
</section>

<!-- Tambahkan custom CSS untuk mobile responsiveness -->
<style>
    @media (max-width: 768px) {
        .lg\:grid-cols-7 {
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 480px) {
        .lg\:grid-cols-7 {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }
</style>
@endsection

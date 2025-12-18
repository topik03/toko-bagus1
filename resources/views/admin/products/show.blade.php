@extends('admin.layouts.admin')

@section('title', $product->name . ' - Toko Bagus')
@section('page-title', 'Detail Produk')

@push('styles')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endpush

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center text-emerald-600 hover:text-emerald-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Produk
        </a>
    </div>

    <!-- Product Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-200 mb-6">
        <div class="px-6 py-4 border-b border-emerald-100">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-emerald-800">{{ $product->name }}</h2>
                <div class="flex space-x-2">
                    <span class="px-3 py-1 text-sm rounded-full bg-emerald-100 text-emerald-800">
                        ID: {{ $product->id }}
                    </span>
                    @if($product->is_featured)
                    <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-star mr-1"></i> Unggulan
                    </span>
                    @endif
                    @if($product->is_active)
                    <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                        Aktif
                    </span>
                    @else
                    <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">
                        Nonaktif
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Images -->
                <div class="lg:col-span-1">
                    <div class="mb-4">
                        @if($product->images->count() > 0)
                            <div class="swiper productImages">
                                <div class="swiper-wrapper">
                                    @foreach($product->images as $image)
                                    <div class="swiper-slide">
                                        @if(file_exists(storage_path('app/public/' . $image->image_path)))
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-64 object-cover rounded-lg border border-emerald-200">
                                        @else
                                            <div class="w-full h-64 bg-emerald-50 rounded-lg border border-emerald-200 flex items-center justify-center">
                                                <i class="fas fa-image text-4xl text-emerald-300"></i>
                                                <p class="text-sm text-emerald-600 mt-2">Gambar tidak ditemukan</p>
                                            </div>
                                        @endif
                                        @if($image->is_primary)
                                        <div class="mt-2 text-center">
                                            <span class="text-xs text-emerald-600">Gambar Utama</span>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination mt-4"></div>
                            </div>
                        @else
                            <div class="w-full h-64 bg-emerald-50 rounded-lg border border-emerald-200 flex flex-col items-center justify-center">
                                <i class="fas fa-image text-4xl text-emerald-300 mb-2"></i>
                                <p class="text-sm text-emerald-600">Belum ada gambar</p>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="flex-1 bg-emerald-600 text-white text-center py-2 rounded-lg hover:bg-emerald-700 transition flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST"
                              class="flex-1"
                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition flex items-center justify-center">
                                <i class="fas fa-trash mr-2"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="lg:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Basic Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-emerald-800 mb-4">Informasi Dasar</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-emerald-600">Kategori</p>
                                    <p class="font-medium text-emerald-800">{{ $product->category->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-emerald-600">Harga</p>
                                    <p class="font-medium text-emerald-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-emerald-600">Stok Tersedia</p>
                                    <p class="font-medium text-emerald-800">{{ $product->stock }} unit</p>
                                </div>
                                <div>
                                    <p class="text-sm text-emerald-600">Terjual</p>
                                    <p class="font-medium text-emerald-800">{{ $product->sold_count ?? 0 }} unit</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-emerald-800 mb-4">Informasi Tambahan</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-emerald-600">Berat</p>
                                    <p class="font-medium text-emerald-800">{{ $product->weight ? $product->weight . ' gram' : '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-emerald-600">Dimensi</p>
                                    <p class="font-medium text-emerald-800">{{ $product->dimensions ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-emerald-600">Slug</p>
                                    <p class="font-medium text-emerald-800">{{ $product->slug }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-emerald-600">Total Gambar</p>
                                    <p class="font-medium text-emerald-800">{{ $product->images->count() }} gambar</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-emerald-800 mb-4">Deskripsi Produk</h3>
                        <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-4">
                            <p class="text-emerald-800 whitespace-pre-line">{{ $product->description }}</p>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="border-t border-emerald-100 pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-emerald-600">Dibuat Pada</p>
                                <p class="text-emerald-800">{{ $product->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-emerald-600">Diperbarui Pada</p>
                                <p class="text-emerald-800">{{ $product->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Debug Info (Hanya untuk development) -->
                    @if(app()->environment('local'))
                    <div class="mt-6 p-4 bg-gray-100 rounded-lg border border-gray-300">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Debug Info:</h4>
                        <div class="text-xs text-gray-600 space-y-1">
                            <p>Storage Path: {{ storage_path('app/public/products') }}</p>
                            <p>Public Path: {{ public_path('storage') }}</p>
                            @if($product->images->count() > 0)
                                <p>First Image Path: {{ $product->images->first()->image_path }}</p>
                                <p>File Exists: {{ file_exists(storage_path('app/public/' . $product->images->first()->image_path)) ? 'YES' : 'NO' }}</p>
                                <p>URL Test: <a href="{{ asset('storage/' . $product->images->first()->image_path) }}" target="_blank" class="text-blue-600">Click to test</a></p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Initialize Swiper
    document.addEventListener('DOMContentLoaded', function() {
        const productImages = document.querySelector('.productImages');
        if (productImages) {
            new Swiper(productImages, {
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                slidesPerView: 1,
                spaceBetween: 10,
            });
        }
    });
</script>
@endpush
@endsection

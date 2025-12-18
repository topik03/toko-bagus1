@extends('admin.layouts.admin')

@section('title', 'Detail Kategori - ' . $category->name . ' - Toko Bagus')
@section('page-title', 'Detail Kategori')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-emerald-800">{{ $category->name }}</h2>
            <p class="text-emerald-600">Detail kategori dan produk terkait</p>
        </div>
        <div class="flex space-x-3 mt-4 md:mt-0">
            <a href="{{ route('admin.categories.edit', $category) }}"
               class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="px-4 py-2 border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Category Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Left Column: Category Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-info-circle mr-2"></i> Informasi Kategori
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-emerald-600">Nama</p>
                        <p class="font-medium text-emerald-800">{{ $category->name }}</p>
                    </div>
                    @if($category->description)
                    <div>
                        <p class="text-sm text-emerald-600">Deskripsi</p>
                        <p class="text-gray-700">{{ $category->description }}</p>
                    </div>
                    @endif
                    @if($category->parent)
                    <div>
                        <p class="text-sm text-emerald-600">Kategori Induk</p>
                        <p class="font-medium text-emerald-800">{{ $category->parent->name }}</p>
                    </div>
                    @endif
                    <div class="flex items-center">
                        <p class="text-sm text-emerald-600 mr-4">Status:</p>
                        <span class="px-3 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            @if($category->products->count() > 0)
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-boxes mr-2"></i> Produk dalam Kategori
                    <span class="text-sm font-normal text-gray-500">({{ $category->products->count() }})</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($category->products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.products.show', $product) }}" class="flex items-center group">
                                        @if($product->images->count() > 0)
                                            <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                                     alt="{{ $product->name }}"
                                                     class="h-10 w-10 object-cover rounded">
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-emerald-800 group-hover:text-emerald-600">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $product->sku ?? 'N/A' }}</p>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-4 py-3">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $product->stock }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Stats & Actions -->
        <div class="space-y-6">
            <!-- Image -->
            @if($category->image_path)
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-image mr-2"></i> Gambar Kategori
                </h3>
                <div class="w-full aspect-square rounded-lg overflow-hidden border border-gray-200">
                    <img src="{{ asset('storage/' . $category->image_path) }}"
                         alt="{{ $category->name }}"
                         class="w-full h-full object-cover">
                </div>
            </div>
            @endif

            <!-- Statistics -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-chart-bar mr-2"></i> Statistik
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-emerald-600">Total Produk</p>
                        <p class="text-2xl font-bold text-emerald-800">{{ $category->products_count ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Tanggal Dibuat</p>
                        <p class="font-medium text-emerald-800">{{ $category->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Terakhir Diupdate</p>
                        <p class="font-medium text-emerald-800">{{ $category->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.products.create') }}?category={{ $category->id }}"
                       class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center">
                        <i class="fas fa-plus mr-2"></i> Tambah Produk Baru
                    </a>

                    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="inline-block w-full">
                        @csrf @method('PUT')
                        <input type="hidden" name="is_active" value="{{ $category->is_active ? '0' : '1' }}">
                        <button type="submit" class="w-full px-4 py-2 {{ $category->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition">
                            <i class="fas fa-toggle-{{ $category->is_active ? 'off' : 'on' }} mr-2"></i>
                            {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Kategori
                        </button>
                    </form>

                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus kategori ini? Semua produk dalam kategori akan kehilangan kategori.')"
                          class="inline-block w-full">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-trash mr-2"></i> Hapus Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

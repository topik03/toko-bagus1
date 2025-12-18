@extends('admin.layouts.admin')

@section('title', 'Kelola Produk - Toko Bagus')
@section('page-title', 'Kelola Produk')

@section('content')
<div class="p-6">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-green-800">Daftar Produk</h2>
            <p class="text-green-600">Kelola semua produk di toko Anda</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.products.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Produk
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-green-700 mb-2">Cari Produk</label>
                <input type="text" placeholder="Nama produk..."
                       class="w-full border border-green-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-green-700 mb-2">Kategori</label>
                <select class="w-full border border-green-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Kategori</option>
                    <!-- Categories loop here -->
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-green-700 mb-2">Status</label>
                <select class="w-full border border-green-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg w-full">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                    @if($product->images->first())
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                             alt="{{ $product->name }}"
                                             class="h-10 w-10 rounded-lg object-cover">
                                    @else
                                        <i class="fas fa-box text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-green-800">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-green-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->stock <= 5)
                                <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    {{ $product->stock }} (Rendah)
                                </span>
                            @elseif($product->stock <= 20)
                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $product->stock }} (Sedang)
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    {{ $product->stock }} (Aman)
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->is_active)
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.products.show', $product) }}"
                                   class="text-blue-600 hover:text-blue-800" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="text-green-600 hover:text-green-800" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800"
                                            onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="text-gray-400 mb-2">
                                <i class="fas fa-box-open text-4xl"></i>
                            </div>
                            <p class="text-gray-500">Belum ada produk</p>
                            <a href="{{ route('admin.products.create') }}"
                               class="text-green-600 hover:text-green-800 font-medium mt-2 inline-block">
                                <i class="fas fa-plus mr-1"></i> Tambah Produk Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

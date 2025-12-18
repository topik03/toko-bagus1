@extends('admin.layouts.admin')

@section('title', 'Kelola Kategori - Toko Bagus')
@section('page-title', 'Kelola Kategori')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-green-800">Daftar Kategori</h2>
            <p class="text-green-600">Kelola kategori produk</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.categories.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Kategori
            </a>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg mr-4">
                            <i class="fas fa-tag text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-green-800">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $category->products_count }} produk</p>
                        </div>
                    </div>
                    @if($category->is_active)
                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">
                            Nonaktif
                        </span>
                    @endif
                </div>

                @if($category->description)
                <p class="text-gray-600 mt-4 text-sm">{{ Str::limit($category->description, 100) }}</p>
                @endif

                <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $category->created_at->format('d/m/Y') }}
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="text-green-600 hover:text-green-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}"
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-tags text-4xl"></i>
                </div>
                <p class="text-gray-500 mb-4">Belum ada kategori</p>
                <a href="{{ route('admin.categories.create') }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Kategori Pertama
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection

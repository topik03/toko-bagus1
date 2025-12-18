@extends('admin.layouts.admin')

@section('title', 'Edit Kategori - ' . $category->name . ' - Toko Bagus')
@section('page-title', 'Edit Kategori')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center text-emerald-600 hover:text-emerald-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Kategori
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-200">
        <div class="px-6 py-4 border-b border-emerald-100">
            <h2 class="text-lg font-semibold text-emerald-800">
                <i class="fas fa-edit mr-2"></i> Edit Kategori: {{ $category->name }}
            </h2>
            <p class="text-sm text-emerald-600 mt-1">Update informasi kategori</p>
        </div>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Display Validation Errors -->
            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="font-bold text-red-800 mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Terjadi Kesalahan
                </h3>
                <ul class="text-sm text-red-600 list-disc pl-5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Basic Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-info-circle mr-2"></i> Informasi Dasar
                </h3>

                <!-- Nama Kategori -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-emerald-700 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $category->name) }}"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           required>
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-emerald-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('description', $category->description) }}</textarea>
                </div>

                <!-- Parent Category -->
                <div class="mb-6">
                    <label for="parent_id" class="block text-sm font-medium text-emerald-700 mb-2">
                        Kategori Induk
                    </label>
                    <select name="parent_id" id="parent_id"
                            class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Tidak Ada --</option>
                        @foreach($categories as $cat)
                            @if($cat->id != $category->id)
                            <option value="{{ $cat->id }}"
                                    {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Current Image -->
            @if($category->image_path)
            <div class="mb-6">
                <label class="block text-sm font-medium text-emerald-700 mb-2">
                    Gambar Saat Ini
                </label>
                <div class="flex items-center space-x-4">
                    <div class="w-32 h-32 border border-emerald-200 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $category->image_path) }}"
                             alt="{{ $category->name }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <button type="button"
                                onclick="document.getElementById('remove_image').value = '1'"
                                class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                            <i class="fas fa-trash mr-2"></i> Hapus Gambar
                        </button>
                        <input type="hidden" name="remove_image" id="remove_image" value="0">
                    </div>
                </div>
            </div>
            @endif

            <!-- New Image Upload -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-image mr-2"></i> Ganti Gambar
                </h3>

                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-emerald-700 mb-2">
                        Upload Gambar Baru (Opsional)
                    </label>
                    <input type="file"
                           name="image"
                           id="image"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           accept="image/*">
                    <p class="text-xs text-emerald-500 mt-1">Format: JPG, PNG, GIF | Maks: 2MB</p>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-toggle-on mr-2"></i> Status
                </h3>

                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox"
                           name="is_active"
                           id="is_active"
                           value="1"
                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="h-5 w-5 text-emerald-600 rounded">
                    <label for="is_active" class="ml-3 text-sm font-medium text-emerald-700">
                        Aktifkan kategori
                    </label>
                </div>
            </div>

            <!-- Statistics -->
            <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-emerald-800 mb-3">Statistik</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-emerald-600">Total Produk</p>
                        <p class="font-medium text-emerald-800">{{ $category->products_count }} produk</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Tanggal Dibuat</p>
                        <p class="font-medium text-emerald-800">{{ $category->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-emerald-100">
                <a href="{{ route('admin.categories.index') }}"
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
</div>
@endsection

@extends('admin.layouts.admin')

@section('title', 'Edit Produk - Toko Bagus')
@section('page-title', 'Edit Produk: ' . $product->name)

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center text-emerald-600 hover:text-emerald-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Produk
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-200">
        <div class="px-6 py-4 border-b border-emerald-100">
            <h2 class="text-lg font-semibold text-emerald-800">
                <i class="fas fa-edit mr-2"></i> Edit Produk
            </h2>
            <p class="text-sm text-emerald-600 mt-1">Update informasi produk</p>
        </div>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <!-- Basic Product Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Nama Produk -->
                <div>
                    <label for="name" class="block text-sm font-medium text-emerald-700 mb-2">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           required
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           value="{{ old('name', $product->name) }}"
                           placeholder="Contoh: Beras Super 5kg">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-emerald-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id"
                            id="category_id"
                            required
                            class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga -->
                <div>
                    <label for="price" class="block text-sm font-medium text-emerald-700 mb-2">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-emerald-500">Rp</span>
                        </div>
                        <input type="number"
                               name="price"
                               id="price"
                               required
                               min="0"
                               step="100"
                               class="w-full border border-emerald-300 rounded-lg pl-12 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('price') border-red-500 @enderror"
                               value="{{ old('price', $product->price) }}"
                               placeholder="0">
                    </div>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-emerald-700 mb-2">
                        Stok <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           name="stock"
                           id="stock"
                           required
                           min="0"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('stock') border-red-500 @enderror"
                           value="{{ old('stock', $product->stock) }}"
                           placeholder="0">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-emerald-700 mb-2">
                    Deskripsi Produk <span class="text-red-500">*</span>
                </label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          required
                          class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Deskripsi lengkap produk...">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Additional Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Berat (gram) -->
                <div>
                    <label for="weight" class="block text-sm font-medium text-emerald-700 mb-2">
                        Berat (gram)
                    </label>
                    <div class="relative">
                        <input type="number"
                               name="weight"
                               id="weight"
                               min="0"
                               class="w-full border border-emerald-300 rounded-lg px-4 py-2 pr-12 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('weight') border-red-500 @enderror"
                               value="{{ old('weight', $product->weight) }}"
                               placeholder="0">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-emerald-500">g</span>
                        </div>
                    </div>
                    @error('weight')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dimensi -->
                <div>
                    <label for="dimensions" class="block text-sm font-medium text-emerald-700 mb-2">
                        Dimensi (PxLxT)
                    </label>
                    <input type="text"
                           name="dimensions"
                           id="dimensions"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('dimensions') border-red-500 @enderror"
                           value="{{ old('dimensions', $product->dimensions) }}"
                           placeholder="Contoh: 30x20x10 cm">
                    @error('dimensions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured & Active -->
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="is_featured"
                               id="is_featured"
                               value="1"
                               class="w-4 h-4 text-emerald-600 border-emerald-300 rounded focus:ring-emerald-500"
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label for="is_featured" class="ml-2 text-sm text-emerald-700">
                            Produk Unggulan
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               id="is_active"
                               value="1"
                               class="w-4 h-4 text-emerald-600 border-emerald-300 rounded focus:ring-emerald-500"
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 text-sm text-emerald-700">
                            Produk Aktif
                        </label>
                    </div>
                </div>
            </div>

            <!-- Gambar Produk yang Sudah Ada -->
            @if($product->images->count() > 0)
            <div class="mb-6">
                <label class="block text-sm font-medium text-emerald-700 mb-2">
                    Gambar Saat Ini
                </label>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach($product->images as $image)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                             alt="Product Image"
                             class="w-full h-32 object-cover rounded-lg border border-emerald-200">
                        <div class="absolute top-2 right-2">
                            <a href="#"
                               onclick="return confirm('Hapus gambar ini?')"
                               class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                        @if($image->is_primary)
                        <div class="absolute top-2 left-2">
                            <span class="bg-emerald-500 text-white text-xs px-2 py-1 rounded">
                                Utama
                            </span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tambah Gambar Baru -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-emerald-700 mb-2">
                    Tambah Gambar Baru (Opsional)
                </label>
                <div class="border-2 border-dashed border-emerald-300 rounded-lg p-6 text-center hover:border-emerald-400 transition">
                    <div class="mb-4">
                        <i class="fas fa-cloud-upload-alt text-4xl text-emerald-400"></i>
                    </div>
                    <p class="text-sm text-emerald-600 mb-2">
                        <span class="font-medium">Klik untuk upload</span> atau drag & drop
                    </p>
                    <p class="text-xs text-emerald-500 mb-4">
                        PNG, JPG, GIF hingga 2MB
                    </p>
                    <input type="file"
                           name="images[]"
                           id="images"
                           multiple
                           accept="image/*"
                           class="hidden">
                    <button type="button"
                            onclick="document.getElementById('images').click()"
                            class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-lg hover:bg-emerald-200 transition">
                        <i class="fas fa-image mr-2"></i> Pilih Gambar
                    </button>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-emerald-100">
                <a href="{{ route('admin.products.index') }}"
                   class="px-6 py-2 border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center">
                    <i class="fas fa-save mr-2"></i> Update Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('admin.layouts.admin')

@section('title', 'Tambah Kategori Baru - Toko Bagus')
@section('page-title', 'Tambah Kategori')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center text-emerald-600 hover:text-emerald-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Kategori
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-200">
        <div class="px-6 py-4 border-b border-emerald-100">
            <h2 class="text-lg font-semibold text-emerald-800">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Kategori Baru
            </h2>
            <p class="text-sm text-emerald-600 mt-1">Isi form berikut untuk menambahkan kategori baru</p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6" enctype="multipart/form-data">
            @csrf

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
                           value="{{ old('name') }}"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           placeholder="Contoh: Bahan Pokok, Minuman, Snack"
                           required>
                    <p class="text-xs text-emerald-500 mt-1">Nama kategori akan digunakan untuk navigasi produk</p>
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-emerald-700 mb-2">
                        Deskripsi (Opsional)
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                              placeholder="Deskripsi singkat tentang kategori ini...">{{ old('description') }}</textarea>
                </div>

                <!-- Parent Category -->
                <div class="mb-6">
                    <label for="parent_id" class="block text-sm font-medium text-emerald-700 mb-2">
                        Kategori Induk (Opsional)
                    </label>
                    <select name="parent_id" id="parent_id"
                            class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Pilih Kategori Induk --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-emerald-500 mt-1">Biarkan kosong jika ini kategori utama</p>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-image mr-2"></i> Gambar Kategori
                </h3>

                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-emerald-700 mb-2">
                        Upload Gambar (Opsional)
                    </label>
                    <div class="border-2 border-dashed border-emerald-300 rounded-lg p-6 text-center hover:bg-emerald-50 transition">
                        <div class="mb-4">
                            <i class="fas fa-cloud-upload-alt text-emerald-400 text-3xl"></i>
                        </div>
                        <p class="text-sm text-emerald-600 mb-2">Klik untuk upload gambar kategori</p>
                        <p class="text-xs text-emerald-500 mb-4">Format: JPG, PNG, GIF | Maks: 2MB</p>
                        <input type="file"
                               name="image"
                               id="image"
                               class="hidden"
                               accept="image/*">
                        <label for="image"
                               class="cursor-pointer inline-block px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition">
                            <i class="fas fa-upload mr-2"></i> Pilih Gambar
                        </label>
                    </div>

                    <!-- Image Preview -->
                    <div id="imagePreview" class="mt-4 hidden">
                        <p class="text-sm text-emerald-700 mb-2">Preview Gambar:</p>
                        <div class="w-32 h-32 border border-emerald-200 rounded-lg overflow-hidden">
                            <img id="previewImage" src="" alt="Preview" class="w-full h-full object-cover">
                        </div>
                    </div>
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
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-5 w-5 text-emerald-600 rounded">
                    <label for="is_active" class="ml-3 text-sm font-medium text-emerald-700">
                        Aktifkan kategori
                    </label>
                </div>
                <p class="text-xs text-emerald-500 mt-1">Kategori nonaktif tidak akan ditampilkan di halaman depan</p>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-emerald-100">
                <a href="{{ route('admin.categories.index') }}"
                   class="px-6 py-2 border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Kategori
                </button>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="font-semibold text-emerald-800 mb-3">
            <i class="fas fa-question-circle mr-2"></i> Tips Menambahkan Kategori
        </h3>
        <ul class="text-sm text-emerald-600 space-y-2">
            <li><i class="fas fa-check-circle text-emerald-500 mr-2"></i> Gunakan nama yang singkat dan jelas</li>
            <li><i class="fas fa-check-circle text-emerald-500 mr-2"></i> Deskripsi membantu SEO dan pemahaman pengguna</li>
            <li><i class="fas fa-check-circle text-emerald-500 mr-2"></i> Kategori induk berguna untuk hierarki produk</li>
            <li><i class="fas fa-check-circle text-emerald-500 mr-2"></i> Gambar membuat kategori lebih menarik</li>
        </ul>
    </div>
</div>

@push('scripts')
<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('previewImage');
        const previewContainer = document.getElementById('imagePreview');

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }

            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
        }
    });

    // Auto generate slug from name (optional)
    document.getElementById('name').addEventListener('blur', function() {
        const name = this.value;
        if (name) {
            // You can add slug generation logic here if needed
            console.log('Slug would be:', name.toLowerCase().replace(/\s+/g, '-'));
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();

        if (!name) {
            e.preventDefault();
            alert('Nama kategori wajib diisi!');
            document.getElementById('name').focus();
        }
    });
</script>
@endpush
@endsection

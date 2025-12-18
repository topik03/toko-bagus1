@extends('admin.layouts.admin')

@section('title', 'Tambah Pengguna Baru - Toko Bagus')
@section('page-title', 'Tambah Pengguna')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center text-emerald-600 hover:text-emerald-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Pengguna
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-200">
        <div class="px-6 py-4 border-b border-emerald-100">
            <h2 class="text-lg font-semibold text-emerald-800">
                <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna Baru
            </h2>
            <p class="text-sm text-emerald-600 mt-1">Isi form untuk menambahkan pengguna baru</p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
            @csrf

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

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-emerald-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           required>
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-emerald-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           required>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-emerald-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               required>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-emerald-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               required>
                    </div>
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-emerald-700 mb-2">
                        Nomor Telepon
                    </label>
                    <input type="text"
                           name="phone"
                           id="phone"
                           value="{{ old('phone') }}"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           placeholder="081234567890">
                </div>

                <!-- Address -->
                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-emerald-700 mb-2">
                        Alamat
                    </label>
                    <textarea name="address"
                              id="address"
                              rows="3"
                              class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('address') }}</textarea>
                </div>
            </div>

            <!-- Role & Status -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-user-tag mr-2"></i> Role & Status
                </h3>

                <div class="space-y-4">
                    <!-- Admin Role -->
                    <div class="flex items-center">
                        <input type="hidden" name="is_admin" value="0">
                        <input type="checkbox"
                               name="is_admin"
                               id="is_admin"
                               value="1"
                               {{ old('is_admin') ? 'checked' : '' }}
                               class="h-5 w-5 text-emerald-600 rounded">
                        <label for="is_admin" class="ml-3 text-sm font-medium text-emerald-700">
                            Jadikan sebagai Administrator
                        </label>
                    </div>
                    <p class="text-xs text-emerald-500 ml-8">
                        Administrator memiliki akses penuh ke sistem
                    </p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-emerald-100">
                <a href="{{ route('admin.users.index') }}"
                   class="px-6 py-2 border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

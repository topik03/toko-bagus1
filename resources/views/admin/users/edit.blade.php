@extends('admin.layouts.admin')

@section('title', 'Edit Pengguna - ' . $user->name . ' - Toko Bagus')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.users.show', $user) }}"
           class="inline-flex items-center text-emerald-600 hover:text-emerald-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Pengguna
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-200">
        <div class="px-6 py-4 border-b border-emerald-100">
            <h2 class="text-lg font-semibold text-emerald-800">
                <i class="fas fa-user-edit mr-2"></i> Edit Pengguna: {{ $user->name }}
            </h2>
            <p class="text-sm text-emerald-600 mt-1">Update informasi pengguna</p>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

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
                           value="{{ old('name', $user->name) }}"
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
                           value="{{ old('email', $user->email) }}"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                           required>
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-emerald-700 mb-2">
                        Nomor Telepon
                    </label>
                    <input type="text"
                           name="phone"
                           id="phone"
                           value="{{ old('phone', $user->phone) }}"
                           class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Address -->
                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-emerald-700 mb-2">
                        Alamat
                    </label>
                    <textarea name="address"
                              id="address"
                              rows="3"
                              class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <!-- Password Change (Optional) -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-key mr-2"></i> Ubah Password (Opsional)
                </h3>
                <p class="text-sm text-emerald-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-emerald-700 mb-2">
                            Password Baru
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-emerald-700 mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="w-full border border-emerald-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
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
                               {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                               class="h-5 w-5 text-emerald-600 rounded">
                        <label for="is_admin" class="ml-3 text-sm font-medium text-emerald-700">
                            Administrator
                        </label>
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox"
                               name="is_active"
                               id="is_active"
                               value="1"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               class="h-5 w-5 text-emerald-600 rounded">
                        <label for="is_active" class="ml-3 text-sm font-medium text-emerald-700">
                            Akun Aktif
                        </label>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-emerald-800 mb-3">Statistik Pengguna</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-emerald-600">Bergabung Sejak</p>
                        <p class="font-medium text-emerald-800">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Terakhir Login</p>
                        <p class="font-medium text-emerald-800">
                            {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum pernah' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-emerald-100">
                <a href="{{ route('admin.users.show', $user) }}"
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

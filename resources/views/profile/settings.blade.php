@extends('layouts.app')

@section('title', 'Pengaturan Akun - Toko Bagus')

@section('content')
<div class="min-h-screen bg-green-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-green-800">⚙️ Pengaturan Akun</h1>
            <p class="text-green-600">Kelola preferensi dan keamanan akun Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar Menu -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-4">
                    <h3 class="font-semibold text-green-700 mb-4 text-lg">Menu Profil</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('profile.dashboard') }}"
                               class="flex items-center p-3 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition">
                                <span class="mr-3">📊</span>
                                <span>Dashboard Profil</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center p-3 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition">
                                <span class="mr-3">👤</span>
                                <span>Edit Profil</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.chats') }}"
                               class="flex items-center p-3 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition">
                                <span class="mr-3">💬</span>
                                <span>Chat Penjual</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.addresses') }}"
                               class="flex items-center p-3 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition">
                                <span class="mr-3">🏠</span>
                                <span>Alamat Saya</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.settings') }}"
                               class="flex items-center p-3 bg-green-50 text-green-800 font-medium rounded-lg">
                                <span class="mr-3">⚙️</span>
                                <span>Pengaturan Akun</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Section 1: Update Password -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">🔐 Ubah Password</h3>

                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-green-700 mb-1">
                                    Password Saat Ini *
                                </label>
                                <input type="password"
                                       id="current_password"
                                       name="current_password"
                                       required
                                       class="w-full px-4 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-green-700 mb-1">
                                    Password Baru *
                                </label>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       required
                                       class="w-full px-4 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-green-700 mb-1">
                                    Konfirmasi Password Baru *
                                </label>
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       required
                                       class="w-full px-4 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>

                            <button type="submit"
                                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-medium">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Section 2: Update Email -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">📧 Ubah Email</h3>

                    <form action="{{ route('profile.update-email') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-green-700 mb-1">
                                    Email Baru *
                                </label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ auth()->user()->email }}"
                                       required
                                       class="w-full px-4 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-medium">
                                Update Email
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Section 3: Notification Settings -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">🔔 Pengaturan Notifikasi</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-green-800">Email Promo</p>
                                <p class="text-sm text-green-600">Dapatkan penawaran khusus via email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-green-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-green-800">Notifikasi Pesanan</p>
                                <p class="text-sm text-green-600">Update status pesanan via email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-green-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-green-800">Notifikasi Stok</p>
                                <p class="text-sm text-green-600">Pemberitahuan saat produk kembali tersedia</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-green-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Danger Zone -->
                <div class="bg-white rounded-xl shadow-md p-6 border border-red-200">
                    <h3 class="text-lg font-semibold text-red-700 mb-4">⚠️ Zona Berbahaya</h3>
                    <p class="text-red-600 mb-4">Tindakan ini tidak dapat dibatalkan. Hapus akun Anda secara permanen.</p>

                    <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirmDelete()">
                        @csrf
                        @method('DELETE')

                        <div class="mb-4">
                            <label for="delete_password" class="block text-sm font-medium text-red-700 mb-1">
                                Masukkan Password Anda untuk Konfirmasi *
                            </label>
                            <input type="password"
                                   id="delete_password"
                                   name="password"
                                   required
                                   class="w-full px-4 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>

                        <button type="submit"
                                class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition font-medium">
                            Hapus Akun Saya
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    return confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan!');
}

// Toggle switch untuk notifikasi
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('input[type="checkbox"]');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const status = this.checked ? 'diaktifkan' : 'dinonaktifkan';
            alert(`Notifikasi ${status}`);
            // Di sini bisa tambahkan AJAX untuk save preference
        });
    });
});
</script>
@endsection

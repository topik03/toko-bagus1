@extends('layouts.app')

@section('title', 'Tambah Alamat Baru - Toko Bagus')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-2">Tambah Alamat Baru</h1>
    <p class="text-gray-600 mb-8">Tambahkan alamat pengiriman baru untuk pesanan Anda</p>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Menu (sama seperti dashboard) -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- User Info -->
                <div class="p-6 border-b">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-green-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">{{ Auth::user()->name }}</h3>
                            <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                            <p class="text-green-600 text-sm font-medium">Member sejak {{ Auth::user()->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Menu -->
                <nav class="p-4">
                    <a href="{{ route('profile.dashboard') }}"
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mb-1">
                        <i class="fas fa-user-circle mr-3"></i>
                        Profil Saya
                    </a>

                    <a href="{{ route('orders.history') }}"
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mb-1">
                        <i class="fas fa-shopping-bag mr-3"></i>
                        Riwayat Pembelian
                        <span class="ml-auto bg-gray-200 text-gray-700 text-xs rounded-full px-2 py-1">
                            {{ Auth::user()->orders()->count() }}
                        </span>
                    </a>

                    <a href="{{ route('profile.chats') }}"
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mb-1">
                        <i class="fas fa-comments mr-3"></i>
                        Chat Penjual
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">3</span>
                    </a>

                    <a href="{{ route('profile.addresses') }}"
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mb-1 bg-green-50 text-green-600">
                        <i class="fas fa-map-marker-alt mr-3"></i>
                        Alamat Saya
                    </a>

                    <a href="{{ route('profile.settings') }}"
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mb-1">
                        <i class="fas fa-cog mr-3"></i>
                        Pengaturan Akun
                    </a>

                    <div class="border-t my-3"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center w-full px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            Keluar
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Quick Stats -->
            <div class="mt-6 bg-white rounded-lg shadow p-6">
                <h4 class="font-semibold mb-4">Statistik Anda</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Pembelian</span>
                        <span class="font-bold">{{ Auth::user()->orders()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Belanja</span>
                        <span class="font-bold text-green-600">Rp {{ number_format(Auth::user()->orders()->sum('total'), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pesanan Aktif</span>
                        <span class="font-bold">{{ Auth::user()->orders()->whereIn('order_status', ['processing', 'shipped'])->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:w-3/4">
            <div class="bg-white rounded-lg shadow">
                <!-- Content Header -->
                <div class="border-b p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold">Tambah Alamat Baru</h2>
                            <p class="text-gray-600">Isi data alamat pengiriman dengan lengkap</p>
                        </div>
                        <a href="{{ route('profile.addresses') }}"
                           class="text-green-600 hover:text-green-800">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Alamat
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('profile.addresses.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Label Alamat -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-tag mr-2 text-green-600"></i>
                                    Label Alamat <span class="text-red-500">*</span>
                                </label>
                                <select name="label" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                    <option value="">Pilih Label</option>
                                    <option value="Rumah" {{ old('label') == 'Rumah' ? 'selected' : '' }}>🏠 Rumah</option>
                                    <option value="Kantor" {{ old('label') == 'Kantor' ? 'selected' : '' }}>🏢 Kantor</option>
                                    <option value="Kos" {{ old('label') == 'Kos' ? 'selected' : '' }}>🏡 Kos</option>
                                    <option value="Lainnya" {{ old('label') == 'Lainnya' ? 'selected' : '' }}>📍 Lainnya</option>
                                </select>
                            </div>

                            <!-- Nama Penerima -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-user mr-2 text-green-600"></i>
                                    Nama Penerima <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="recipient_name" required
                                       value="{{ old('recipient_name', Auth::user()->name) }}"
                                       placeholder="Masukkan nama lengkap penerima"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            </div>

                            <!-- No. Telepon -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-phone mr-2 text-green-600"></i>
                                    No. Telepon/WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" required
                                       value="{{ old('phone', Auth::user()->phone) }}"
                                       placeholder="Contoh: 081234567890"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            </div>

                            <!-- Alamat Lengkap -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea name="address" rows="3" required
                                          placeholder="Masukkan alamat lengkap (jalan, nomor, RT/RW, kelurahan)"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">{{ old('address', Auth::user()->address) }}</textarea>
                            </div>

                            <!-- Kota & Kode Pos -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">
                                        <i class="fas fa-city mr-2 text-green-600"></i>
                                        Kota/Kabupaten <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="city" required
                                           value="{{ old('city', Auth::user()->city) }}"
                                           placeholder="Contoh: Jakarta Timur"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">
                                        <i class="fas fa-mail-bulk mr-2 text-green-600"></i>
                                        Kode Pos <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="postal_code" required
                                           value="{{ old('postal_code', Auth::user()->postal_code) }}"
                                           placeholder="Contoh: 12345"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                </div>
                            </div>

                            <!-- Alamat Utama -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_default" value="1" id="is_default"
                                           class="h-5 w-5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                           {{ old('is_default') ? 'checked' : '' }}>
                                    <label for="is_default" class="ml-3">
                                        <span class="font-medium text-gray-700">Jadikan alamat utama</span>
                                        <p class="text-gray-500 text-sm mt-1">
                                            Alamat utama akan digunakan sebagai default pengiriman pesanan
                                        </p>
                                    </label>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('profile.addresses') }}"
                                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                                    <i class="fas fa-times mr-2"></i> Batal
                                </a>
                                <button type="submit"
                                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                                    <i class="fas fa-save mr-2"></i> Simpan Alamat
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Box -->
            <div class="mt-6 bg-green-50 border border-green-200 rounded-lg shadow p-6">
                <h4 class="font-bold text-green-800 mb-3 flex items-center">
                    <i class="fas fa-lightbulb mr-2 text-green-600"></i>
                    Tips Mengisi Alamat
                </h4>
                <ul class="text-green-700 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                        <span>Pastikan nomor telepon aktif untuk konfirmasi kurir</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                        <span>Tambahkan patokan lokasi jika alamat sulit ditemukan</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                        <span>Periksa kembali kode pos untuk akurasi pengiriman</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

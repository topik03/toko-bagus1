@extends('profile.dashboard')

@section('profile-title', 'Alamat Saya')
@section('profile-subtitle', 'Kelola alamat pengiriman Anda')

@section('profile-content')
<div>
    <!-- Session Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- Add New Address Button -->
<div class="mb-6">
    <a href="{{ route('profile.addresses.create') }}"
       class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
        <i class="fas fa-plus mr-2"></i> Tambah Alamat Baru
    </a>
</div>

    <!-- Address Form (Hidden by Default) -->
    <div id="address-form" class="hidden bg-gray-50 p-6 rounded-lg mb-6">
        <h4 class="font-semibold mb-4">Tambah Alamat Baru</h4>
        <form action="{{ route('profile.addresses.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Label Alamat *</label>
                    <select name="label" required class="w-full border rounded-lg px-4 py-2">
                        <option value="Rumah">Rumah</option>
                        <option value="Kantor">Kantor</option>
                        <option value="Kos">Kos</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Nama Penerima *</label>
                    <input type="text" name="recipient_name" required
                           class="w-full border rounded-lg px-4 py-2"
                           value="{{ old('recipient_name', auth()->user()->name) }}">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">No. Telepon *</label>
                    <input type="text" name="phone" required
                           class="w-full border rounded-lg px-4 py-2"
                           value="{{ old('phone', auth()->user()->phone) }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Alamat Lengkap *</label>
                    <textarea name="address" rows="2" required
                              class="w-full border rounded-lg px-4 py-2">{{ old('address', auth()->user()->address) }}</textarea>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Kota *</label>
                    <input type="text" name="city" required
                           class="w-full border rounded-lg px-4 py-2"
                           value="{{ old('city', auth()->user()->city) }}">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Kode Pos *</label>
                    <input type="text" name="postal_code" required
                           class="w-full border rounded-lg px-4 py-2"
                           value="{{ old('postal_code', auth()->user()->postal_code) }}">
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_default" value="1" class="mr-2">
                        <span>Jadikan alamat utama</span>
                    </label>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Simpan Alamat
                </button>
                <button type="button" onclick="toggleAddressForm()"
                        class="ml-2 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
            </div>
        </form>
    </div>

    <!-- Address List -->
    <div class="space-y-4">
        @forelse($addresses as $address)
        <div class="border rounded-lg p-4 {{ $address->is_default ? 'bg-green-50 border-green-200' : 'bg-white' }}">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center mb-2">
                        @if($address->is_default)
                        <span class="bg-green-600 text-white text-xs px-2 py-1 rounded mr-2">Utama</span>
                        @endif
                        <h4 class="font-semibold">{{ $address->label }}</h4>
                    </div>
                    <p class="text-gray-700 font-medium">{{ $address->recipient_name }}</p>
                    <p class="text-gray-700">{{ $address->address }}</p>
                    <p class="text-gray-600">{{ $address->city }}, {{ $address->postal_code }}</p>
                    <p class="text-gray-600">{{ $address->phone }}</p>
                </div>
                <div class="flex space-x-2">
                    <!-- Edit Form (Modal atau inline) -->
                    <form action="{{ route('profile.addresses.update', $address) }}" method="POST" class="hidden" id="edit-form-{{ $address->id }}">
                        @csrf @method('PUT')
                        <!-- Sama seperti form tambah, tapi dengan data address -->
                    </form>

                    <button onclick="editAddress({{ $address->id }})"
                            class="text-green-600 hover:text-green-800">
                        <i class="fas fa-edit"></i>
                    </button>

                    @if(!$address->is_default)
                    <form action="{{ route('profile.addresses.delete', $address) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus alamat ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-map-marker-alt text-4xl mb-2"></i>
            <p>Belum ada alamat tersimpan</p>
            <p class="text-sm">Tambahkan alamat pertama Anda!</p>
        </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function toggleAddressForm() {
    console.log('Tombol diklik!'); // Cek di Console

    const form = document.getElementById('address-form');
    const button = document.querySelector('button[onclick="toggleAddressForm()"]');

    if (form && button) {
        // Toggle form
        form.classList.toggle('hidden');

        // Toggle teks tombol
        if (form.classList.contains('hidden')) {
            button.innerHTML = '<i class="fas fa-plus mr-2"></i> Tambah Alamat Baru';
        } else {
            button.innerHTML = '<i class="fas fa-minus mr-2"></i> Tutup Form';
        }
    } else {
        console.error('Element tidak ditemukan!');
        alert('Terjadi kesalahan. Silakan refresh halaman.');
    }
}

// Atau gunakan event listener yang lebih reliable
document.addEventListener('DOMContentLoaded', function() {
    console.log('Halaman alamat dimuat!');

    // Cara 1: Tambah event listener ke tombol
    const addButton = document.querySelector('button[onclick*="toggleAddressForm"]');
    if (addButton) {
        // Hapus onclick attribute dan ganti dengan event listener
        addButton.removeAttribute('onclick');
        addButton.addEventListener('click', function() {
            console.log('Tombol diklik via event listener');
            toggleAddressForm();
        });
    }

    // Cara 2: Debug - cek apakah semua element ada
    console.log('Form element:', document.getElementById('address-form'));
    console.log('Button element:', addButton);

    // Test: buka form secara otomatis untuk debugging
    // setTimeout(() => toggleAddressForm(), 1000);
});
</script>
@endpush
@endsection

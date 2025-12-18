@extends('profile.dashboard')

@section('profile-title', 'Edit Profil Saya')
@section('profile-subtitle', 'Perbarui informasi profil Anda')

@section('profile-content')
<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 mb-2">Nomor Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   placeholder="0812-3456-7890">
        </div>

        <div>
            <label class="block text-gray-700 mb-2">Tanggal Lahir (Opsional)</label>
            <input type="date" name="birthdate" value="{{ old('birthdate', $user->birthdate ?? '') }}"
                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2">Alamat</label>
            <textarea name="address" rows="3"
                      class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                      placeholder="Jl. Contoh No. 123">{{ old('address', $user->address ?? '') }}</textarea>
        </div>
    </div>

    <div class="mt-8">
        <button type="submit"
                class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold">
            Simpan Perubahan
        </button>
        <a href="{{ route('profile.dashboard') }}"
           class="ml-4 border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50">
            Batal
        </a>
    </div>
</form>
@endsection

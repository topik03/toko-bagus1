@extends('admin.layouts.admin')

@section('title', 'Kelola Pengguna - Toko Bagus')
@section('page-title', 'Kelola Pengguna')

@section('content')
<div class="p-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
        <p class="text-green-800">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-red-800">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
        </p>
    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-green-800">Daftar Pengguna</h2>
            <p class="text-green-600">Kelola semua pengguna sistem</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.users.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Pengguna
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-purple-200 p-4">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg mr-4">
                    <i class="fas fa-users text-purple-600"></i>
                </div>
                <div>
                    <p class="text-purple-600 text-sm">Total Pengguna</p>
                    <p class="text-2xl font-bold text-purple-800">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-green-200 p-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <i class="fas fa-crown text-green-600"></i>
                </div>
                <div>
                    <p class="text-green-600 text-sm">Admin</p>
                    <p class="text-2xl font-bold text-green-800">{{ $adminUsers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-blue-200 p-4">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-shopping-cart text-blue-600"></i>
                </div>
                <div>
                    <p class="text-blue-600 text-sm">Dengan Pesanan</p>
                    <p class="text-2xl font-bold text-blue-800">
                        {{ $usersWithOrders }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-green-100 text-green-800 rounded-full flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-green-800">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $user->orders_count }} pesanan
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                                <!-- DEBUG: Tampilkan nilai untuk testing -->
                                <span class="hidden">{{ $user->is_admin }}{{ $user->is_active }}</span>

                                @if($user->is_admin == 1 || $user->is_admin == true)
                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                        <i class="fas fa-crown mr-1"></i> Admin
                                    </span>
                                @endif

                                @if($user->is_active == 1 || $user->is_active == true)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i> Nonaktif
                                    </span>
                                @endif

                                @if($user->email_verified_at)
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-check-circle mr-1"></i> Verified
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-green-600 hover:text-green-800 p-1" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="text-blue-600 hover:text-blue-800 p-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if($user->id !== auth()->id())
                                    <!-- Toggle Admin Form -->
                                    <form action="{{ route('admin.users.toggle-admin', $user) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('{{ $user->is_admin ? 'Hapus status admin dari user ini?' : 'Jadikan user ini sebagai admin?' }}')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="text-purple-600 hover:text-purple-800 p-1"
                                                title="{{ $user->is_admin ? 'Hapus Admin' : 'Jadikan Admin' }}">
                                            <i class="fas {{ $user->is_admin ? 'fa-user-minus' : 'fa-user-plus' }}"></i>
                                        </button>
                                    </form>

                                    <!-- Toggle Active Form -->
                                    <form action="{{ route('admin.users.toggle-active', $user) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan akun ini?' : 'Aktifkan akun ini?' }}')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="text-{{ $user->is_active ? 'red' : 'green' }}-600 hover:text-{{ $user->is_active ? 'red' : 'green' }}-800 p-1"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas {{ $user->is_active ? 'fa-user-times' : 'fa-user-check' }}"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center">
                            <div class="text-gray-400 mb-2">
                                <i class="fas fa-users text-4xl"></i>
                            </div>
                            <p class="text-gray-500">Belum ada pengguna</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<script>
// Auto refresh page jika ada success message (opsional)
@if(session('success'))
setTimeout(function() {
    window.location.reload();
}, 2000);
@endif
</script>
@endsection

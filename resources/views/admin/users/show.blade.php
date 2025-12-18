@extends('admin.layouts.admin')

@section('title', 'Detail Pengguna - ' . $user->name . ' - Toko Bagus')
@section('page-title', 'Detail Pengguna')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center text-emerald-600 hover:text-emerald-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Pengguna
        </a>
    </div>

    <!-- User Header -->
    <div class="bg-white rounded-lg border border-emerald-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="h-16 w-16 bg-emerald-100 text-emerald-800 rounded-full flex items-center justify-center text-2xl font-bold mr-4">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-emerald-800">{{ $user->name }}</h2>
                    <p class="text-emerald-600">{{ $user->email }}</p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @if($user->is_admin)
                        <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                            <i class="fas fa-crown mr-1"></i> Admin
                        </span>
                        @endif
                        <span class="px-3 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas {{ $user->is_active ? 'fa-check' : 'fa-times' }} mr-1"></i>
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        @if($user->email_verified_at)
                        <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            <i class="fas fa-check-circle mr-1"></i> Email Terverifikasi
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline">
                    @csrf @method('PUT')
                    <button type="submit"
                            class="px-4 py-2 {{ $user->is_admin ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-purple-600 hover:bg-purple-700' }} text-white rounded-lg transition">
                        <i class="fas {{ $user->is_admin ? 'fa-user-minus' : 'fa-user-plus' }} mr-2"></i>
                        {{ $user->is_admin ? 'Hapus Admin' : 'Jadikan Admin' }}
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: User Info & Orders -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Information -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-user-circle mr-2"></i> Informasi Pengguna
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-emerald-600">Email</p>
                        <p class="font-medium text-emerald-800">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Telepon</p>
                        <p class="font-medium text-emerald-800">{{ $user->phone ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-emerald-600">Alamat</p>
                        <p class="font-medium text-emerald-800">{{ $user->address ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Bergabung Sejak</p>
                        <p class="font-medium text-emerald-800">{{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600">Terakhir Login</p>
                        <p class="font-medium text-emerald-800">
                            {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum pernah' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-emerald-800">
                        <i class="fas fa-shopping-cart mr-2"></i> Pesanan Terbaru
                    </h3>
                    <a href="{{ route('admin.orders.index') }}?user={{ $user->id }}"
                       class="text-sm text-emerald-600 hover:text-emerald-800">
                        Lihat Semua
                    </a>
                </div>

                @if($user->orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Pesanan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($user->orders->take(5) as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="font-medium text-emerald-600 hover:text-emerald-800">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 font-medium text-emerald-800">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 text-xs rounded-full
                                        {{ $order->order_status == 'completed' ? 'bg-green-100 text-green-800' :
                                           ($order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                           ($order->order_status == 'processing' ? 'bg-blue-100 text-blue-800' :
                                           ($order->order_status == 'shipped' ? 'bg-purple-100 text-purple-800' :
                                           'bg-red-100 text-red-800'))) }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="text-emerald-600 hover:text-emerald-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">Belum ada pesanan</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Stats & Actions -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-chart-bar mr-2"></i> Statistik
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-emerald-600">Total Pesanan</p>
                            <p class="text-2xl font-bold text-emerald-800">{{ $totalOrders }}</p>
                        </div>
                        <div class="p-3 bg-emerald-100 rounded-lg">
                            <i class="fas fa-shopping-bag text-emerald-600"></i>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-emerald-600">Total Belanja</p>
                            <p class="text-2xl font-bold text-emerald-800">
                                Rp {{ number_format($totalSpent, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="fas fa-money-bill-wave text-purple-600"></i>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-emerald-600">Ulasan</p>
                            <p class="text-2xl font-bold text-emerald-800">{{ $totalReviews }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-star text-blue-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg border border-emerald-200 p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="w-full px-4 py-2 {{ $user->is_admin ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-purple-600 hover:bg-purple-700' }} text-white rounded-lg transition">
                            <i class="fas {{ $user->is_admin ? 'fa-user-minus' : 'fa-user-plus' }} mr-2"></i>
                            {{ $user->is_admin ? 'Hapus Admin' : 'Jadikan Admin' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="w-full px-4 py-2 {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition">
                            <i class="fas {{ $user->is_active ? 'fa-user-times' : 'fa-user-check' }} mr-2"></i>
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Akun
                        </button>
                    </form>

                    <form action="{{ route('admin.users.impersonate', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-user-secret mr-2"></i> Login Sebagai User
                        </button>
                    </form>
                    @endif

                    @if($user->id !== auth()->id() && $user->orders->count() == 0)
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-trash mr-2"></i> Hapus Pengguna
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

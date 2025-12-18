@extends('layouts.app')

@section('title', 'Dashboard Profil - Toko Bagus')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-2">Dashboard Profil</h1>
    <p class="text-gray-600 mb-8">Kelola informasi profil dan aktivitas Anda</p>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Menu -->
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
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mb-1 {{ request()->routeIs('profile.dashboard') ? 'bg-green-50 text-green-600' : '' }}">
                        <i class="fas fa-user-circle mr-3"></i>
                        Profil Saya
                    </a>

                    <a href="{{ route('orders.history') }}"
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mb-1 {{ request()->routeIs('orders.history') ? 'bg-green-50 text-green-600' : '' }}">
                        <i class="fas fa-shopping-bag mr-3"></i>
                        Riwayat Pembelian
                        <span class="ml-auto bg-gray-200 text-gray-700 text-xs rounded-full px-2 py-1">
                            {{ Auth::user()->orders()->count() }}
                        </span>
                    </a>

                    <a href="{{ route('profile.chats') }}"
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mb-1 {{ request()->routeIs('profile.chats') ? 'bg-green-50 text-green-600' : '' }}">
                        <i class="fas fa-comments mr-3"></i>
                        Chat Penjual
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">3</span>
                    </a>

                    <a href="{{ route('profile.addresses') }}"
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mb-1 {{ request()->routeIs('profile.addresses') ? 'bg-green-50 text-green-600' : '' }}">
                        <i class="fas fa-map-marker-alt mr-3"></i>
                        Alamat Saya
                    </a>

                    <a href="{{ route('profile.settings') }}"
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mb-1 {{ request()->routeIs('profile.settings') ? 'bg-green-50 text-green-600' : '' }}">
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
                    <h2 class="text-2xl font-bold">
                        @yield('profile-title', 'Profil Saya')
                    </h2>
                    <p class="text-gray-600">
                        @yield('profile-subtitle', 'Kelola informasi profil Anda')
                    </p>
                </div>

                <!-- Dynamic Content -->
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('profile-content')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

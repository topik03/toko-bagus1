<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Toko Bagus') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- TAILWIND CDN VERSI 3 (SOLUSI UTAMA) -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Custom Tailwind Config untuk warna -->
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            green: {
                                50: '#f0fdf4',
                                100: '#dcfce7',
                                200: '#bbf7d0',
                                300: '#86efac',
                                400: '#4ade80',
                                500: '#22c55e',
                                600: '#16a34a',
                                700: '#15803d',
                                800: '#166534',
                                900: '#14532d',
                            }
                        }
                    }
                }
            }
        </script>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Alpine.js untuk dropdown -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- CUSTOM CSS UNTUK FIX GRADIENT DAN GRID -->
        <style>
            /* FIX: Gradient hijau untuk hero section */
            .hero-gradient {
                background: linear-gradient(to right, #10b981 0%, #059669 100%) !important;
            }

            /* FIX: Grid columns untuk Tailwind CDN */
            .grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }

            .grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            }

            .grid-cols-7 {
                grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
            }

            /* FIX: Responsive grid */
            @media (min-width: 768px) {
                .md\:grid-cols-4 {
                    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
                }
            }

            @media (min-width: 1024px) {
                .lg\:grid-cols-7 {
                    grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
                }

                .lg\:grid-cols-4 {
                    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
                }
            }

            /* FIX: Container class */
            .container {
                width: 100% !important;
                margin-right: auto !important;
                margin-left: auto !important;
                padding-right: 1rem !important;
                padding-left: 1rem !important;
            }

            @media (min-width: 640px) {
                .container {
                    max-width: 640px !important;
                }
            }

            @media (min-width: 768px) {
                .container {
                    max-width: 768px !important;
                }
            }

            @media (min-width: 1024px) {
                .container {
                    max-width: 1024px !important;
                }
            }

            @media (min-width: 1280px) {
                .container {
                    max-width: 1280px !important;
                }
            }

            /* FIX: Group hover */
            .group:hover .group-hover\:bg-green-200 {
                background-color: #bbf7d0 !important;
            }

            .group:hover .group-hover\:text-green-600 {
                color: #16a34a !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Navigation -->
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('home') }}" class="text-2xl font-bold text-green-600">
                                    <i class="fas fa-store mr-2"></i>Toko Bagus
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <a href="{{ route('home') }}"
                                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    Beranda
                                </a>
                                <a href="{{ route('products.catalog') }}"
                                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    Semua Produk
                                </a>
                            </div>
                        </div>

                        <!-- Right Side Navigation -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Cart -->
                            <a href="{{ route('cart.index') }}"
                               class="relative inline-flex items-center px-3 py-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <i class="fas fa-shopping-cart text-xl"></i>

                                @php
                                    // Hitung cart items
                                    $cartCount = 0;
                                    try {
                                        if (auth()->check()) {
                                            $cart = App\Models\Cart::where('user_id', auth()->id())->first();
                                        } else {
                                            $sessionId = session()->getId();
                                            $cart = App\Models\Cart::where('session_id', $sessionId)->first();
                                        }

                                        if ($cart) {
                                            $cartCount = $cart->items()->sum('quantity');
                                        }
                                    } catch (\Exception $e) {
                                        $cartCount = 0;
                                    }
                                @endphp

                                @if($cartCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>

                            <!-- Auth Links -->
                            @auth
                                <div class="relative ml-4" x-data="{ open: false }">
                                    <button @click="open = !open"
                                            class="flex items-center text-sm text-gray-500 hover:text-gray-700 focus:outline-none">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                            <i class="fas fa-user text-green-600 text-sm"></i>
                                        </div>
                                        <span>{{ Auth::user()->name }}</span>
                                        <svg class="ms-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <div x-show="open"
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50 border border-gray-200"
                                         style="display: none;">
                                        <!-- Dashboard Profil -->
                                        <a href="{{ route('profile.dashboard') }}"
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i> Dashboard Profil
                                        </a>

                                        <!-- Edit Profil -->
                                        <a href="{{ route('profile.edit') }}"
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-user-edit mr-3 w-5 text-center"></i> Edit Profil
                                        </a>

                                        <!-- Riwayat Pesanan -->
                                        <a href="{{ route('orders.history') }}"
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-history mr-3 w-5 text-center"></i> Riwayat Pesanan
                                            <span class="ml-auto bg-gray-200 text-gray-700 text-xs rounded-full px-2 py-0.5">
                                                {{ Auth::user()->orders()->count() }}
                                            </span>
                                        </a>

                                        <!-- Alamat Saya -->
                                        <a href="{{ route('profile.addresses') }}"
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-map-marker-alt mr-3 w-5 text-center"></i> Alamat Saya
                                        </a>

                                        <div class="border-t my-2"></div>

                                        <!-- Logout -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                    class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="ml-4 flex items-center space-x-4">
                                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-green-600">Login</a>
                                    <a href="{{ route('register') }}" class="text-sm bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Daftar</a>
                                </div>
                            @endauth
                        </div>

                        <!-- Hamburger Menu Button -->
                        <div class="flex items-center sm:hidden">
                            <!-- Cart Icon Mobile -->
                            <a href="{{ route('cart.index') }}" class="relative mr-4 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-shopping-cart text-xl"></i>
                                @php
                                    $cartCountMobile = 0;
                                    try {
                                        if (auth()->check()) {
                                            $cart = App\Models\Cart::where('user_id', auth()->id())->first();
                                        } else {
                                            $sessionId = session()->getId();
                                            $cart = App\Models\Cart::where('session_id', $sessionId)->first();
                                        }
                                        if ($cart) {
                                            $cartCountMobile = $cart->items()->sum('quantity');
                                        }
                                    } catch (\Exception $e) {}
                                @endphp
                                @if($cartCountMobile > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $cartCountMobile }}</span>
                                @endif
                            </a>

                            <button @click="mobileMenuOpen = !mobileMenuOpen"
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg class="h-6 w-6" :class="{ 'hidden': !mobileMenuOpen, 'block': mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div class="sm:hidden" x-show="mobileMenuOpen" x-transition @click.away="mobileMenuOpen = false" style="display: none;">
                    <div class="pt-2 pb-3 space-y-1 bg-white border-t">
                        <a href="{{ route('home') }}"
                           class="block pl-3 pr-4 py-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-l-4 border-transparent hover:border-green-500">
                            <i class="fas fa-home mr-3 w-5 text-center"></i> Beranda
                        </a>
                        <a href="{{ route('products.catalog') }}"
                           class="block pl-3 pr-4 py-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-l-4 border-transparent hover:border-green-500">
                            <i class="fas fa-box mr-3 w-5 text-center"></i> Semua Produk
                        </a>

                        @auth
                            <!-- Mobile User Menu -->
                            <div class="border-t mt-2 pt-2">
                                <a href="{{ route('profile.dashboard') }}"
                                   class="block pl-3 pr-4 py-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-l-4 border-transparent hover:border-green-500">
                                    <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i> Dashboard Profil
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                   class="block pl-3 pr-4 py-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-l-4 border-transparent hover:border-green-500">
                                    <i class="fas fa-user-edit mr-3 w-5 text-center"></i> Edit Profil
                                </a>
                                <a href="{{ route('orders.history') }}"
                                   class="block pl-3 pr-4 py-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-l-4 border-transparent hover:border-green-500">
                                    <i class="fas fa-history mr-3 w-5 text-center"></i> Riwayat Pesanan
                                </a>
                                <a href="{{ route('profile.addresses') }}"
                                   class="block pl-3 pr-4 py-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-l-4 border-transparent hover:border-green-500">
                                    <i class="fas fa-map-marker-alt mr-3 w-5 text-center"></i> Alamat Saya
                                </a>
                            </div>

                            <!-- Mobile Logout -->
                            <div class="border-t mt-2 pt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-left pl-3 pr-4 py-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-l-4 border-transparent hover:border-red-500">
                                        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Mobile Auth Links -->
                            <div class="border-t mt-2 pt-2">
                                <a href="{{ route('login') }}"
                                   class="block pl-3 pr-4 py-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-l-4 border-transparent hover:border-green-500">
                                    <i class="fas fa-sign-in-alt mr-3 w-5 text-center"></i> Login
                                </a>
                                <a href="{{ route('register') }}"
                                   class="block pl-3 pr-4 py-3 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-l-4 border-transparent hover:border-green-500">
                                    <i class="fas fa-user-plus mr-3 w-5 text-center"></i> Daftar
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-gray-800 text-white py-8 mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <h3 class="text-2xl font-bold mb-4">Toko Bagus</h3>
                            <p class="text-gray-300">Toko sembako online terpercaya dengan harga terjangkau dan kualitas terbaik.</p>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Kategori</h4>
                            <ul class="space-y-2">
                                <li><a href="{{ route('products.catalog') }}?category=beras" class="text-gray-300 hover:text-white">Beras & Beras Analog</a></li>
                                <li><a href="{{ route('products.catalog') }}?category=minyak-goreng" class="text-gray-300 hover:text-white">Minyak Goreng</a></li>
                                <li><a href="{{ route('products.catalog') }}?category=gula" class="text-gray-300 hover:text-white">Gula & Pemanis</a></li>
                                <li><a href="{{ route('products.catalog') }}?category=telur-susu" class="text-gray-300 hover:text-white">Telur & Susu</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Layanan</h4>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-300 hover:text-white">Cara Belanja</a></li>
                                <li><a href="#" class="text-gray-300 hover:text-white">Pengiriman</a></li>
                                <li><a href="#" class="text-gray-300 hover:text-white">Pembayaran</a></li>
                                <li><a href="#" class="text-gray-300 hover:text-white">Kontak Kami</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                            <p class="text-gray-300"><i class="fas fa-phone mr-2"></i> 0812-3456-7890</p>
                            <p class="text-gray-300"><i class="fas fa-envelope mr-2"></i> info@tokobagus.com</p>
                            <p class="text-gray-300"><i class="fas fa-map-marker-alt mr-2"></i> Jakarta, Indonesia</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                        <p>&copy; {{ date('Y') }} Toko Bagus. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Alpine.js initialization -->
        <script>
            // Initialize Alpine.js data
            document.addEventListener('alpine:init', () => {
                Alpine.data('dropdown', () => ({
                    open: false,
                    toggle() {
                        this.open = !this.open
                    }
                }))

                Alpine.data('mobileMenu', () => ({
                    mobileMenuOpen: false,
                    toggleMobileMenu() {
                        this.mobileMenuOpen = !this.mobileMenuOpen
                    }
                }))
            })

            // Set initial state for mobile menu
            document.addEventListener('DOMContentLoaded', function() {
                if (!window.Alpine) {
                    console.warn('Alpine.js not loaded');
                }
            });
        </script>

        <!-- If Alpine.js fails to load from CDN, load local -->
        <script>
            if (typeof Alpine === 'undefined') {
                document.write('<script src="/js/alpine.js"><\/script>');
            }
        </script>
    </body>
</html>

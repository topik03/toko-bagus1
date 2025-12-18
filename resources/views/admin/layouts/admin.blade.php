<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Toko Bagus')</title>

    <!-- Tailwind CSS via CDN (sementara) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        .sidebar {
            transition: all 0.3s ease;
            background: linear-gradient(180deg, #198561 0%, #34d399 100%);
        }
        .content-area {
            transition: all 0.3s ease;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
        }
        /* Warna hijau lebih terang seperti gambar */
        .bg-emerald-50 { background-color: #ecfdf5; }
        .bg-emerald-100 { background-color: #d1fae5; }
        .bg-emerald-200 { background-color: #a7f3d0; }
        .bg-emerald-300 { background-color: #6ee7b7; }
        .bg-emerald-400 { background-color: #34d399; }
        .bg-emerald-500 { background-color: #10b981; }
        .bg-emerald-600 { background-color: #059669; }
        .bg-emerald-700 { background-color: #095842; }
        .bg-emerald-800 { background-color: #065f46; }
        .bg-emerald-900 { background-color: #064e3b; }

        .text-emerald-50 { color: #ecfdf5; }
        .text-emerald-100 { color: #d1fae5; }
        .text-emerald-200 { color: #a7f3d0; }
        .text-emerald-300 { color: #6ee7b7; }
        .text-emerald-400 { color: #34d399; }
        .text-emerald-500 { color: #10b981; }
        .text-emerald-600 { color: #059669; }
        .text-emerald-700 { color: #047857; }
        .text-emerald-800 { color: #065f46; }
        .text-emerald-900 { color: #064e3b; }

        .border-emerald-200 { border-color: #a7f3d0; }
        .border-emerald-300 { border-color: #6ee7b7; }
        .border-emerald-400 { border-color: #34d399; }
        .border-emerald-500 { border-color: #10b981; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Main Container -->
    <div class="flex min-h-screen">

        <!-- Sidebar - Warna hijau lebih terang -->
        <div class="sidebar text-white w-64 fixed h-full overflow-y-auto">
            <div class="p-4">
                <!-- Logo -->
                <div class="flex items-center mb-8">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-store text-emerald-500 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-white">Toko Bagus</h2>
                        <p class="text-emerald-100 text-xs">Admin Panel</p>
                    </div>
                </div>

                <!-- User Info -->
                <div class="mb-6 p-3 bg-emerald-400/20 backdrop-blur-sm rounded-lg border border-emerald-300/30">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white text-emerald-600 rounded-full flex items-center justify-center font-bold shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-emerald-100">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="mb-6">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('admin.dashboard') }}"
                               class="flex items-center p-3 rounded-lg hover:bg-emerald-400/30 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-400/30' : '' }}">
                                <i class="fas fa-tachometer-alt w-5 mr-3 text-emerald-100"></i>
                                <span class="text-emerald-50">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.products.index') }}"
                               class="flex items-center p-3 rounded-lg hover:bg-emerald-400/30 {{ request()->routeIs('admin.products.*') ? 'bg-emerald-400/30' : '' }}">
                                <i class="fas fa-box w-5 mr-3 text-emerald-100"></i>
                                <span class="text-emerald-50">Produk</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.categories.index') }}"
                               class="flex items-center p-3 rounded-lg hover:bg-emerald-400/30 {{ request()->routeIs('admin.categories.*') ? 'bg-emerald-400/30' : '' }}">
                                <i class="fas fa-tags w-5 mr-3 text-emerald-100"></i>
                                <span class="text-emerald-50">Kategori</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.orders.index') }}"
                               class="flex items-center p-3 rounded-lg hover:bg-emerald-400/30 {{ request()->routeIs('admin.orders.*') ? 'bg-emerald-400/30' : '' }}">
                                <i class="fas fa-shopping-cart w-5 mr-3 text-emerald-100"></i>
                                <span class="text-emerald-50">Pesanan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.index') }}"
                               class="flex items-center p-3 rounded-lg hover:bg-emerald-400/30 {{ request()->routeIs('admin.users.*') ? 'bg-emerald-400/30' : '' }}">
                                <i class="fas fa-users w-5 mr-3 text-emerald-100"></i>
                                <span class="text-emerald-50">Pengguna</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Settings -->
                <div class="pt-6 border-t border-emerald-400/30">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center p-3 rounded-lg hover:bg-emerald-400/30">
                                <i class="fas fa-arrow-left w-5 mr-3 text-emerald-100"></i>
                                <span class="text-emerald-50">Kembali ke User</span>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full p-3 rounded-lg hover:bg-emerald-400/30 text-left">
                                    <i class="fas fa-sign-out-alt w-5 mr-3 text-emerald-100"></i>
                                    <span class="text-emerald-50">Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-area flex-1 ml-64">
            <!-- Top Bar - Warna lebih terang -->
            <header class="bg-gradient-to-r from-emerald-600 to-emerald-500 shadow-sm">
                <div class="flex justify-between items-center px-6 py-4">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="md:hidden text-white">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-white ml-4">
                            @yield('page-title', 'Dashboard')
                        </h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <i class="fas fa-bell text-white text-xl cursor-pointer"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                        </div>
                        <div class="text-sm text-white">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ now()->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6">
                @if(session('success'))
                    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t px-6 py-4">
                <div class="flex justify-between items-center text-sm text-emerald-600">
                    <div>
                        &copy; {{ date('Y') }} Toko Bagus - Admin Panel
                    </div>
                    <div>
                        <span class="text-emerald-600">
                            <i class="fas fa-circle mr-1"></i> System Online
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            const messages = document.querySelectorAll('.bg-emerald-100, .bg-red-100');
            messages.forEach(function(message) {
                message.style.transition = 'opacity 0.5s';
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>

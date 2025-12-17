<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'MotorSpareParts - Toko Sparepart Motor Terlengkap')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional meta tags -->
    <meta name="description" content="Toko sparepart motor terlengkap dengan berbagai brand ternama seperti Proper, BRT, MJM, dan lainnya. Juga menyediakan paket bore up untuk berbagai kebutuhan.">
    <meta name="keywords" content="sparepart motor, bore up, racing, motor modifikasi, pulley, ECU, piston">

    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex flex-col">
    <!-- Header/Navigation -->
    <header class="sticky top-0 z-40 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="settings" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">MotorSpareParts</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">
                        Beranda
                    </a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">
                        Produk
                    </a>
                    <a href="{{ route('packages.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">
                        Paket Bore Up
                    </a>
                    <a href="{{ route('contact') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors">
                        Kontak
                    </a>

                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">
                        <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                        <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">
                            0
                        </span>
                    </a>

                    <!-- Orders -->
                    <a href="{{ route('orders.index') }}" class="p-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">
                        <i data-lucide="package" class="w-6 h-6"></i>
                    </a>

                    <!-- Theme Toggle -->
                    <button id="theme-toggle" type="button" class="p-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">
                        <i data-lucide="sun" class="w-6 h-6 hidden dark:block"></i>
                        <i data-lucide="moon" class="w-6 h-6 block dark:hidden"></i>
                    </button>

                    <!-- User Menu -->
                    @auth
                        <div class="hs-dropdown relative">
                            <button type="button" class="hs-dropdown-toggle flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                    <i data-lucide="user" class="w-4 h-4 text-primary-600 dark:text-primary-400"></i>
                                </div>
                                <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                            </button>

                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[200px] bg-white dark:bg-gray-800 shadow-lg rounded-lg p-2 mt-2 border border-gray-200 dark:border-gray-700">
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                        <span>Admin Dashboard</span>
                                    </a>
                                @else
                                    <a href="{{ route('orders.index') }}" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                                        <i data-lucide="package" class="w-4 h-4"></i>
                                        <span>Pesanan Saya</span>
                                    </a>
                                    <a href="{{ route('profile') }}" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                        <span>Profil</span>
                                    </a>
                                @endif

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-red-600 dark:text-red-400">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary text-sm">
                            Masuk
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center space-x-4">
                    <a href="{{ route('cart.index') }}" class="relative p-2">
                        <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                        <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">
                            0
                        </span>
                    </a>

                    <button id="mobile-menu-toggle" type="button" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden py-4 border-t border-gray-200 dark:border-gray-800">
                <div class="flex flex-col space-y-4">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                        <i data-lucide="home" class="w-5 h-5"></i>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                        <i data-lucide="package" class="w-5 h-5"></i>
                        <span>Produk</span>
                    </a>
                    <a href="{{ route('packages.index') }}" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                        <span>Paket Bore Up</span>
                    </a>
                    <a href="{{ route('contact') }}" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                        <span>Kontak</span>
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span>Pesanan Saya</span>
                    </a>

                    @auth
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                            <p class="px-2 text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->name }}</p>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                    <span>Admin Dashboard</span>
                                </a>
                            @else
                                <a href="{{ route('orders.index') }}" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <i data-lucide="package" class="w-5 h-5"></i>
                                    <span>Pesanan Saya</span>
                                </a>
                                <a href="{{ route('profile') }}" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <i data-lucide="user" class="w-5 h-5"></i>
                                    <span>Profil</span>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-red-600 dark:text-red-400">
                                    <i data-lucide="log-out" class="w-5 h-5"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary text-center">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="settings" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">MotorSpareParts</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Toko sparepart motor terlengkap dengan berbagai brand ternama. Siap melayani kebutuhan modifikasi motor Anda.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Menu</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                                Produk
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('packages.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                                Paket Bore Up
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                                Kontak
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Brands -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Brands</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['Proper', 'BRT', 'MJM', 'MJRT', 'Spectro', 'Yamagata', 'Dr. Pulley'] as $brand)
                            <span class="badge badge-primary">{{ $brand }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Kontak</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="phone" class="w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                            <span class="text-gray-600 dark:text-gray-400">+62 812-3456-7890</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                            <span class="text-gray-600 dark:text-gray-400">info@motorspareparts.com</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i data-lucide="map-pin" class="w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                            <span class="text-gray-600 dark:text-gray-400">Jl. Otomotif No. 123, Jakarta</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700 text-center">
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    &copy; {{ date('Y') }} MotorSpareParts. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    const icon = mobileMenuToggle.querySelector('i');
                    if (mobileMenu.classList.contains('hidden')) {
                        icon.setAttribute('data-lucide', 'menu');
                    } else {
                        icon.setAttribute('data-lucide', 'x');
                    }
                    createIcons({ icons });
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (mobileMenu && !mobileMenu.contains(event.target) && mobileMenuToggle && !mobileMenuToggle.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                    const icon = mobileMenuToggle.querySelector('i');
                    icon.setAttribute('data-lucide', 'menu');
                    createIcons({ icons });
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

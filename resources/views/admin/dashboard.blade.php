@extends('layouts.app')

@section('title', 'Dashboard Admin - MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Admin Header -->
    <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Panel kontrol MotorSpareParts</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-secondary text-sm">
                            <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Admin Navigation -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8">
                <a href="{{ route('admin.dashboard') }}"
                   class="py-4 px-2 border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="py-4 px-2 border-b-2 {{ request()->routeIs('admin.products.*') ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Produk
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="py-4 px-2 border-b-2 {{ request()->routeIs('admin.orders.*') ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Pesanan
                </a>
                <a href="{{ route('admin.packages.index') }}"
                   class="py-4 px-2 border-b-2 {{ request()->routeIs('admin.packages.*') ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Paket
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Orders -->
            <div class="card">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                            <i data-lucide="shopping-bag" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Pesanan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">1,248</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm text-green-600 dark:text-green-400">
                            <i data-lucide="trending-up" class="w-4 h-4 inline mr-1"></i>
                            +12.5% dari bulan lalu
                        </span>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="card">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-4">
                            <i data-lucide="dollar-sign" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pendapatan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp 248.5Jt</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm text-green-600 dark:text-green-400">
                            <i data-lucide="trending-up" class="w-4 h-4 inline mr-1"></i>
                            +8.3% dari bulan lalu
                        </span>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="card">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-4">
                            <i data-lucide="package" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">156</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            12 produk baru bulan ini
                        </span>
                    </div>
                </div>
            </div>

            <!-- Packages -->
            <div class="card">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-4">
                            <i data-lucide="layers" class="w-6 h-6 text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Paket Terjual</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">93</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm text-green-600 dark:text-green-400">
                            <i data-lucide="trending-up" class="w-4 h-4 inline mr-1"></i>
                            +24% dari bulan lalu
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Orders -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Revenue Chart -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            Pendapatan 6 Bulan Terakhir
                        </h3>
                        <div class="h-64">
                            <!-- Chart would go here -->
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg">
                                <div class="text-center">
                                    <i data-lucide="bar-chart" class="w-12 h-12 text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 dark:text-gray-400">Chart Pendapatan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div>
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            Pesanan Terbaru
                        </h3>
                        <div class="space-y-4">
                            @for($i = 0; $i < 5; $i++)
                                <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">#ORD-20240115-ABC{{ $i }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Rp 1.250.000</p>
                                    </div>
                                    <span class="badge badge-success">Paid</span>
                                </div>
                            @endfor
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('admin.orders.index') }}" class="btn-primary w-full text-center py-2">
                                Lihat Semua Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8">
            <div class="card">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        Quick Actions
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.products.create') }}"
                           class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-500 dark:hover:border-primary-500 transition-colors group">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 dark:group-hover:bg-blue-800">
                                    <i data-lucide="plus" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Tambah Produk</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Produk baru</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.packages.create') }}"
                           class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-500 dark:hover:border-primary-500 transition-colors group">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 dark:group-hover:bg-green-800">
                                    <i data-lucide="layers" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Buat Paket</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Paket bore up baru</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.orders.index') }}"
                           class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-500 dark:hover:border-primary-500 transition-colors group">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 dark:group-hover:bg-purple-800">
                                    <i data-lucide="shopping-bag" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Kelola Pesanan</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Lihat & update pesanan</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

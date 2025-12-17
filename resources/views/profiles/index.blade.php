@extends('layouts.app')

@section('title', 'Profil Saya - MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profil Saya</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Kelola informasi akun Anda
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Profile Info -->
            <div class="md:col-span-2">
                <div class="card mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            Informasi Profil
                        </h3>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nama Lengkap
                                    </label>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ auth()->user()->name }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Email
                                    </label>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ auth()->user()->email }}
                                    </p>
                                </div>
                            </div>

                            @if(auth()->user()->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor Telepon
                                </label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ auth()->user()->phone }}
                                </p>
                            </div>
                            @endif

                            @if(auth()->user()->address)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Alamat
                                </label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ auth()->user()->address }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                @php
                    $recentOrders = auth()->user()->orders()->latest()->take(3)->get();
                @endphp

                @if($recentOrders->count() > 0)
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            Pesanan Terbaru
                        </h3>

                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                            <a href="{{ route('orders.show', $order->id) }}"
                               class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $order->order_code }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $order->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-primary-600 dark:text-primary-400">
                                            Rp {{ number_format($order->total, 0, ',', '.') }}
                                        </p>
                                        <span class="badge {{ $order->order_status == 'pending' ? 'badge-warning' : ($order->order_status == 'processing' ? 'badge-primary' : ($order->order_status == 'shipped' ? 'badge-info' : ($order->order_status == 'delivered' ? 'badge-success' : 'badge-danger'))) }} text-xs">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>

                        @if(auth()->user()->orders()->count() > 3)
                        <div class="mt-6">
                            <a href="{{ route('orders.index') }}" class="btn-primary w-full text-center">
                                Lihat Semua Pesanan
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Account Stats -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Statistik Akun
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Total Pesanan</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    {{ auth()->user()->orders()->count() }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Pesanan Berjalan</span>
                                <span class="font-bold text-yellow-600 dark:text-yellow-400">
                                    {{ auth()->user()->orders()->whereIn('order_status', ['pending', 'processing', 'shipped'])->count() }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Pesanan Selesai</span>
                                <span class="font-bold text-green-600 dark:text-green-400">
                                    {{ auth()->user()->orders()->where('order_status', 'delivered')->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Aksi Cepat
                        </h3>

                        <div class="space-y-3">
                            <a href="{{ route('orders.index') }}"
                               class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                <i data-lucide="package" class="w-5 h-5 text-primary-600 dark:text-primary-400 mr-3"></i>
                                <span>Pesanan Saya</span>
                            </a>

                            <a href="{{ route('cart.index') }}"
                               class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                <i data-lucide="shopping-cart" class="w-5 h-5 text-primary-600 dark:text-primary-400 mr-3"></i>
                                <span>Keranjang Belanja</span>
                            </a>

                            <a href="{{ route('products.index') }}"
                               class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                <i data-lucide="shopping-bag" class="w-5 h-5 text-primary-600 dark:text-primary-400 mr-3"></i>
                                <span>Lanjut Belanja</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Kelola Akun
                        </h3>

                        <div class="space-y-3">
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <i data-lucide="user-cog" class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-3"></i>
                                <span>Edit Profil</span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors text-red-600 dark:text-red-400">
                                    <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'MotorSpareParts - Toko Sparepart Motor Terlengkap')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-linear-to-r from-gray-900 to-primary-900 dark:from-gray-800 dark:to-gray-900 text-white">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-black/50"></div>
            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1920&q=80"
                 alt="Motor Parts Background"
                 class="w-full h-full object-cover">
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Sparepart Motor <span class="text-primary-400">Premium</span> untuk Performa Terbaik
                </h1>
                <p class="text-xl text-gray-300 mb-8">
                    Temukan berbagai sparepart motor berkualitas dari brand ternama. Dari kebutuhan harian hingga modifikasi racing, kami punya solusinya.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('products.index') }}" class="btn-primary bg-primary-600 hover:bg-primary-700 text-lg px-8 py-3">
                        <i data-lucide="shopping-bag" class="w-5 h-5 inline mr-2"></i>
                        Belanja Sekarang
                    </a>
                    <a href="{{ route('packages.index') }}" class="btn-secondary bg-white/10 hover:bg-white/20 text-lg px-8 py-3">
                        <i data-lucide="layers" class="w-5 h-5 inline mr-2"></i>
                        Lihat Paket Bore Up
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-12">
                Brand Terpercaya
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-8">
                @foreach(['Proper', 'BRT', 'MJM', 'MJRT', 'Spectro', 'Yamagata', 'Dr. Pulley'] as $brand)
                    <div class="bg-white dark:bg-gray-700 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                            <i data-lucide="star" class="w-8 h-8 text-primary-600 dark:text-primary-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $brand }}</h3>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Produk Unggulan</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Sparepart berkualitas untuk performa maksimal</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">
                    Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4 inline ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $featuredProducts = [
                        [
                            'name' => 'Pulley Custom 14 Derajat',
                            'brand' => 'Proper',
                            'price' => 450000,
                            'category' => 'Transmisi',
                            'image' => 'pulley'
                        ],
                        [
                            'name' => 'ECU Racing',
                            'brand' => 'BRT',
                            'price' => 2500000,
                            'category' => 'Kelistrikan',
                            'image' => 'cpu'
                        ],
                        [
                            'name' => 'Blok Piston 62mm',
                            'brand' => 'MJM',
                            'price' => 1200000,
                            'category' => 'Mesin',
                            'image' => 'cylinder'
                        ],
                        [
                            'name' => 'TB Downdraft 28mm',
                            'brand' => 'Spectro',
                            'price' => 1800000,
                            'category' => 'Bahan Bakar',
                            'image' => 'fuel'
                        ],
                    ];
                @endphp

                @foreach($featuredProducts as $product)
                    <div class="card group">
                        <div class="p-4">
                            <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 rounded-lg mb-4 flex items-center justify-center">
                                <i data-lucide="{{ $product['image'] }}" class="w-16 h-16 text-gray-400 group-hover:text-primary-500 transition-colors"></i>
                            </div>
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="badge badge-primary">{{ $product['brand'] }}</span>
                                    <span class="badge badge-success ml-2">{{ $product['category'] }}</span>
                                </div>
                                <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                    <i data-lucide="heart" class="w-5 h-5 text-gray-400 hover:text-red-500"></i>
                                </button>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $product['name'] }}</h3>
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                    Rp {{ number_format($product['price'], 0, ',', '.') }}
                                </span>
                                <button class="p-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Bore Up Packages -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Paket Bore Up</h2>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Tingkatkan performa motor Anda dengan paket bore up yang sudah teruji. Mulai dari harian hingga racing.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $packages = [
                        [
                            'name' => 'Harian',
                            'description' => 'Untuk kebutuhan sehari-hari dengan peningkatan performa yang halus',
                            'price' => 3500000,
                            'features' => ['Piston + Ring', 'Blok Piston', 'Klep Standar', 'Service Mesin'],
                            'color' => 'blue'
                        ],
                        [
                            'name' => 'Sport',
                            'description' => 'Performa lebih agresif untuk touring dan city riding',
                            'price' => 6500000,
                            'features' => ['Piston Racing', 'Blok Piston Oversize', 'Klep Racing', 'Karburator Racing', 'CDI Racing'],
                            'color' => 'green',
                            'popular' => true
                        ],
                        [
                            'name' => 'Racing',
                            'description' => 'Paket lengkap untuk balap dan performa maksimal',
                            'price' => 12000000,
                            'features' => ['Piston Full Racing', 'Blok Piston Big Bore', 'Klep Full Race', 'TB Downdraft', 'ECU Racing', 'Knalpot Racing'],
                            'color' => 'red'
                        ],
                    ];
                @endphp

                @foreach($packages as $package)
                    <div class="card relative {{ $package['popular'] ?? false ? 'ring-2 ring-primary-500' : '' }}">
                        @if($package['popular'] ?? false)
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <span class="bg-primary-600 text-white px-4 py-1 rounded-full text-sm font-semibold">
                                    Paling Populer
                                </span>
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $package['name'] }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $package['description'] }}</p>

                            <div class="mb-6">
                                <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                                    Rp {{ number_format($package['price'], 0, ',', '.') }}
                                </span>
                            </div>

                            <ul class="space-y-3 mb-8">
                                @foreach($package['features'] as $feature)
                                    <li class="flex items-center">
                                        <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <a href="{{ route('packages.index') }}" class="w-full btn-primary py-3 text-center block">
                                Pilih Paket
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-linear-to-r from-primary-600 to-primary-800 rounded-2xl p-8 md:p-12 text-white">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-8 md:mb-0 md:mr-8">
                        <h2 class="text-3xl font-bold mb-4">Butuh Konsultasi Modifikasi?</h2>
                        <p class="text-primary-100 mb-6">
                            Tim ahli kami siap membantu Anda memilih sparepart dan paket yang tepat untuk kebutuhan motor Anda.
                        </p>
                        <div class="flex items-center space-x-4">
                            <i data-lucide="phone" class="w-6 h-6"></i>
                            <span class="text-xl font-semibold">+62 812-3456-7890</span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <a href="{{ route('contact') }}" class="bg-white text-primary-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg inline-flex items-center">
                            <i data-lucide="message-circle" class="w-5 h-5 mr-2"></i>
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Paket Bore Up Motor - MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="mb-12 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                Paket <span class="text-primary-600 dark:text-primary-400">Bore Up</span> Motor
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto mb-8">
                Tingkatkan performa motor Anda dengan paket bore up profesional.
                Dari kebutuhan harian hingga racing, kami punya solusi terbaik.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#packages" class="btn-primary px-8 py-3">
                    <i data-lucide="layers" class="w-5 h-5 inline mr-2"></i>
                    Lihat Paket
                </a>
                <a href="#consultation" class="btn-secondary px-8 py-3">
                    <i data-lucide="message-circle" class="w-5 h-5 inline mr-2"></i>
                    Konsultasi Gratis
                </a>
            </div>
        </div>

        <!-- Packages Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1">
                <div class="card sticky top-24">
                    <div class="p-6">
                        <!-- Categories Filter -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Kategori Paket
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('packages.index') }}"
                                   class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ !request('category') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                                    Semua Kategori
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('packages.index', ['category' => $category]) }}"
                                       class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request('category') == $category ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                                        @php
                                            $labels = [
                                                'harian' => 'Harian',
                                                'sport' => 'Sport',
                                                'racing' => 'Racing',
                                                'custom' => 'Custom',
                                            ];
                                        @endphp
                                        {{ $labels[$category] ?? ucfirst($category) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Difficulty Filter -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Tingkat Kesulitan
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('packages.index') }}"
                                   class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ !request('difficulty') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                                    Semua Level
                                </a>
                                @foreach($difficultyLevels as $difficulty)
                                    <a href="{{ route('packages.index', ['difficulty' => $difficulty]) }}"
                                       class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request('difficulty') == $difficulty ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                                        @php
                                            $labels = [
                                                'beginner' => 'Pemula',
                                                'intermediate' => 'Menengah',
                                                'expert' => 'Expert',
                                            ];
                                        @endphp
                                        {{ $labels[$difficulty] ?? ucfirst($difficulty) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Range Harga
                            </h3>
                            <form method="GET" action="{{ route('packages.index') }}" class="space-y-4">
                                <div class="flex items-center space-x-2">
                                    <input type="number"
                                           name="min_price"
                                           value="{{ request('min_price') }}"
                                           placeholder="Min"
                                           class="input-field w-20"
                                           min="0">
                                    <span class="text-gray-500">-</span>
                                    <input type="number"
                                           name="max_price"
                                           value="{{ request('max_price') }}"
                                           placeholder="Max"
                                           class="input-field w-20"
                                           min="0">
                                </div>
                                <div class="text-sm text-gray-500">
                                    Range: Rp {{ number_format($priceRange->min_price ?? 0, 0, ',', '.') }} -
                                    Rp {{ number_format($priceRange->max_price ?? 0, 0, ',', '.') }}
                                </div>
                                <button type="submit" class="btn-primary w-full py-2">
                                    Terapkan Filter
                                </button>
                            </form>
                        </div>

                        <!-- Featured Packages -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Paket Terpopuler
                            </h3>
                            <div class="space-y-4">
                                @foreach($featuredPackages as $featured)
                                    <a href="{{ route('packages.show', $featured->slug) }}"
                                       class="block p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mr-3">
                                                <i data-lucide="zap" class="w-6 h-6 text-primary-600 dark:text-primary-400"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-white text-sm">
                                                    {{ $featured->name }}
                                                </h4>
                                                <p class="text-sm text-primary-600 dark:text-primary-400 font-semibold">
                                                    Rp {{ number_format($featured->final_price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        @if(request()->hasAny(['category', 'difficulty', 'min_price', 'max_price']))
                            <div class="mt-6">
                                <a href="{{ route('packages.index') }}" class="btn-secondary w-full text-center py-2">
                                    <i data-lucide="x" class="w-4 h-4 inline mr-2"></i>
                                    Reset Filter
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Packages List -->
            <div class="lg:col-span-3">
                <!-- Packages Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Pilihan Paket Bore Up
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ $packages->total() }} paket tersedia
                        </p>
                    </div>

                    <!-- Sort Options -->
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">Urutkan:</span>
                        <div class="hs-dropdown relative">
                            <button type="button" class="hs-dropdown-toggle px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                                @switch(request('sort', 'popular'))
                                    @case('price_low')
                                        Harga: Terendah
                                        @break
                                    @case('price_high')
                                        Harga: Tertinggi
                                        @break
                                    @case('name')
                                        Nama: A-Z
                                        @break
                                    @default
                                        Terpopuler
                                @endswitch
                                <i data-lucide="chevron-down" class="w-4 h-4 ml-2 inline"></i>
                            </button>

                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-50 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-2 mt-2 border border-gray-200 dark:border-gray-700">
                                <a href="{{ route('packages.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}"
                                   class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm {{ request('sort', 'popular') == 'popular' ? 'text-primary-600 dark:text-primary-400' : '' }}">
                                    Terpopuler
                                </a>
                                <a href="{{ route('packages.index', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}"
                                   class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm {{ request('sort') == 'price_low' ? 'text-primary-600 dark:text-primary-400' : '' }}">
                                    Harga: Terendah
                                </a>
                                <a href="{{ route('packages.index', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}"
                                   class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm {{ request('sort') == 'price_high' ? 'text-primary-600 dark:text-primary-400' : '' }}">
                                    Harga: Tertinggi
                                </a>
                                <a href="{{ route('packages.index', array_merge(request()->except('sort'), ['sort' => 'name'])) }}"
                                   class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm {{ request('sort') == 'name' ? 'text-primary-600 dark:text-primary-400' : '' }}">
                                    Nama: A-Z
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if($packages->count() > 0)
                    <!-- Packages Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="packages">
                        @foreach($packages as $package)
                            <div class="card group hover:shadow-xl transition-all duration-300">
                                <!-- Package Badge -->
                                <div class="relative">
                                    @if($package->hasDiscount())
                                        <div class="absolute top-3 left-3 z-10">
                                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                                -{{ $package->discount_percentage }}%
                                            </span>
                                        </div>
                                    @endif

                                    @if($package->is_featured)
                                        <div class="absolute top-3 right-3 z-10">
                                            <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                                <i data-lucide="star" class="w-3 h-3 inline mr-1"></i>
                                                Populer
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Package Image/Icon -->
                                    <div class="aspect-video bg-linear-to-br from-primary-500 to-primary-700 rounded-t-lg flex items-center justify-center p-8">
                                        <i data-lucide="zap" class="w-20 h-20 text-white opacity-80 group-hover:scale-110 transition-transform duration-300"></i>
                                    </div>
                                </div>

                                <!-- Package Info -->
                                <div class="p-6">
                                    <!-- Category & Difficulty -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="badge badge-primary">
                                                {{ $package->category_label }}
                                            </span>
                                            <span class="badge badge-success">
                                                {{ $package->difficulty_label }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <i data-lucide="users" class="w-4 h-4 inline mr-1"></i>
                                            {{ $package->order_count }} pesanan
                                        </div>
                                    </div>

                                    <!-- Package Name -->
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2">
                                        <a href="{{ route('packages.show', $package->slug) }}"
                                           class="hover:text-primary-600 dark:hover:text-primary-400">
                                            {{ $package->name }}
                                        </a>
                                    </h3>

                                    <!-- Package Description -->
                                    <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                        {{ $package->description }}
                                    </p>

                                    <!-- Key Features -->
                                    <div class="mb-4">
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            <i data-lucide="zap" class="w-4 h-4 mr-2"></i>
                                            @if($package->power_gain_percentage)
                                                <span>+{{ $package->power_gain_percentage }}% daya</span>
                                            @else
                                                <span>Custom setup</span>
                                            @endif
                                            <span class="mx-2">•</span>
                                            <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                                            <span>{{ $package->duration_days }} hari</span>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="mb-6">
                                        @if($package->hasDiscount())
                                            <div class="flex items-center">
                                                <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                                    Rp {{ number_format($package->discount_price, 0, ',', '.') }}
                                                </span>
                                                <span class="text-lg text-gray-500 line-through ml-3">
                                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                                Rp {{ number_format($package->price, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('packages.show', $package->slug) }}"
                                           class="btn-primary flex-1 text-center py-3">
                                            <i data-lucide="info" class="w-4 h-4 inline mr-2"></i>
                                            Detail
                                        </a>
                                        <button onclick="addPackageToCart({{ $package->id }})"
                                                class="btn-secondary p-3">
                                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $packages->links('vendor.pagination.tailwind') }}
                    </div>
                @else
                    <!-- No Packages Found -->
                    <div class="card text-center py-16">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                            <i data-lucide="package-x" class="w-12 h-12 text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                            Paket tidak ditemukan
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                            Tidak ada paket yang sesuai dengan filter yang Anda pilih. Coba gunakan filter lain.
                        </p>
                        <a href="{{ route('packages.index') }}" class="btn-primary inline-flex items-center">
                            <i data-lucide="refresh-ccw" class="w-4 h-4 mr-2"></i>
                            Reset Filter
                        </a>
                    </div>
                @endif

                <!-- Consultation Section -->
                <div class="mt-16" id="consultation">
                    <div class="card bg-linear-to-r from-primary-600 to-primary-800 text-white">
                        <div class="p-8 md:p-12">
                            <div class="flex flex-col md:flex-row items-center justify-between">
                                <div class="mb-8 md:mb-0 md:mr-8">
                                    <h3 class="text-2xl font-bold mb-4">Butuh Konsultasi Bore Up?</h3>
                                    <p class="text-primary-100 mb-6">
                                        Konsultasi gratis dengan ahli modifikasi motor kami.
                                        Dapatkan rekomendasi paket terbaik untuk motor Anda.
                                    </p>
                                    <div class="flex items-center space-x-4">
                                        <i data-lucide="phone" class="w-6 h-6"></i>
                                        <span class="text-xl font-semibold">+62 812-3456-7890</span>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ route('contact') }}"
                                       class="bg-white text-primary-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg inline-flex items-center">
                                        <i data-lucide="message-circle" class="w-5 h-5 mr-2"></i>
                                        Konsultasi Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add package to cart (this would be implemented based on your cart system)
    function addPackageToCart(packageId) {
        // In a real implementation, this would add a service/package to cart
        // For now, we'll redirect to package detail page
        window.location.href = `/packages/${packageId}`;
    }
</script>
@endpush
@endsection

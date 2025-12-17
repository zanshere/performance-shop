@extends('layouts.app')

@section('title', 'Produk Sparepart Motor - MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Katalog Produk</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Temukan sparepart motor berkualitas untuk kebutuhan modifikasi Anda
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:w-1/4">
                <div class="card sticky top-24">
                    <div class="p-6">
                        <!-- Search -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cari Produk</h3>
                            <form id="search-form" method="GET" action="{{ route('products.index') }}">
                                <div class="relative">
                                    <input type="text"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Cari produk..."
                                           class="input-field pl-10">
                                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                </div>
                            </form>
                        </div>

                        <!-- Categories -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Kategori</h3>
                            <div class="space-y-2">
                                <a href="{{ route('products.index') }}"
                                   class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ !request('category') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                                    Semua Kategori
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('products.index', ['category' => $category->name]) }}"
                                       class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request('category') == $category->name ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                                        <div class="flex items-center">
                                            <i data-lucide="{{ $category->icon ?? 'package' }}" class="w-4 h-4 mr-3"></i>
                                            {{ $category->name }}
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Brands -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Brand</h3>
                            <div class="space-y-2">
                                <a href="{{ route('products.index') }}"
                                   class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ !request('brand') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                                    Semua Brand
                                </a>
                                @foreach($brands as $brand)
                                    <a href="{{ route('products.index', ['brand' => $brand->name]) }}"
                                       class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request('brand') == $brand->name ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                                        {{ $brand->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Range Harga</h3>
                            <form id="price-form" method="GET" action="{{ route('products.index') }}">
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-4">
                                        <input type="number"
                                               name="min_price"
                                               value="{{ request('min_price') }}"
                                               placeholder="Min"
                                               class="input-field w-24"
                                               min="0">
                                        <span class="text-gray-500">-</span>
                                        <input type="number"
                                               name="max_price"
                                               value="{{ request('max_price') }}"
                                               placeholder="Max"
                                               class="input-field w-24"
                                               min="0">
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Range: Rp {{ number_format($priceRange->min_price ?? 0, 0, ',', '.') }} -
                                        Rp {{ number_format($priceRange->max_price ?? 0, 0, ',', '.') }}
                                    </div>
                                    <button type="submit" class="btn-primary w-full py-2">
                                        Terapkan Filter
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Clear Filters -->
                        @if(request()->hasAny(['search', 'brand', 'category', 'min_price', 'max_price']))
                            <div>
                                <a href="{{ route('products.index') }}" class="btn-secondary w-full text-center py-2">
                                    <i data-lucide="x" class="w-4 h-4 inline mr-2"></i>
                                    Reset Filter
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:w-3/4">
                <!-- Products Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">
                            Menampilkan <span class="font-semibold">{{ $products->total() }}</span> produk
                            @if(request('search'))
                                untuk "<span class="font-semibold">{{ request('search') }}</span>"
                            @endif
                        </p>
                    </div>

                    <!-- Sort Options -->
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">Urutkan:</span>
                        <div class="hs-dropdown relative">
                            <button type="button" class="hs-dropdown-toggle px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                                @switch(request('sort', 'latest'))
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
                                        Terbaru
                                @endswitch
                                <i data-lucide="chevron-down" class="w-4 h-4 ml-2 inline"></i>
                            </button>

                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[200px] bg-white dark:bg-gray-800 shadow-lg rounded-lg p-2 mt-2 border border-gray-200 dark:border-gray-700">
                                <a href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}"
                                   class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm {{ request('sort', 'latest') == 'latest' ? 'text-primary-600 dark:text-primary-400' : '' }}">
                                    Terbaru
                                </a>
                                <a href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}"
                                   class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm {{ request('sort') == 'price_low' ? 'text-primary-600 dark:text-primary-400' : '' }}">
                                    Harga: Terendah
                                </a>
                                <a href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}"
                                   class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm {{ request('sort') == 'price_high' ? 'text-primary-600 dark:text-primary-400' : '' }}">
                                    Harga: Tertinggi
                                </a>
                                <a href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'name'])) }}"
                                   class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm {{ request('sort') == 'name' ? 'text-primary-600 dark:text-primary-400' : '' }}">
                                    Nama: A-Z
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="card group hover:shadow-xl transition-all duration-300">
                                <!-- Product Image -->
                                <div class="relative overflow-hidden rounded-t-xl">
                                    @if($product->hasDiscount())
                                        <div class="absolute top-3 left-3 z-10">
                                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                                -{{ $product->discount_percentage }}%
                                            </span>
                                        </div>
                                    @endif

                                    @if($product->is_featured)
                                        <div class="absolute top-3 right-3 z-10">
                                            <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                                <i data-lucide="star" class="w-3 h-3 inline mr-1"></i>
                                                Featured
                                            </span>
                                        </div>
                                    @endif

                                    <div class="aspect-square bg-gray-100 dark:bg-gray-700 flex items-center justify-center p-8">
                                        @if($product->images && is_array(json_decode($product->images, true)))
                                            <img src="{{ asset('storage/products/' . json_decode($product->images, true)[0]) }}"
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <i data-lucide="package" class="w-24 h-24 text-gray-400 group-hover:text-primary-500 transition-colors"></i>
                                        @endif
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                        <div class="flex justify-center space-x-2">
                                            <button onclick="addToCart({{ $product->id }})"
                                                    class="bg-white text-gray-900 hover:bg-gray-100 p-2 rounded-lg">
                                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                            </button>
                                            <button class="bg-white text-gray-900 hover:bg-gray-100 p-2 rounded-lg">
                                                <i data-lucide="heart" class="w-5 h-5"></i>
                                            </button>
                                            <a href="{{ route('products.show', $product->id) }}"
                                               class="bg-white text-gray-900 hover:bg-gray-100 p-2 rounded-lg">
                                                <i data-lucide="eye" class="w-5 h-5"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span class="badge badge-primary">{{ $product->brand }}</span>
                                            <span class="badge badge-success ml-2">{{ $product->category }}</span>
                                        </div>
                                        @if(!$product->isInStock())
                                            <span class="badge badge-warning">Habis</span>
                                        @endif
                                    </div>

                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                        <a href="{{ route('products.show', $product->id) }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <!-- Price -->
                                    <div class="flex items-center mb-3">
                                        @if($product->hasDiscount())
                                            <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                                Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-sm text-gray-500 line-through ml-2">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Stock Info -->
                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                                        <div class="flex items-center">
                                            <i data-lucide="package" class="w-4 h-4 mr-1"></i>
                                            <span>{{ $product->stock }} tersedia</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i data-lucide="shipping" class="w-4 h-4 mr-1"></i>
                                            <span>{{ $product->weight ?? 'N/A' }} kg</span>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('products.show', $product->id) }}"
                                           class="btn-primary flex-1 text-center py-2">
                                            <i data-lucide="shopping-bag" class="w-4 h-4 inline mr-2"></i>
                                            Detail
                                        </a>
                                        <button onclick="addToCart({{ $product->id }})"
                                                class="btn-secondary p-2 {{ !$product->isInStock() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ !$product->isInStock() ? 'disabled' : '' }}>
                                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links('vendor.pagination.tailwind') }}
                    </div>
                @else
                    <!-- No Products Found -->
                    <div class="text-center py-16">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                            <i data-lucide="package-x" class="w-12 h-12 text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Produk tidak ditemukan
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Coba gunakan kata kunci lain atau hapus beberapa filter
                        </p>
                        <a href="{{ route('products.index') }}" class="btn-primary inline-flex items-center">
                            <i data-lucide="refresh-ccw" class="w-4 h-4 mr-2"></i>
                            Reset Pencarian
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div id="quick-view-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="card">
            <!-- Modal content will be loaded here via AJAX -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add to Cart Function
    function addToCart(productId) {
        // In a real application, you would make an AJAX request to the server
        // For now, we'll simulate with the frontend cart manager

        // Get product details (in real app, fetch from API)
        const product = {
            id: productId,
            name: 'Product Name', // This should come from API
            price: 100000,
            image: null
        };

        // Add to cart
        window.cartManager.addToCart(product);
    }

    // Quick View Product
    function quickView(productId) {
        // Implement AJAX call to load product details in modal
        fetch(`/api/products/${productId}/quick-view`)
            .then(response => response.json())
            .then(data => {
                document.querySelector('#quick-view-modal .card').innerHTML = data.html;
                const modal = HSOverlay.getInstance('#quick-view-modal');
                modal.open();
            });
    }

    // Initialize dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all HSDropdown instances
        const dropdowns = document.querySelectorAll('.hs-dropdown');
        dropdowns.forEach(dropdown => {
            new HSDropdown(dropdown);
        });

        // Auto submit search on enter
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('search-form').submit();
                }
            });
        }
    });
</script>
@endpush
@endsection

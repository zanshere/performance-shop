@extends('layouts.app')

@section('title', $product->name . ' - MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
                        <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                        <a href="{{ route('products.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white md:ml-2">
                            Produk
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $product->name }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div>
                <!-- Main Image -->
                <div class="card mb-4">
                    <div class="aspect-square bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center p-8">
                        @if($product->images && is_array(json_decode($product->images, true)))
                            <img src="{{ asset('storage/products/' . json_decode($product->images, true)[0]) }}"
                                 alt="{{ $product->name }}"
                                 id="main-product-image"
                                 class="w-full h-full object-contain">
                        @else
                            <i data-lucide="package" class="w-48 h-48 text-gray-400"></i>
                        @endif
                    </div>
                </div>

                <!-- Thumbnail Images -->
                @if($product->images && count(json_decode($product->images, true)) > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(json_decode($product->images, true) as $index => $image)
                            <button onclick="changeMainImage('{{ asset('storage/products/' . $image) }}')"
                                    class="aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-transparent hover:border-primary-500 overflow-hidden">
                                <img src="{{ asset('storage/products/' . $image) }}"
                                     alt="{{ $product->name }} - Thumbnail {{ $index + 1 }}"
                                     class="w-full h-full object-contain p-2">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <!-- Product Header -->
                <div class="mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $product->name }}
                            </h1>
                            <div class="flex items-center space-x-4">
                                <span class="badge badge-primary">{{ $product->brand }}</span>
                                <span class="badge badge-success">{{ $product->category }}</span>
                                @if($product->is_featured)
                                    <span class="badge badge-warning">
                                        <i data-lucide="star" class="w-3 h-3 inline mr-1"></i>
                                        Featured
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Share Button -->
                        <button onclick="shareProduct()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                            <i data-lucide="share-2" class="w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                        </button>
                    </div>

                    <!-- SKU and Stock -->
                    <div class="flex items-center space-x-6 text-sm text-gray-600 dark:text-gray-400 mb-4">
                        <div class="flex items-center">
                            <i data-lucide="hash" class="w-4 h-4 mr-2"></i>
                            <span>SKU: {{ $product->sku }}</span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="package" class="w-4 h-4 mr-2"></i>
                            <span class="{{ $product->isInStock() ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->isInStock() ? $product->stock . ' tersedia' : 'Habis' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <div class="flex items-center mb-2">
                        @if($product->hasDiscount())
                            <span class="text-4xl font-bold text-primary-600 dark:text-primary-400">
                                Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                            </span>
                            <span class="text-xl text-gray-500 line-through ml-4">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold ml-4">
                                Hemat {{ $product->discount_percentage }}%
                            </span>
                        @else
                            <span class="text-4xl font-bold text-primary-600 dark:text-primary-400">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Deskripsi</h3>
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>

                <!-- Specifications -->
                @if($product->specifications)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Spesifikasi</h3>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach(json_decode($product->specifications, true) as $key => $value)
                                    <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                        <dt class="text-gray-600 dark:text-gray-400 font-medium">{{ $key }}</dt>
                                        <dd class="text-gray-900 dark:text-white">{{ $value }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                @endif

                <!-- Compatibility -->
                @if($product->compatibility)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Kompatibilitas</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach(json_decode($product->compatibility, true) as $motor)
                                <span class="badge badge-primary">{{ $motor }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quantity and Actions -->
                <div class="mb-8">
                    <div class="flex items-center space-x-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jumlah
                            </label>
                            <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg">
                                <button onclick="updateQuantity(-1)"
                                        class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <i data-lucide="minus" class="w-4 h-4"></i>
                                </button>
                                <input type="number"
                                       id="quantity"
                                       value="1"
                                       min="1"
                                       max="{{ $product->stock }}"
                                       class="w-16 text-center border-0 bg-transparent text-gray-900 dark:text-white">
                                <button onclick="updateQuantity(1)"
                                        class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Maksimal {{ $product->stock }} pcs
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button onclick="addToCart({{ $product->id }})"
                                class="btn-primary flex-1 py-3 text-lg {{ !$product->isInStock() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ !$product->isInStock() ? 'disabled' : '' }}>
                            <i data-lucide="shopping-cart" class="w-5 h-5 inline mr-2"></i>
                            Tambah ke Keranjang
                        </button>

                        <a href="{{ route('checkout') }}"
                           class="btn-secondary flex-1 py-3 text-lg text-center {{ !$product->isInStock() ? 'opacity-50 cursor-not-allowed' : '' }}"
                           {{ !$product->isInStock() ? 'disabled' : '' }}>
                            <i data-lucide="credit-card" class="w-5 h-5 inline mr-2"></i>
                            Beli Sekarang
                        </a>

                        <button class="p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <i data-lucide="truck" class="w-5 h-5 text-primary-600 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Gratis Ongkir</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Min. pembelian Rp 500.000</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="shield-check" class="w-5 h-5 text-primary-600 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Garansi Resmi</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">1-6 bulan tergantung produk</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
                    Produk Terkait
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="card group">
                            <a href="{{ route('products.show', $relatedProduct->id) }}">
                                <div class="aspect-square bg-gray-100 dark:bg-gray-700 rounded-t-lg flex items-center justify-center p-4">
                                    @if($relatedProduct->images && is_array(json_decode($relatedProduct->images, true)))
                                        <img src="{{ asset('storage/products/' . json_decode($relatedProduct->images, true)[0]) }}"
                                             alt="{{ $relatedProduct->name }}"
                                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <i data-lucide="package" class="w-16 h-16 text-gray-400"></i>
                                    @endif
                                </div>
                            </a>
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="badge badge-primary">{{ $relatedProduct->brand }}</span>
                                    @if($relatedProduct->hasDiscount())
                                        <span class="text-sm font-semibold text-red-600">
                                            -{{ $relatedProduct->discount_percentage }}%
                                        </span>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                    <a href="{{ route('products.show', $relatedProduct->id) }}"
                                       class="hover:text-primary-600 dark:hover:text-primary-400">
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h3>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-primary-600 dark:text-primary-400">
                                        Rp {{ number_format($relatedProduct->final_price, 0, ',', '.') }}
                                    </span>
                                    <button onclick="addToCart({{ $relatedProduct->id }})"
                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Change main product image
    function changeMainImage(imageUrl) {
        document.getElementById('main-product-image').src = imageUrl;
    }

    // Update quantity
    function updateQuantity(change) {
        const input = document.getElementById('quantity');
        let value = parseInt(input.value);
        const max = parseInt(input.max);
        const min = parseInt(input.min);

        value += change;
        if (value < min) value = min;
        if (value > max) value = max;

        input.value = value;
    }

    // Add to cart with quantity
    function addToCart(productId) {
        const quantity = parseInt(document.getElementById('quantity').value);

        // In a real application, make AJAX request
        const product = {
            id: productId,
            name: '{{ $product->name }}',
            price: {{ $product->final_price }},
            image: '{{ $product->images ? json_decode($product->images, true)[0] : null }}',
            quantity: quantity
        };

        // Add to cart manager
        for (let i = 0; i < quantity; i++) {
            window.cartManager.addToCart(product);
        }
    }

    // Share product
    function shareProduct() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $product->name }}',
                text: 'Lihat produk ini di MotorSpareParts: {{ $product->name }}',
                url: window.location.href,
            });
        } else {
            // Fallback for browsers that don't support Web Share API
            navigator.clipboard.writeText(window.location.href);
            alert('Link produk telah disalin ke clipboard!');
        }
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Update max quantity based on stock
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            quantityInput.addEventListener('change', function() {
                let value = parseInt(this.value);
                const max = parseInt(this.max);
                const min = parseInt(this.min);

                if (value < min) this.value = min;
                if (value > max) this.value = max;
            });
        }
    });
</script>
@endpush
@endsection

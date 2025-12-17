<div class="p-6">
    <button type="button" class="hs-dropdown-toggle absolute top-3 right-3 inline-flex justify-center items-center gap-2 rounded-full border border-transparent font-medium text-gray-800 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2 transition-all text-sm dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white dark:focus:ring-gray-900 dark:focus:ring-offset-gray-800" data-hs-overlay="#quick-view-modal">
        <span class="sr-only">Close</span>
        <i data-lucide="x" class="w-4 h-4"></i>
    </button>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center p-4">
            @if($product->images && is_array(json_decode($product->images, true)))
                <img src="{{ asset('storage/products/' . json_decode($product->images, true)[0]) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-contain">
            @else
                <i data-lucide="package" class="w-24 h-24 text-gray-400"></i>
            @endif
        </div>

        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $product->name }}</h3>
            <div class="flex items-center space-x-2 mb-4">
                <span class="badge badge-primary">{{ $product->brand }}</span>
                <span class="badge badge-success">{{ $product->category }}</span>
            </div>

            <div class="mb-4">
                @if($product->hasDiscount())
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                            Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                        </span>
                        <span class="text-lg text-gray-500 line-through ml-4">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold ml-4">
                            -{{ $product->discount_percentage }}%
                        </span>
                    </div>
                @else
                    <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                @endif
            </div>

            <p class="text-gray-600 dark:text-gray-400 mb-6 line-clamp-3">
                {{ Str::limit($product->description, 150) }}
            </p>

            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg">
                        <button onclick="updateQuickViewQuantity(-1)"
                                class="px-3 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                            <i data-lucide="minus" class="w-4 h-4"></i>
                        </button>
                        <input type="number"
                               id="quick-view-quantity"
                               value="1"
                               min="1"
                               max="{{ $product->stock }}"
                               class="w-12 text-center border-0 bg-transparent text-gray-900 dark:text-white">
                        <button onclick="updateQuickViewQuantity(1)"
                                class="px-3 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Stok: {{ $product->stock }}
                    </div>
                </div>

                <div class="flex space-x-2">
                    <button onclick="addToCart({{ $product->id }})"
                            class="btn-primary flex-1 py-2 {{ !$product->isInStock() ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ !$product->isInStock() ? 'disabled' : '' }}>
                        <i data-lucide="shopping-cart" class="w-4 h-4 inline mr-2"></i>
                        Tambah ke Keranjang
                    </button>
                    <a href="{{ route('products.show', $product->id) }}"
                       class="btn-secondary py-2 px-4">
                        <i data-lucide="eye" class="w-4 h-4 inline mr-2"></i>
                        Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateQuickViewQuantity(change) {
        const input = document.getElementById('quick-view-quantity');
        let value = parseInt(input.value);
        const max = parseInt(input.max);
        const min = parseInt(input.min);

        value += change;
        if (value < min) value = min;
        if (value > max) value = max;

        input.value = value;
    }
</script>

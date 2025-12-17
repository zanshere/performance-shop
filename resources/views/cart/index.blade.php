@extends('layouts.app')

@section('title', 'Keranjang Belanja - MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Keranjang Belanja</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Periksa dan kelola produk dalam keranjang Anda
            </p>
        </div>

        @if(empty($cart['items']))
            <!-- Empty Cart -->
            <div class="card text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="w-12 h-12 text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Keranjang Belanja Kosong
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Anda belum menambahkan produk ke keranjang. Mulai belanja untuk menemukan sparepart motor yang Anda butuhkan.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}" class="btn-primary inline-flex items-center">
                        <i data-lucide="shopping-bag" class="w-4 h-4 mr-2"></i>
                        Mulai Belanja
                    </a>
                    <a href="{{ route('packages.index') }}" class="btn-secondary inline-flex items-center">
                        <i data-lucide="layers" class="w-4 h-4 mr-2"></i>
                        Lihat Paket Bore Up
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <!-- Cart Header -->
                    <div class="card mb-4">
                        <div class="p-4 flex justify-between items-center">
                            <div class="flex items-center">
                                <i data-lucide="shopping-cart" class="w-5 h-5 text-primary-600 mr-3"></i>
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ $cart['count'] }} Produk di Keranjang
                                </span>
                            </div>
                            <button onclick="clearCart()"
                                    class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 flex items-center">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                Kosongkan Keranjang
                            </button>
                        </div>
                    </div>

                    <!-- Cart Items List -->
                    <div class="space-y-4">
                        @foreach($cart['items'] as $item)
                            <div class="card" id="cart-item-{{ $item['id'] }}">
                                <div class="p-4 flex flex-col sm:flex-row gap-4">
                                    <!-- Product Image -->
                                    <div class="shrink-0">
                                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            @if($item['image'])
                                                <img src="{{ asset('storage/products/' . $item['image']) }}"
                                                     alt="{{ $item['name'] }}"
                                                     class="w-full h-full object-contain p-2">
                                            @else
                                                <i data-lucide="package" class="w-8 h-8 text-gray-400"></i>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-grow">
                                        <div class="flex justify-between">
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                                                    {{ $item['name'] }}
                                                </h3>
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <span class="badge badge-primary text-xs">{{ $item['brand'] }}</span>
                                                    <span class="badge badge-success text-xs">{{ $item['category'] }}</span>
                                                </div>
                                            </div>
                                            <button onclick="removeFromCart({{ $item['id'] }})"
                                                    class="text-gray-400 hover:text-red-500">
                                                <i data-lucide="x" class="w-5 h-5"></i>
                                            </button>
                                        </div>

                                        <!-- Price and Quantity -->
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                                            <div class="mb-4 sm:mb-0">
                                                @if($item['has_discount'])
                                                    <div class="flex items-center">
                                                        <span class="text-xl font-bold text-primary-600 dark:text-primary-400">
                                                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                                                        </span>
                                                        <span class="text-sm text-gray-500 line-through ml-2">
                                                            Rp {{ number_format($item['original_price'], 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-xl font-bold text-primary-600 dark:text-primary-400">
                                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center space-x-4">
                                                <!-- Quantity Control -->
                                                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg">
                                                    <button onclick="updateQuantity({{ $item['id'] }}, -1)"
                                                            class="px-3 py-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                        <i data-lucide="minus" class="w-4 h-4"></i>
                                                    </button>
                                                    <input type="number"
                                                           id="quantity-{{ $item['id'] }}"
                                                           value="{{ $item['quantity'] }}"
                                                           min="1"
                                                           max="{{ $item['stock'] }}"
                                                           class="w-16 text-center border-0 bg-transparent text-gray-900 dark:text-white"
                                                           onchange="updateQuantity({{ $item['id'] }}, 0, this.value)">
                                                    <button onclick="updateQuantity({{ $item['id'] }}, 1)"
                                                            class="px-3 py-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                                    </button>
                                                </div>

                                                <!-- Subtotal -->
                                                <div class="text-right">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Subtotal</p>
                                                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stock Info -->
                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            <i data-lucide="package" class="w-4 h-4 inline mr-1"></i>
                                            Stok tersedia: {{ $item['stock'] }} pcs
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Continue Shopping -->
                    <div class="mt-6">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 dark:text-primary-400">
                            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                            Lanjutkan Belanja
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="card sticky top-24">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                Ringkasan Pesanan
                            </h3>

                            <!-- Order Details -->
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        Rp {{ number_format($cart['subtotal'], 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Ongkos Kirim</span>
                                    <span class="text-gray-900 dark:text-white">
                                        Akan dihitung saat checkout
                                    </span>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Total</span>
                                        <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                            Rp {{ number_format($cart['subtotal'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Promo Code (Optional) -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kode Promo
                                </label>
                                <div class="flex gap-2">
                                    <input type="text"
                                           placeholder="Masukkan kode promo"
                                           class="input-field flex-grow">
                                    <button class="btn-secondary px-4">
                                        Terapkan
                                    </button>
                                </div>
                            </div>

                            <!-- Checkout Button -->
                            <a href="{{ route('checkout') }}" class="btn-primary w-full py-3 text-lg mb-4">
                                <i data-lucide="credit-card" class="w-5 h-5 inline mr-2"></i>
                                Lanjut ke Checkout
                            </a>

                            <!-- Payment Methods -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    Metode Pembayaran:
                                </p>
                                <div class="flex space-x-2">
                                    <div class="w-10 h-6 bg-blue-100 rounded flex items-center justify-center">
                                        <span class="text-xs font-semibold text-blue-800">BCA</span>
                                    </div>
                                    <div class="w-10 h-6 bg-red-100 rounded flex items-center justify-center">
                                        <span class="text-xs font-semibold text-red-800">MDR</span>
                                    </div>
                                    <div class="w-10 h-6 bg-green-100 rounded flex items-center justify-center">
                                        <span class="text-xs font-semibold text-green-800">VA</span>
                                    </div>
                                    <div class="w-10 h-6 bg-yellow-100 rounded flex items-center justify-center">
                                        <span class="text-xs font-semibold text-yellow-800">QRIS</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="mt-6 space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-start">
                                    <i data-lucide="shield-check" class="w-4 h-4 mt-0.5 mr-2 text-green-600"></i>
                                    <span>Transaksi aman dan terenkripsi</span>
                                </div>
                                <div class="flex items-start">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 mt-0.5 mr-2 text-blue-600"></i>
                                    <span>Garansi produk 1-6 bulan</span>
                                </div>
                                <div class="flex items-start">
                                    <i data-lucide="truck" class="w-4 h-4 mt-0.5 mr-2 text-orange-600"></i>
                                    <span>Gratis ongkir min. belanja Rp 500.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Update cart item quantity
    function updateQuantity(productId, change, newValue = null) {
        const input = document.getElementById(`quantity-${productId}`);
        let quantity = newValue !== null ? parseInt(newValue) : parseInt(input.value) + change;

        // Validate min and max
        const min = parseInt(input.min);
        const max = parseInt(input.max);

        if (quantity < min) quantity = min;
        if (quantity > max) quantity = max;

        // Update input value
        input.value = quantity;

        // Send AJAX request to update cart
        fetch(`{{ route('cart.update', '') }}/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                quantity: quantity,
                _method: 'PUT'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count in header
                window.cartManager.updateCartCount();

                // Update item subtotal in UI
                const itemElement = document.getElementById(`cart-item-${productId}`);
                if (itemElement) {
                    const subtotalElement = itemElement.querySelector('.item-subtotal');
                    if (subtotalElement) {
                        subtotalElement.textContent = `Rp ${data.item_subtotal.toLocaleString('id-ID')}`;
                    }
                }

                // Show success notification
                window.cartManager.showNotification('Jumlah produk berhasil diperbarui');
            } else {
                alert(data.message);
                // Reset to original value
                input.value = parseInt(input.value) - change;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui keranjang');
            input.value = parseInt(input.value) - change;
        });
    }

    // Remove item from cart
    function removeFromCart(productId) {
        if (!confirm('Hapus produk dari keranjang?')) return;

        fetch(`{{ route('cart.remove', '') }}/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove item from UI
                const itemElement = document.getElementById(`cart-item-${productId}`);
                if (itemElement) {
                    itemElement.remove();
                }

                // Update cart count
                window.cartManager.updateCartCount();

                // Show success notification
                window.cartManager.showNotification('Produk berhasil dihapus dari keranjang');

                // If cart is empty, reload page
                if (data.cart_count === 0) {
                    setTimeout(() => location.reload(), 1000);
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus produk');
        });
    }

    // Clear entire cart
    function clearCart() {
        if (!confirm('Kosongkan seluruh keranjang belanja?')) return;

        fetch(`{{ route('cart.clear') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to show empty cart
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengosongkan keranjang');
        });
    }

    // Initialize cart items
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to quantity inputs
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.id.replace('quantity-', '');
                updateQuantity(productId, 0, this.value);
            });
        });
    });
</script>
@endpush
@endsection

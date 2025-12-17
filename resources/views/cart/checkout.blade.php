@extends('layouts.app')

@section('title', 'Checkout - MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Checkout Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center">
                        <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    </div>
                    <div class="h-1 w-16 bg-primary-600"></div>
                </div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center">
                        <span class="text-sm font-semibold">2</span>
                    </div>
                    <div class="h-1 w-16 bg-gray-300 dark:bg-gray-700"></div>
                </div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-400 flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>
            <div class="flex justify-center mt-4 text-sm">
                <div class="text-center w-24">
                    <div class="font-medium text-primary-600 dark:text-primary-400">Keranjang</div>
                </div>
                <div class="text-center w-24">
                    <div class="font-medium text-primary-600 dark:text-primary-400">Checkout</div>
                </div>
                <div class="text-center w-24">
                    <div class="font-medium text-gray-600 dark:text-gray-400">Pembayaran</div>
                </div>
            </div>
        </div>

        <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Customer Info -->
                <div class="lg:col-span-2">
                    <!-- Customer Information -->
                    <div class="card mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                <i data-lucide="user" class="w-5 h-5 inline mr-2"></i>
                                Informasi Pemesan
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Full Name -->
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nama Lengkap *
                                    </label>
                                    <input type="text"
                                           id="customer_name"
                                           name="customer_name"
                                           value="{{ Auth::check() ? Auth::user()->name : '' }}"
                                           required
                                           class="input-field"
                                           placeholder="Masukkan nama lengkap">
                                    @error('customer_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Email *
                                    </label>
                                    <input type="email"
                                           id="customer_email"
                                           name="customer_email"
                                           value="{{ Auth::check() ? Auth::user()->email : '' }}"
                                           required
                                           class="input-field"
                                           placeholder="email@contoh.com">
                                    @error('customer_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nomor Telepon *
                                    </label>
                                    <input type="tel"
                                           id="customer_phone"
                                           name="customer_phone"
                                           required
                                           class="input-field"
                                           placeholder="0812-3456-7890">
                                    @error('customer_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label for="customer_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Alamat Lengkap *
                                    </label>
                                    <textarea id="customer_address"
                                              name="customer_address"
                                              rows="3"
                                              required
                                              class="input-field"
                                              placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan"></textarea>
                                    @error('customer_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- City -->
                                <div>
                                    <label for="customer_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Kota *
                                    </label>
                                    <input type="text"
                                           id="customer_city"
                                           name="customer_city"
                                           required
                                           class="input-field"
                                           placeholder="Jakarta">
                                    @error('customer_city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Province -->
                                <div>
                                    <label for="customer_province" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Provinsi *
                                    </label>
                                    <input type="text"
                                           id="customer_province"
                                           name="customer_province"
                                           required
                                           class="input-field"
                                           placeholder="DKI Jakarta">
                                    @error('customer_province')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Postal Code -->
                                <div>
                                    <label for="customer_postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Kode Pos *
                                    </label>
                                    <input type="text"
                                           id="customer_postal_code"
                                           name="customer_postal_code"
                                           required
                                           class="input-field"
                                           placeholder="12345">
                                    @error('customer_postal_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="card mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                <i data-lucide="truck" class="w-5 h-5 inline mr-2"></i>
                                Metode Pengiriman
                            </h3>

                            <div class="space-y-4">
                                @foreach($shippingOptions as $option)
                                    <label class="flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-primary-500">
                                        <input type="radio"
                                               name="shipping_service"
                                               value="{{ $option['code'] }}"
                                               class="mr-4 text-primary-600 focus:ring-primary-500"
                                               {{ $loop->first ? 'checked' : '' }}
                                               required>
                                        <div class="flex-grow">
                                            <div class="flex justify-between">
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ $option['name'] }}
                                                </span>
                                                <span class="font-semibold text-primary-600 dark:text-primary-400">
                                                    Rp {{ number_format($option['cost'], 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                {{ $option['description'] }}
                                            </p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Shipping Note -->
                            <div class="mt-6">
                                <label for="shipping_note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan Pengiriman (Opsional)
                                </label>
                                <textarea id="shipping_note"
                                          name="shipping_note"
                                          rows="2"
                                          class="input-field"
                                          placeholder="Contoh: Kirim jam 10-12 siang, rumah warna hijau"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                <i data-lucide="credit-card" class="w-5 h-5 inline mr-2"></i>
                                Metode Pembayaran
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="relative">
                                    <input type="radio"
                                           name="payment_method"
                                           value="bank_transfer"
                                           class="sr-only peer"
                                           checked
                                           required>
                                    <div class="p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-primary-500 peer-checked:border-primary-500 peer-checked:ring-2 peer-checked:ring-primary-200 dark:peer-checked:ring-primary-900">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                                <i data-lucide="building" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">Transfer Bank</div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">BCA, Mandiri, BRI, dll.</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative">
                                    <input type="radio"
                                           name="payment_method"
                                           value="credit_card"
                                           class="sr-only peer"
                                           required>
                                    <div class="p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-primary-500 peer-checked:border-primary-500 peer-checked:ring-2 peer-checked:ring-primary-200 dark:peer-checked:ring-primary-900">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                                <i data-lucide="credit-card" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">Kartu Kredit</div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Visa, Mastercard</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative">
                                    <input type="radio"
                                           name="payment_method"
                                           value="ewallet"
                                           class="sr-only peer"
                                           required>
                                    <div class="p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-primary-500 peer-checked:border-primary-500 peer-checked:ring-2 peer-checked:ring-primary-200 dark:peer-checked:ring-primary-900">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                                                <i data-lucide="smartphone" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">E-Wallet</div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Gopay, OVO, Dana</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="lg:col-span-1">
                    <div class="card sticky top-24">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                Ringkasan Pesanan
                            </h3>

                            <!-- Order Items -->
                            <div class="space-y-4 mb-6">
                                @foreach($cart['items'] as $item)
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $item['name'] }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $item['quantity'] }} × Rp {{ number_format($item['price'], 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Totals -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                    <span class="text-gray-900 dark:text-white">
                                        Rp {{ number_format($cart['subtotal'], 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Ongkos Kirim</span>
                                    <span class="text-gray-900 dark:text-white">
                                        <span id="shipping-cost">Rp 0</span>
                                    </span>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Total</span>
                                        <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                            <span id="total-amount">Rp {{ number_format($cart['subtotal'], 0, ',', '.') }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mt-6">
                                <label class="flex items-start">
                                    <input type="checkbox"
                                           name="terms"
                                           required
                                           class="mt-1 mr-3 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Saya menyetujui
                                        <a href="#" class="text-primary-600 hover:text-primary-700 dark:text-primary-400">
                                            Syarat & Ketentuan
                                        </a>
                                        dan
                                        <a href="#" class="text-primary-600 hover:text-primary-700 dark:text-primary-400">
                                            Kebijakan Privasi
                                        </a>
                                        MotorSpareParts
                                    </span>
                                </label>
                                @error('terms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Place Order Button -->
                            <button type="submit"
                                    class="btn-primary w-full py-3 text-lg mt-6"
                                    id="place-order-btn">
                                <i data-lucide="shopping-bag" class="w-5 h-5 inline mr-2"></i>
                                Buat Pesanan
                            </button>

                            <!-- Security Info -->
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i data-lucide="shield-check" class="w-4 h-4 mr-2 text-green-600"></i>
                                    <span>Pembayaran aman dengan enkripsi SSL</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    // Calculate shipping cost based on selected service
    function calculateShippingCost(serviceCode) {
        const shippingOptions = @json($shippingOptions);
        const option = shippingOptions.find(opt => opt.code === serviceCode);
        return option ? option.cost : 0;
    }

    // Update order totals when shipping method changes
    function updateOrderTotals() {
        const selectedShipping = document.querySelector('input[name="shipping_service"]:checked');
        if (!selectedShipping) return;

        const shippingCost = calculateShippingCost(selectedShipping.value);
        const subtotal = {{ $cart['subtotal'] }};
        const total = subtotal + shippingCost;

        // Update UI
        document.getElementById('shipping-cost').textContent =
            'Rp ' + shippingCost.toLocaleString('id-ID');
        document.getElementById('total-amount').textContent =
            'Rp ' + total.toLocaleString('id-ID');
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Update totals initially
        updateOrderTotals();

        // Listen for shipping method changes
        document.querySelectorAll('input[name="shipping_service"]').forEach(input => {
            input.addEventListener('change', updateOrderTotals);
        });

        // Form submission
        const form = document.getElementById('checkout-form');
        const submitBtn = document.getElementById('place-order-btn');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i data-lucide="loader" class="w-5 h-5 inline mr-2 animate-spin"></i>Memproses...';

            // Submit form
            this.submit();
        });
    });
</script>

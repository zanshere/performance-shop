@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_code . ' - MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('orders.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 dark:text-primary-400">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Kembali ke Daftar Pesanan
            </a>
        </div>

        <!-- Order Header -->
        <div class="card mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Pesanan #{{ $order->order_code }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="badge badge-{{ $order->payment_status_badge }}">
                                <i data-lucide="credit-card" class="w-3 h-3 inline mr-1"></i>
                                {{ ucfirst($order->payment_status) }}
                            </span>
                            <span class="badge badge-{{ $order->order_status_badge }}">
                                <i data-lucide="package" class="w-3 h-3 inline mr-1"></i>
                                {{ ucfirst($order->order_status) }}
                            </span>
                            @if($order->isPaid())
                                <span class="text-sm text-green-600 dark:text-green-400">
                                    <i data-lucide="check-circle" class="w-4 h-4 inline mr-1"></i>
                                    Dibayar pada {{ $order->paid_at->format('d M Y, H:i') }}
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">
                            Tanggal Pesanan: {{ $order->created_at->format('d F Y, H:i') }}
                        </p>
                    </div>

                    <div class="text-right">
                        <div class="text-3xl font-bold text-primary-600 dark:text-primary-400 mb-2">
                            {{ $order->formatted_total }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $order->payment_method == 'bank_transfer' ? 'Transfer Bank' :
                               $order->payment_method == 'credit_card' ? 'Kartu Kredit' : 'E-Wallet' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            <i data-lucide="package" class="w-5 h-5 inline mr-2"></i>
                            Item Pesanan
                        </h3>

                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            @if($item->product && $item->product->images)
                                                <img src="{{ asset('storage/products/' . json_decode($item->product->images, true)[0]) }}"
                                                     alt="{{ $item->product_name }}"
                                                     class="w-full h-full object-contain p-1">
                                            @else
                                                <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-grow">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">
                                            {{ $item->product_name }}
                                        </h4>
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            <span class="mr-4">
                                                {{ $item->quantity }} ×
                                                @if($item->product_discount_price)
                                                    <span class="line-through text-gray-500">
                                                        Rp {{ number_format($item->product_price, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-primary-600 dark:text-primary-400 ml-2">
                                                        Rp {{ number_format($item->product_discount_price, 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    Rp {{ number_format($item->product_price, 0, ',', '.') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </div>
                                        @if($item->product_discount_price)
                                            <div class="text-sm text-green-600 dark:text-green-400">
                                                Hemat Rp {{ number_format(($item->product_price - $item->product_discount_price) * $item->quantity, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            <i data-lucide="history" class="w-5 h-5 inline mr-2"></i>
                            Status Pesanan
                        </h3>

                        <div class="relative">
                            <!-- Timeline -->
                            <div class="space-y-6">
                                @php
                                    $timeline = [
                                        [
                                            'status' => 'Pesanan Dibuat',
                                            'date' => $order->created_at,
                                            'icon' => 'shopping-cart',
                                            'active' => true,
                                        ],
                                        [
                                            'status' => 'Pembayaran',
                                            'date' => $order->isPaid() ? $order->paid_at : null,
                                            'icon' => 'credit-card',
                                            'active' => $order->isPaid(),
                                        ],
                                        [
                                            'status' => 'Diproses',
                                            'date' => $order->order_status == 'processing' ? $order->updated_at : null,
                                            'icon' => 'package',
                                            'active' => in_array($order->order_status, ['processing', 'shipped', 'delivered']),
                                        ],
                                        [
                                            'status' => 'Dikirim',
                                            'date' => $order->shipped_at,
                                            'icon' => 'truck',
                                            'active' => in_array($order->order_status, ['shipped', 'delivered']),
                                        ],
                                        [
                                            'status' => 'Selesai',
                                            'date' => $order->delivered_at,
                                            'icon' => 'check-circle',
                                            'active' => $order->order_status == 'delivered',
                                        ],
                                    ];
                                @endphp

                                @foreach($timeline as $item)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                                {{ $item['active'] ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' }}">
                                                <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                                            </div>
                                            @if(!$loop->last)
                                                <div class="h-12 w-0.5 mx-auto {{ $item['active'] ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-700' }}"></div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $item['status'] }}
                                            </div>
                                            @if($item['date'])
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $item['date']->format('d F Y, H:i') }}
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-500 dark:text-gray-500">
                                                    Menunggu
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="space-y-6">
                <!-- Customer Info -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i data-lucide="user" class="w-5 h-5 inline mr-2"></i>
                            Informasi Pemesan
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Nama</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer_email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Telepon</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer_phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i data-lucide="truck" class="w-5 h-5 inline mr-2"></i>
                            Alamat Pengiriman
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer_name }}</p>
                                <p class="text-gray-600 dark:text-gray-400">{{ $order->customer_phone }}</p>
                            </div>
                            <div>
                                <p class="text-gray-700 dark:text-gray-300">{{ $order->customer_address }}</p>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ $order->customer_city }}, {{ $order->customer_province }} {{ $order->customer_postal_code }}
                                </p>
                            </div>
                            @if($order->shipping_service)
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Kurir</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->shipping_service }}</p>
                                </div>
                            @endif
                            @if($order->shipping_note)
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Catatan</p>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $order->shipping_note }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Total -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i data-lucide="receipt" class="w-5 h-5 inline mr-2"></i>
                            Ringkasan Pembayaran
                        </h3>

                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span class="text-gray-900 dark:text-white">
                                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Ongkos Kirim</span>
                                <span class="text-gray-900 dark:text-white">
                                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-2">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Total</span>
                                    <span class="text-xl font-bold text-primary-600 dark:text-primary-400">
                                        {{ $order->formatted_total }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 space-y-2">
                            @if($order->isPending())
                                <button onclick="payNow()" class="btn-primary w-full py-3">
                                    <i data-lucide="credit-card" class="w-5 h-5 inline mr-2"></i>
                                    Bayar Sekarang
                                </button>
                            @endif

                            @if($order->canBeCancelled())
                                <button onclick="showCancelModal()" class="btn-secondary w-full py-3">
                                    <i data-lucide="x" class="w-5 h-5 inline mr-2"></i>
                                    Batalkan Pesanan
                                </button>
                            @endif

                            @if($order->isPaid())
                                <a href="{{ route('orders.invoice', $order->id) }}"
                                   target="_blank"
                                   class="btn-secondary w-full py-3 text-center">
                                    <i data-lucide="download" class="w-5 h-5 inline mr-2"></i>
                                    Unduh Invoice
                                </a>

                                <button onclick="reorder()" class="btn-secondary w-full py-3">
                                    <i data-lucide="refresh-cw" class="w-5 h-5 inline mr-2"></i>
                                    Pesan Lagi
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
@if($order->isPending() && $snapToken)
<div id="payment-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-4xl sm:w-full m-3 sm:mx-auto">
        <div class="card h-[80vh]">
            <div class="p-6 h-full flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Pembayaran Pesanan #{{ $order->order_code }}
                    </h3>
                    <button type="button" class="hs-dropdown-toggle p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                            data-hs-overlay="#payment-modal">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="flex-grow">
                    <div id="snap-container" class="h-full w-full"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Cancel Order Modal -->
<div id="cancel-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="card">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Batalkan Pesanan
                    </h3>
                    <button type="button" class="hs-dropdown-toggle p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                            data-hs-overlay="#cancel-modal">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form id="cancel-form" method="POST" action="{{ route('orders.cancel', $order->id) }}">
                    @csrf
                    @method('POST')

                    <div class="mb-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Pembatalan *
                        </label>
                        <textarea id="reason"
                                  name="reason"
                                  rows="4"
                                  required
                                  class="input-field"
                                  placeholder="Berikan alasan pembatalan pesanan..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                class="btn-secondary"
                                data-hs-overlay="#cancel-modal">
                            Batal
                        </button>
                        <button type="submit" class="btn-primary bg-red-600 hover:bg-red-700">
                            <i data-lucide="x" class="w-4 h-4 inline mr-2"></i>
                            Batalkan Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if($order->isPending() && $snapToken)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    // Initialize Snap payment
    window.snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            alert('Pembayaran berhasil!');
            location.reload();
        },
        onPending: function(result) {
            alert('Menunggu pembayaran...');
            location.reload();
        },
        onError: function(result) {
            alert('Pembayaran gagal: ' + result.status_message);
        },
        onClose: function() {
            alert('Anda menutup popup tanpa menyelesaikan pembayaran');
        }
    });

    // Open payment modal
    function payNow() {
        const modal = HSOverlay.getInstance('#payment-modal');
        modal.open();
    }
</script>
@endif

<script>
    // Show cancel modal
    function showCancelModal() {
        const modal = HSOverlay.getInstance('#cancel-modal');
        modal.open();
    }

    // Reorder functionality
    function reorder() {
        if (!confirm('Tambahkan semua item pesanan ini ke keranjang?')) return;

        fetch(`{{ route('orders.reorder', $order->id) }}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pesanan ulang');
        });
    }
</script>
@endpush
@endsection

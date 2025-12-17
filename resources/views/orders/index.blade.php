@extends('layouts.app')

@section('title', 'Pesanan Saya - MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Pesanan Saya</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Lihat dan kelola semua pesanan Anda
            </p>
        </div>

        <!-- Orders Filter -->
        <div class="card mb-6">
            <div class="p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Status Filter -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('orders.index') }}"
                       class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        Semua
                    </a>
                    @foreach($statuses as $key => $label)
                        <a href="{{ route('orders.index', ['status' => $key]) }}"
                           class="px-4 py-2 rounded-lg {{ request('status') == $key ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <!-- Search -->
                <form method="GET" action="{{ route('orders.index') }}" class="flex gap-2">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari kode pesanan..."
                           class="input-field">
                    <button type="submit" class="btn-primary px-4">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>

        @if($orders->count() > 0)
            <!-- Orders List -->
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="card hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <!-- Order Header -->
                            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
                                <div>
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $order->formatted_order_code }}
                                        </h3>
                                        <span class="badge badge-{{ $order->payment_status_badge }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                        <span class="badge badge-{{ $order->order_status_badge }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i>
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="text-2xl font-bold text-primary-600 dark:text-primary-400 mb-1">
                                        {{ $order->formatted_total }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $order->items->count() }} item
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items Preview -->
                            <div class="mb-4">
                                <div class="flex items-center space-x-4 overflow-x-auto pb-2">
                                    @foreach($order->items as $item)
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
                                    @endforeach
                                </div>
                            </div>

                            <!-- Order Actions -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <div class="flex items-center">
                                        <i data-lucide="truck" class="w-4 h-4 mr-2"></i>
                                        {{ $order->shipping_service ?? 'Belum ditentukan' }}
                                    </div>
                                </div>

                                <div class="flex space-x-2">
                                    <a href="{{ route('orders.show', $order->id) }}"
                                       class="btn-primary px-4 py-2">
                                        <i data-lucide="eye" class="w-4 h-4 inline mr-2"></i>
                                        Detail Pesanan
                                    </a>

                                    @if($order->canBeCancelled())
                                        <button type="button"
                                                onclick="showCancelModal({{ $order->id }})"
                                                class="btn-secondary px-4 py-2">
                                            <i data-lucide="x" class="w-4 h-4 inline mr-2"></i>
                                            Batalkan
                                        </button>
                                    @endif

                                    @if($order->isPaid())
                                        <button onclick="reorder({{ $order->id }})"
                                                class="btn-secondary px-4 py-2">
                                            <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                                            Pesan Lagi
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links('vendor.pagination.tailwind') }}
            </div>
        @else
            <!-- No Orders -->
            <div class="card text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                    <i data-lucide="package" class="w-12 h-12 text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Belum ada pesanan
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Anda belum membuat pesanan apapun. Mulai belanja untuk menemukan sparepart motor yang Anda butuhkan.
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
        @endif
    </div>
</div>

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

                <form id="cancel-form" method="POST">
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
<script>
    // Show cancel order modal
    function showCancelModal(orderId) {
        const form = document.getElementById('cancel-form');
        form.action = `/orders/${orderId}/cancel`;

        const modal = HSOverlay.getInstance('#cancel-modal');
        modal.open();
    }

    // Reorder functionality
    function reorder(orderId) {
        if (!confirm('Tambahkan semua item pesanan ini ke keranjang?')) return;

        fetch(`/orders/${orderId}/reorder`, {
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

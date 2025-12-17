@extends('layouts.app')

@section('title', 'Detail Pesanan - Admin MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Pesanan</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        {{ $order->order_code }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.orders.index') }}"
                       class="btn-secondary">
                        <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                        Kembali
                    </a>
                    <a href="{{ route('admin.orders.edit', $order->id) }}"
                       class="btn-primary">
                        <i data-lucide="edit" class="w-4 h-4 inline mr-2"></i>
                        Edit Pesanan
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600 dark:text-green-400 mr-3"></i>
                    <span class="text-green-800 dark:text-green-300">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Details -->
            <div class="lg:col-span-2">
                <!-- Order Status -->
                <div class="card mb-6">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    Status Pesanan
                                </h3>
                                <div class="flex items-center space-x-3">
                                    <span class="badge {{ $order->order_status == 'pending' ? 'badge-warning' : ($order->order_status == 'processing' ? 'badge-primary' : ($order->order_status == 'shipped' ? 'badge-info' : ($order->order_status == 'delivered' ? 'badge-success' : 'badge-danger'))) }} text-lg px-4 py-2">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                    <span class="badge {{ $order->payment_status == 'pending' ? 'badge-warning' : ($order->payment_status == 'paid' ? 'badge-success' : 'badge-danger') }} text-lg px-4 py-2">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Tanggal: {{ $order->created_at->format('d F Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            Item Pesanan
                        </h3>

                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            @if($item->product && $item->product->images)
                                                <img src="{{ asset('storage/' . json_decode($item->product->images, true)[0]) }}"
                                                     alt="{{ $item->product_name }}"
                                                     class="w-full h-full object-contain p-1">
                                            @else
                                                <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="ml-4 flex-grow">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">
                                            {{ $item->product_name }}
                                        </h4>
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mt-1">
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
                            Riwayat Status
                        </h3>

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
                                        'date' => $order->paid_at,
                                        'icon' => 'credit-card',
                                        'active' => $order->payment_status == 'paid',
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

            <!-- Order Summary & Actions -->
            <div class="space-y-6">
                <!-- Customer Info -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Informasi Pelanggan
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
                            @if($order->user)
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Akun</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $order->user->name }} ({{ $order->user->email }})
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
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

                <!-- Payment Summary -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
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
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Metode Pembayaran
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ $order->payment_method == 'bank_transfer' ? 'Transfer Bank' :
                                   $order->payment_method == 'credit_card' ? 'Kartu Kredit' : 'E-Wallet' }}
                            </p>

                            @if($order->midtrans_transaction_id)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">ID Transaksi Midtrans</p>
                                    <p class="text-sm font-mono text-gray-900 dark:text-white">
                                        {{ $order->midtrans_transaction_id }}
                                    </p>
                                </div>
                            @endif
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
                            <!-- Update Order Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Update Status Pesanan
                                </label>
                                <select id="order-status" class="input-field">
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $order->order_status == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button"
                                        onclick="updateOrderStatus()"
                                        class="btn-primary w-full mt-2">
                                    Update Status
                                </button>
                            </div>

                            <!-- Update Payment Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Update Status Pembayaran
                                </label>
                                <select id="payment-status" class="input-field">
                                    @foreach($paymentStatusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $order->payment_status == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button"
                                        onclick="updatePaymentStatus()"
                                        class="btn-primary w-full mt-2">
                                    Update Pembayaran
                                </button>
                            </div>

                            <!-- Invoice -->
                            <a href="{{ route('orders.invoice', $order->id) }}"
                               target="_blank"
                               class="btn-secondary w-full text-center block">
                                <i data-lucide="file-text" class="w-4 h-4 inline mr-2"></i>
                                Cetak Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    // Update order status
    function updateOrderStatus() {
        const status = document.getElementById('order-status').value;

        fetch(`/admin/orders/${ {{ $order->id }} }/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status pesanan berhasil diperbarui!');
                location.reload();
            } else {
                alert('Gagal memperbarui status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui status');
        });
    }

    // Update payment status
    function updatePaymentStatus() {
        const status = document.getElementById('payment-status').value;

        // In a real application, you would make an AJAX request
        // For now, redirect to edit page
        window.location.href = `/admin/orders/{{ $order->id }}/edit`;
    }
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Kelola Pesanan - Admin MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Pesanan</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Kelola semua pesanan pelanggan
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
            <div class="card">
                <div class="p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
            <div class="card">
                <div class="p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Menunggu Bayar</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending'] }}</p>
                </div>
            </div>
            <div class="card">
                <div class="p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Sudah Dibayar</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['paid'] }}</p>
                </div>
            </div>
            <div class="card">
                <div class="p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Diproses</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['processing'] }}</p>
                </div>
            </div>
            <div class="card">
                <div class="p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Dikirim</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['shipped'] }}</p>
                </div>
            </div>
            <div class="card">
                <div class="p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['delivered'] }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Cari Pesanan
                            </label>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Kode, nama, email, telepon..."
                                   class="input-field">
                        </div>

                        <!-- Order Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status Pesanan
                            </label>
                            <select name="order_status" class="input-field">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('order_status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="processing" {{ request('order_status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="shipped" {{ request('order_status') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                <option value="delivered" {{ request('order_status') == 'delivered' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status Pembayaran
                            </label>
                            <select name="payment_status" class="input-field">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                                <option value="expired" {{ request('payment_status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Dari
                                </label>
                                <input type="date"
                                       name="date_from"
                                       value="{{ request('date_from') }}"
                                       class="input-field">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Sampai
                                </label>
                                <input type="date"
                                       name="date_to"
                                       value="{{ request('date_to') }}"
                                       class="input-field">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <div class="flex space-x-3">
                            <button type="reset"
                                    onclick="window.location.href='{{ route('admin.orders.index') }}'"
                                    class="btn-secondary px-4 py-2">
                                Reset
                            </button>
                            <button type="submit" class="btn-primary px-4 py-2">
                                <i data-lucide="filter" class="w-4 h-4 inline mr-2"></i>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kode Pesanan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Pelanggan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $order->order_code }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->items->count() }} item
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $order->customer_name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->customer_email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <span class="badge {{ $order->order_status == 'pending' ? 'badge-warning' : ($order->order_status == 'processing' ? 'badge-primary' : ($order->order_status == 'shipped' ? 'badge-info' : ($order->order_status == 'delivered' ? 'badge-success' : 'badge-danger'))) }} text-xs">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                        <span class="badge {{ $order->payment_status == 'pending' ? 'badge-warning' : ($order->payment_status == 'paid' ? 'badge-success' : 'badge-danger') }} text-xs">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400"
                                           title="Detail">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order->id) }}"
                                           class="text-green-600 hover:text-green-900 dark:text-green-400"
                                           title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        @if($order->payment_status == 'pending')
                                            <button type="button"
                                                    onclick="confirmDelete({{ $order->id }})"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400"
                                                    title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <i data-lucide="package" class="w-12 h-12 mx-auto mb-4 text-gray-400"></i>
                                        <p>Tidak ada pesanan ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
    </main>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto">
        <div class="card">
            <div class="p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-8 h-8 text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Hapus Pesanan?
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Apakah Anda yakin ingin menghapus pesanan ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="delete-form" method="POST" class="space-y-3">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center space-x-3">
                        <button type="button"
                                class="btn-secondary"
                                onclick="HSOverlay.getInstance('#delete-modal').close()">
                            Batal
                        </button>
                        <button type="submit"
                                class="btn-primary bg-red-600 hover:bg-red-700">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Confirm delete
    function confirmDelete(orderId) {
        const form = document.getElementById('delete-form');
        form.action = `/admin/orders/${orderId}`;

        const modal = HSOverlay.getInstance('#delete-modal');
        modal.open();
    }
</script>
@endpush
@endsection

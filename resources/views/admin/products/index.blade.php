@extends('layouts.app')

@section('title', 'Kelola Produk - Admin MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Admin Header & Nav (sudah ada di layout) -->

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Produk</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Kelola semua produk sparepart motor
                </p>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.products.export') }}"
                   class="btn-secondary inline-flex items-center">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                    Export
                </a>
                <a href="{{ route('admin.products.create') }}"
                   class="btn-primary inline-flex items-center">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Tambah Produk
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.products.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Cari Produk
                            </label>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Nama, SKU, deskripsi..."
                                   class="input-field">
                        </div>

                        <!-- Brand Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Brand
                            </label>
                            <select name="brand" class="input-field">
                                <option value="">Semua Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->name }}" {{ request('brand') == $brand->name ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Kategori
                            </label>
                            <select name="category" class="input-field">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status
                            </label>
                            <select name="status" class="input-field">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <!-- Sorting -->
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Urutkan:</span>
                                <select name="sort" class="input-field text-sm py-1">
                                    <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama</option>
                                    <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Harga</option>
                                    <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }}>Stok</option>
                                </select>
                                <select name="order" class="input-field text-sm py-1">
                                    <option value="desc" {{ request('order', 'desc') == 'desc' ? 'selected' : '' }}>Desc</option>
                                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Asc</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <button type="reset"
                                    onclick="window.location.href='{{ route('admin.products.index') }}'"
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

        <!-- Bulk Actions -->
        <form id="bulk-action-form" method="POST" action="{{ route('admin.products.bulk-action') }}">
            @csrf
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <select id="bulk-action" name="action" class="input-field text-sm py-2">
                        <option value="">Pilih Aksi</option>
                        <option value="activate">Aktifkan</option>
                        <option value="deactivate">Nonaktifkan</option>
                        <option value="delete">Hapus</option>
                    </select>
                    <button type="button"
                            onclick="applyBulkAction()"
                            class="btn-secondary px-4 py-2">
                        Terapkan
                    </button>
                </div>

                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                </div>
            </div>

            <!-- Products Table -->
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left">
                                    <input type="checkbox"
                                           id="select-all"
                                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Produk
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Brand & Kategori
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Harga
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Stok
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox"
                                               name="ids[]"
                                               value="{{ $product->id }}"
                                               class="product-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($product->images && is_array(json_decode($product->images, true)))
                                                    <img class="h-10 w-10 rounded-lg object-cover"
                                                         src="{{ asset('storage/' . json_decode($product->images, true)[0]) }}"
                                                         alt="{{ $product->name }}">
                                                @else
                                                    <div class="h-10 w-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                                        <i data-lucide="package" class="w-5 h-5 text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $product->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    SKU: {{ $product->sku }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <span class="badge badge-primary text-xs">{{ $product->brand }}</span>
                                            <span class="badge badge-success text-xs">{{ $product->category }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                        </div>
                                        @if($product->hasDiscount())
                                            <div class="text-xs text-gray-500 line-through">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $product->stock }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-warning' }} text-xs">
                                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                            @if($product->is_featured)
                                                <span class="badge badge-primary text-xs">Featured</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('products.show', $product->id) }}"
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400"
                                               title="Lihat">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                               class="text-green-600 hover:text-green-900 dark:text-green-400"
                                               title="Edit">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            <button type="button"
                                                    onclick="confirmDelete({{ $product->id }})"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400"
                                                    title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-gray-500 dark:text-gray-400">
                                            <i data-lucide="package" class="w-12 h-12 mx-auto mb-4 text-gray-400"></i>
                                            <p>Tidak ada produk ditemukan</p>
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
                {{ $products->withQueryString()->links('vendor.pagination.tailwind') }}
            </div>
        </form>
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
                    Hapus Produk?
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.
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
    // Select all checkbox
    document.getElementById('select-all')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk action
    function applyBulkAction() {
        const action = document.getElementById('bulk-action').value;
        const checkedProducts = document.querySelectorAll('.product-checkbox:checked');

        if (!action) {
            alert('Pilih aksi terlebih dahulu');
            return;
        }

        if (checkedProducts.length === 0) {
            alert('Pilih minimal satu produk');
            return;
        }

        if (confirm(`Anda yakin ingin ${action} ${checkedProducts.length} produk?`)) {
            document.getElementById('bulk-action-form').submit();
        }
    }

    // Confirm delete
    function confirmDelete(productId) {
        const form = document.getElementById('delete-form');
        form.action = `/admin/products/${productId}`;

        const modal = HSOverlay.getInstance('#delete-modal');
        modal.open();
    }
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Tambah Produk - Admin MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Produk Baru</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Tambahkan produk sparepart motor baru ke katalog
            </p>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Basic Info -->
                <div class="lg:col-span-2">
                    <!-- Basic Information -->
                    <div class="card mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                Informasi Dasar
                            </h3>

                            <div class="space-y-6">
                                <!-- Product Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nama Produk *
                                    </label>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           required
                                           value="{{ old('name') }}"
                                           class="input-field"
                                           placeholder="Contoh: Pulley Custom 14 Derajat">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- SKU -->
                                <div>
                                    <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        SKU (Stock Keeping Unit) *
                                    </label>
                                    <input type="text"
                                           id="sku"
                                           name="sku"
                                           required
                                           value="{{ old('sku') }}"
                                           class="input-field"
                                           placeholder="Contoh: PUL-14-PRO">
                                    @error('sku')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Deskripsi *
                                    </label>
                                    <textarea id="description"
                                              name="description"
                                              rows="5"
                                              required
                                              class="input-field"
                                              placeholder="Deskripsi lengkap produk...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Specifications -->
                                <div>
                                    <label for="specifications" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Spesifikasi (Format: key: value)
                                    </label>
                                    <textarea id="specifications"
                                              name="specifications"
                                              rows="4"
                                              class="input-field"
                                              placeholder="Material: Aluminium 6061
Sudut: 14°
Diameter: 120mm
Berat: 850 gram">{{ old('specifications') }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Masukkan satu spesifikasi per baris dengan format "key: value"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Stock -->
                    <div class="card mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                Harga & Stok
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Regular Price -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Harga Normal *
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                        <input type="number"
                                               id="price"
                                               name="price"
                                               required
                                               min="0"
                                               step="100"
                                               value="{{ old('price') }}"
                                               class="input-field pl-10"
                                               placeholder="0">
                                    </div>
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Discount Price -->
                                <div>
                                    <label for="discount_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Harga Diskon
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                        <input type="number"
                                               id="discount_price"
                                               name="discount_price"
                                               min="0"
                                               step="100"
                                               value="{{ old('discount_price') }}"
                                               class="input-field pl-10"
                                               placeholder="0">
                                    </div>
                                    @error('discount_price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Stock -->
                                <div>
                                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Stok *
                                    </label>
                                    <input type="number"
                                           id="stock"
                                           name="stock"
                                           required
                                           min="0"
                                           value="{{ old('stock') }}"
                                           class="input-field"
                                           placeholder="0">
                                    @error('stock')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Categories & Images -->
                <div class="space-y-6">
                    <!-- Categories & Brand -->
                    <div class="card">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                Kategori & Brand
                            </h3>

                            <div class="space-y-6">
                                <!-- Brand -->
                                <div>
                                    <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Brand *
                                    </label>
                                    <select id="brand"
                                            name="brand"
                                            required
                                            class="input-field">
                                        <option value="">Pilih Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->name }}" {{ old('brand') == $brand->name ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                        <option value="other" {{ old('brand') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    <input type="text"
                                           id="other_brand"
                                           name="other_brand"
                                           value="{{ old('other_brand') }}"
                                           class="input-field mt-2 hidden"
                                           placeholder="Masukkan nama brand">
                                    @error('brand')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Kategori *
                                    </label>
                                    <select id="category"
                                            name="category"
                                            required
                                            class="input-field">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    <input type="text"
                                           id="other_category"
                                           name="other_category"
                                           value="{{ old('other_category') }}"
                                           class="input-field mt-2 hidden"
                                           placeholder="Masukkan nama kategori">
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Weight -->
                                <div>
                                    <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Berat (kg)
                                    </label>
                                    <input type="number"
                                           id="weight"
                                           name="weight"
                                           min="0"
                                           step="0.01"
                                           value="{{ old('weight') }}"
                                           class="input-field"
                                           placeholder="0.85">
                                    @error('weight')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Compatibility -->
                                <div>
                                    <label for="compatibility" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Kompatibilitas (pisahkan dengan koma)
                                    </label>
                                    <input type="text"
                                           id="compatibility"
                                           name="compatibility"
                                           value="{{ old('compatibility') }}"
                                           class="input-field"
                                           placeholder="Honda Beat, Yamaha Mio, Suzuki Nex">
                                    @error('compatibility')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="card">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                Gambar Produk
                            </h3>

                            <div id="image-upload-area" class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-8 text-center hover:border-primary-500 transition-colors">
                                <i data-lucide="upload" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">
                                    Seret dan lepas gambar di sini atau klik untuk memilih
                                </p>
                                <p class="text-sm text-gray-500 mb-4">
                                    Format: JPG, PNG, GIF (Maks. 2MB per gambar)
                                </p>
                                <input type="file"
                                       id="images"
                                       name="images[]"
                                       multiple
                                       accept="image/*"
                                       class="hidden">
                                <button type="button"
                                        onclick="document.getElementById('images').click()"
                                        class="btn-secondary">
                                    Pilih Gambar
                                </button>
                            </div>

                            <!-- Image Preview -->
                            <div id="image-preview" class="mt-4 grid grid-cols-4 gap-2 hidden">
                                <!-- Images will be previewed here -->
                            </div>

                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Status -->
                    <div class="card">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                Status Produk
                            </h3>

                            <div class="space-y-4">
                                <!-- Featured -->
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           id="is_featured"
                                           name="is_featured"
                                           value="1"
                                           {{ old('is_featured') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600">
                                    <label for="is_featured" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                        Tandai sebagai produk unggulan
                                    </label>
                                </div>

                                <!-- Active -->
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600">
                                    <label for="is_active" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                        Aktifkan produk
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="sticky bottom-6">
                        <div class="card">
                            <div class="p-6">
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('admin.products.index') }}"
                                       class="btn-secondary">
                                        <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                                        Kembali
                                    </a>
                                    <button type="submit" class="btn-primary">
                                        <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                                        Simpan Produk
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

@push('scripts')
<script>
    // Show other brand/category input
    document.getElementById('brand')?.addEventListener('change', function() {
        const otherInput = document.getElementById('other_brand');
        if (this.value === 'other') {
            otherInput.classList.remove('hidden');
            otherInput.required = true;
        } else {
            otherInput.classList.add('hidden');
            otherInput.required = false;
            otherInput.value = '';
        }
    });

    document.getElementById('category')?.addEventListener('change', function() {
        const otherInput = document.getElementById('other_category');
        if (this.value === 'other') {
            otherInput.classList.remove('hidden');
            otherInput.required = true;
        } else {
            otherInput.classList.add('hidden');
            otherInput.required = false;
            otherInput.value = '';
        }
    });

    // Image upload preview
    document.getElementById('images')?.addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        preview.classList.remove('hidden');

        Array.from(e.target.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}"
                         class="w-full h-24 object-cover rounded-lg">
                    <button type="button"
                            onclick="removeImage(${index})"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });

    // Drag and drop for images
    const uploadArea = document.getElementById('image-upload-area');
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/10');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/10');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/10');

        const files = e.dataTransfer.files;
        const input = document.getElementById('images');

        // Create a new DataTransfer object
        const dataTransfer = new DataTransfer();

        // Add existing files
        for (let i = 0; i < input.files.length; i++) {
            dataTransfer.items.add(input.files[i]);
        }

        // Add dropped files
        for (let i = 0; i < files.length; i++) {
            if (files[i].type.startsWith('image/')) {
                dataTransfer.items.add(files[i]);
            }
        }

        // Update the input files
        input.files = dataTransfer.files;

        // Trigger change event
        const event = new Event('change', { bubbles: true });
        input.dispatchEvent(event);
    });

    // Remove image from preview
    function removeImage(index) {
        const input = document.getElementById('images');
        const dataTransfer = new DataTransfer();

        // Add all files except the removed one
        for (let i = 0; i < input.files.length; i++) {
            if (i !== index) {
                dataTransfer.items.add(input.files[i]);
            }
        }

        // Update the input files
        input.files = dataTransfer.files;

        // Update preview
        const event = new Event('change', { bubbles: true });
        input.dispatchEvent(event);
    }
</script>
@endpush
@endsection

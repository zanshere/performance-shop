@extends('layouts.app')

@section('title', $package->name . ' - MotorSpareParts')

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
                        <a href="{{ route('packages.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white md:ml-2">
                            Paket Bore Up
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $package->name }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Package Details -->
            <div class="lg:col-span-2">
                <!-- Package Header -->
                <div class="card mb-6">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                            <div>
                                <div class="flex items-center flex-wrap gap-2 mb-4">
                                    <span class="badge badge-primary text-lg px-4 py-2">
                                        {{ $package->category_label }}
                                    </span>
                                    <span class="badge badge-success text-lg px-4 py-2">
                                        {{ $package->difficulty_label }}
                                    </span>
                                    @if($package->is_featured)
                                        <span class="badge badge-warning text-lg px-4 py-2">
                                            <i data-lucide="star" class="w-4 h-4 inline mr-1"></i>
                                            Paket Populer
                                        </span>
                                    @endif
                                </div>

                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                                    {{ $package->name }}
                                </h1>

                                <!-- Stats -->
                                <div class="flex items-center space-x-6 text-gray-600 dark:text-gray-400 mb-6">
                                    <div class="flex items-center">
                                        <i data-lucide="users" class="w-5 h-5 mr-2"></i>
                                        <span>{{ $package->order_count }} pesanan</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i data-lucide="eye" class="w-5 h-5 mr-2"></i>
                                        <span>{{ $package->view_count }} dilihat</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i data-lucide="clock" class="w-5 h-5 mr-2"></i>
                                        <span>{{ $package->duration_days }} hari pengerjaan</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="text-right">
                                @if($package->hasDiscount())
                                    <div class="mb-2">
                                        <span class="text-sm text-gray-500 line-through">
                                            Rp {{ number_format($package->price, 0, ',', '.') }}
                                        </span>
                                        <span class="ml-2 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            Hemat {{ $package->discount_percentage }}%
                                        </span>
                                    </div>
                                    <div class="text-4xl font-bold text-primary-600 dark:text-primary-400">
                                        Rp {{ number_format($package->discount_price, 0, ',', '.') }}
                                    </div>
                                @else
                                    <div class="text-4xl font-bold text-primary-600 dark:text-primary-400">
                                        Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="card mb-6">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                            <i data-lucide="file-text" class="w-5 h-5 inline mr-2"></i>
                            Deskripsi Paket
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                {{ $package->description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="card mb-6">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                            <i data-lucide="zap" class="w-5 h-5 inline mr-2"></i>
                            Fitur Utama
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($package->features as $feature)
                                <div class="flex items-start">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0"></i>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Included Items -->
                <div class="card mb-6">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                            <i data-lucide="package" class="w-5 h-5 inline mr-2"></i>
                            Yang Termasuk
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($package->included_items as $item)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <i data-lucide="check" class="w-5 h-5 text-primary-600 dark:text-primary-400 mr-3"></i>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Compatibility -->
                @if($package->compatible_bikes)
                    <div class="card mb-6">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                <i data-lucide="bike" class="w-5 h-5 inline mr-2"></i>
                                Motor yang Kompatibel
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($package->compatible_bikes as $bike)
                                    <span class="badge badge-primary px-4 py-2">{{ $bike }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Package -->
                <div class="card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            Pesan Paket Ini
                        </h3>

                        <!-- Power Gain -->
                        @if($package->power_gain_percentage)
                            <div class="mb-6 p-4 bg-gradient-to-r from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-900/10 rounded-lg">
                                <div class="text-center">
                                    <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">
                                        +{{ $package->power_gain_percentage }}%
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Peningkatan Daya Maksimal
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- CTA Buttons -->
                        <div class="space-y-3">
                            <button onclick="openInquiryModal()"
                                    class="btn-primary w-full py-3 text-lg">
                                <i data-lucide="message-circle" class="w-5 h-5 inline mr-2"></i>
                                Konsultasi & Pesan
                            </button>

                            <a href="{{ route('contact') }}"
                               class="btn-secondary w-full py-3 text-lg text-center block">
                                <i data-lucide="phone" class="w-5 h-5 inline mr-2"></i>
                                Hubungi Kami
                            </a>
                        </div>

                        <!-- Additional Info -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-y-3 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-start">
                                <i data-lucide="shield-check" class="w-4 h-4 mt-0.5 mr-2 text-green-600"></i>
                                <span>Garansi pengerjaan 6 bulan</span>
                            </div>
                            <div class="flex items-start">
                                <i data-lucide="users" class="w-4 h-4 mt-0.5 mr-2 text-blue-600"></i>
                                <span>Dikerjakan oleh mekanik bersertifikat</span>
                            </div>
                            <div class="flex items-start">
                                <i data-lucide="clock" class="w-4 h-4 mt-0.5 mr-2 text-orange-600"></i>
                                <span>Estimasi pengerjaan {{ $package->duration_days }} hari kerja</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommended Products -->
                @if($recommendedProducts->count() > 0)
                    <div class="card">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                <i data-lucide="package" class="w-5 h-5 inline mr-2"></i>
                                Produk Terkait
                            </h3>
                            <div class="space-y-4">
                                @foreach($recommendedProducts as $product)
                                    <a href="{{ route('products.show', $product->id) }}"
                                       class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-500 dark:hover:border-primary-500 transition-colors group">
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                                            @if($product->images && is_array(json_decode($product->images, true)))
                                                <img src="{{ asset('storage/products/' . json_decode($product->images, true)[0]) }}"
                                                     alt="{{ $product->name }}"
                                                     class="w-full h-full object-contain p-1">
                                            @else
                                                <i data-lucide="package" class="w-6 h-6 text-gray-400 group-hover:text-primary-500"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow">
                                            <h4 class="font-medium text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 text-sm">
                                                {{ Str::limit($product->name, 30) }}
                                            </h4>
                                            <p class="text-sm text-primary-600 dark:text-primary-400 font-semibold">
                                                Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Related Packages -->
                @if($relatedPackages->count() > 0)
                    <div class="card">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                                <i data-lucide="layers" class="w-5 h-5 inline mr-2"></i>
                                Paket Lainnya
                            </h3>
                            <div class="space-y-4">
                                @foreach($relatedPackages as $related)
                                    <a href="{{ route('packages.show', $related->slug) }}"
                                       class="block p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="font-medium text-gray-900 dark:text-white text-sm">
                                                {{ $related->name }}
                                            </span>
                                            <span class="badge badge-primary text-xs">
                                                {{ $related->category_label }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-primary-600 dark:text-primary-400 font-semibold">
                                                Rp {{ number_format($related->final_price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $related->duration_days }} hari
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Inquiry Modal -->
<div id="inquiry-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="card">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Konsultasi Paket {{ $package->name }}
                    </h3>
                    <button type="button" class="hs-dropdown-toggle p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                            data-hs-overlay="#inquiry-modal">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form id="inquiry-form" method="POST" action="{{ route('packages.inquiry', $package->id) }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="inquiry_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nama Lengkap *
                            </label>
                            <input type="text"
                                   id="inquiry_name"
                                   name="name"
                                   required
                                   class="input-field"
                                   placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label for="inquiry_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Email *
                            </label>
                            <input type="email"
                                   id="inquiry_email"
                                   name="email"
                                   required
                                   class="input-field"
                                   placeholder="email@contoh.com">
                        </div>

                        <div>
                            <label for="inquiry_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nomor Telepon *
                            </label>
                            <input type="tel"
                                   id="inquiry_phone"
                                   name="phone"
                                   required
                                   class="input-field"
                                   placeholder="0812-3456-7890">
                        </div>

                        <div>
                            <label for="inquiry_bike" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Model Motor *
                            </label>
                            <input type="text"
                                   id="inquiry_bike"
                                   name="bike_model"
                                   required
                                   class="input-field"
                                   placeholder="Contoh: Honda Vario 150">
                        </div>

                        <div>
                            <label for="inquiry_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Pesan / Pertanyaan *
                            </label>
                            <textarea id="inquiry_message"
                                      name="message"
                                      rows="4"
                                      required
                                      class="input-field"
                                      placeholder="Tulis pesan atau pertanyaan Anda mengenai paket ini..."></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button"
                                class="btn-secondary"
                                data-hs-overlay="#inquiry-modal">
                            Batal
                        </button>
                        <button type="submit" class="btn-primary">
                            <i data-lucide="send" class="w-4 h-4 inline mr-2"></i>
                            Kirim Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Open inquiry modal
    function openInquiryModal() {
        const modal = HSOverlay.getInstance('#inquiry-modal');
        modal.open();
    }

    // Form submission
    document.getElementById('inquiry-form')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 inline mr-2 animate-spin"></i>Mengirim...';

        // Submit form via AJAX
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Permintaan konsultasi berhasil dikirim!');

                // Close modal
                const modal = HSOverlay.getInstance('#inquiry-modal');
                modal.close();

                // Reset form
                form.reset();
            } else {
                alert('Terjadi kesalahan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim permintaan');
        })
        .finally(() => {
            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
</script>
@endpush
@endsection

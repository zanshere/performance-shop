@extends('layouts.app')

@section('title', 'Kontak Kami - MotorSpareParts')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Hubungi <span class="text-primary-600 dark:text-primary-400">Kami</span>
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                Butuh bantuan atau ingin berkonsultasi tentang modifikasi motor?
                Tim ahli kami siap membantu Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div>
                <div class="card mb-8">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                            Informasi Kontak
                        </h2>

                        <div class="space-y-6">
                            <!-- Phone -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                        <i data-lucide="phone" class="w-6 h-6 text-primary-600 dark:text-primary-400"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Telepon</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $contactInfo['phone'] }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">
                                        {{ $contactInfo['working_hours'] }}
                                    </p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                        <i data-lucide="mail" class="w-6 h-6 text-primary-600 dark:text-primary-400"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Email</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $contactInfo['email'] }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">
                                        Balasan dalam 1x24 jam
                                    </p>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                        <i data-lucide="map-pin" class="w-6 h-6 text-primary-600 dark:text-primary-400"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Alamat Workshop</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $contactInfo['address'] }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">
                                        Kunjungi workshop kami untuk konsultasi langsung
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp Button -->
                        <div class="mt-8">
                            <a href="https://wa.me/{{ str_replace('+', '', $contactInfo['whatsapp']) }}"
                               target="_blank"
                               class="inline-flex items-center justify-center w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                <i data-lucide="message-circle" class="w-5 h-5 mr-3"></i>
                                Chat via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="card">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                            Pertanyaan yang Sering Ditanyakan
                        </h2>

                        <div class="space-y-4">
                            @foreach($faqs as $faq)
                                <div class="hs-accordion" id="faq-{{ $loop->index }}">
                                    <button type="button"
                                            class="hs-accordion-toggle w-full flex justify-between items-center text-left font-medium text-gray-900 dark:text-white py-3 px-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <span>{{ $faq['question'] }}</span>
                                        <i data-lucide="chevron-down" class="hs-accordion-active:rotate-180 w-5 h-5 transition-transform"></i>
                                    </button>
                                    <div class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                                         id="faq-content-{{ $loop->index }}">
                                        <div class="p-4 text-gray-600 dark:text-gray-400">
                                            {{ $faq['answer'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <div class="card">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                            Kirim Pesan
                        </h2>

                        @if(session('success'))
                            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                <div class="flex items-center">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600 dark:text-green-400 mr-3"></i>
                                    <span class="text-green-800 dark:text-green-300">{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <div class="flex items-center">
                                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 dark:text-red-400 mr-3"></i>
                                    <span class="text-red-800 dark:text-red-300">{{ session('error') }}</span>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf

                            <div class="space-y-4">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nama Lengkap *
                                    </label>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           required
                                           class="input-field"
                                           placeholder="Masukkan nama lengkap"
                                           value="{{ old('name') }}">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Email *
                                    </label>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           required
                                           class="input-field"
                                           placeholder="email@contoh.com"
                                           value="{{ old('email') }}">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nomor Telepon *
                                    </label>
                                    <input type="tel"
                                           id="phone"
                                           name="phone"
                                           required
                                           class="input-field"
                                           placeholder="0812-3456-7890"
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Subject -->
                                <div>
                                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Subjek *
                                    </label>
                                    <select id="subject"
                                            name="subject"
                                            required
                                            class="input-field">
                                        <option value="" disabled selected>Pilih subjek</option>
                                        <option value="Konsultasi Paket Bore Up" {{ old('subject') == 'Konsultasi Paket Bore Up' ? 'selected' : '' }}>
                                            Konsultasi Paket Bore Up
                                        </option>
                                        <option value="Pertanyaan Produk" {{ old('subject') == 'Pertanyaan Produk' ? 'selected' : '' }}>
                                            Pertanyaan Produk
                                        </option>
                                        <option value="Teknis & Instalasi" {{ old('subject') == 'Teknis & Instalasi' ? 'selected' : '' }}>
                                            Teknis & Instalasi
                                        </option>
                                        <option value="Garansi & Service" {{ old('subject') == 'Garansi & Service' ? 'selected' : '' }}>
                                            Garansi & Service
                                        </option>
                                        <option value="Lainnya" {{ old('subject') == 'Lainnya' ? 'selected' : '' }}>
                                            Lainnya
                                        </option>
                                    </select>
                                    @error('subject')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Message -->
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Pesan *
                                    </label>
                                    <textarea id="message"
                                              name="message"
                                              rows="6"
                                              required
                                              class="input-field"
                                              placeholder="Tulis pesan Anda di sini...">{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-8">
                                <button type="submit"
                                        class="btn-primary w-full py-3 text-lg">
                                    <i data-lucide="send" class="w-5 h-5 inline mr-2"></i>
                                    Kirim Pesan
                                </button>
                            </div>

                            <!-- Privacy Note -->
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                                Dengan mengirim pesan, Anda menyetujui
                                <a href="#" class="text-primary-600 hover:text-primary-700 dark:text-primary-400">
                                    Kebijakan Privasi
                                </a> kami.
                            </p>
                        </form>
                    </div>
                </div>

                <!-- Map -->
                <div class="card mt-8">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i data-lucide="map-pin" class="w-5 h-5 inline mr-2"></i>
                            Lokasi Workshop
                        </h3>
                        <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <!-- Replace with actual Google Maps embed -->
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="text-center">
                                    <i data-lucide="map" class="w-16 h-16 text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 dark:text-gray-400">Peta lokasi workshop</p>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                            {{ $contactInfo['address'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize accordions
    document.addEventListener('DOMContentLoaded', function() {
        const accordions = document.querySelectorAll('.hs-accordion');
        accordions.forEach(accordion => {
            new HSAccordion(accordion);
        });
    });
</script>
@endpush
@endsection

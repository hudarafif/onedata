@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
            <li>
                <a href="{{ route('dashboard.index') }}" class="hover:text-blue-600 transition">Dashboard</a>
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 01-1.414 1.414L7.293 14.707z" clip-rule="evenodd"/>
                </svg>
                <a href="{{ route('organization.company.index') }}" class="hover:text-blue-600 transition">Data Perusahaan</a>
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 01-1.414 1.414L7.293 14.707z" clip-rule="evenodd"/>
                </svg>
                <span class="text-gray-900 dark:text-white">Detail Perusahaan</span>
            </li>
        </ol>
    </nav>

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ $company->name }}
            </h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">
                Informasi detail perusahaan dan struktur divisi terkait.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('organization.company.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow hover:bg-gray-50 transition dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
               Kembali
            </a>
            <!-- <a href="{{ route('organization.company.edit', $company->id) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Perusahaan
            </a> -->
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 italic">Informasi Umum</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Perusahaan</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ $company->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Holding Naungan</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">
                        @if($company->holding)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                {{ $company->holding->name }}
                            </span>
                        @else
                            <span class="text-gray-400 italic text-sm">Tanpa Holding</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Terdaftar Sejak</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $company->created_at->format('d F Y') }}</p>
                </div>
            </div>
        </div>

        {{-- ===== CARD STRUKTUR ORGANISASI ===== --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
             x-data="strukturOrganisasi()">

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-3 bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 sm:px-6 sm:py-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/20 sm:h-9 sm:w-9 sm:rounded-xl">
                        <svg class="h-4 w-4 text-white sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="truncate text-sm font-semibold text-white sm:text-base">Struktur Organisasi</h3>
                        <p class="hidden text-xs text-indigo-100 sm:block">Bagan/diagram struktur perusahaan</p>
                    </div>
                </div>

                {{-- Tombol Ganti (hanya jika sudah ada gambar) --}}
                @if($company->struktur_image)
                    <button type="button" @click="openUpload = true"
                            class="shrink-0 inline-flex items-center gap-1.5 rounded-lg bg-white/15 px-2.5 py-1.5 text-xs font-medium text-white transition hover:bg-white/25">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span class="hidden xs:inline">Ganti</span> Gambar
                    </button>
                @endif
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mx-4 mt-4 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300 sm:mx-6">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="mx-4 mt-4 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300 sm:mx-6">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $errors->first('struktur_image') }}</span>
                </div>
            @endif

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- *** SUDAH ADA GAMBAR *** --}}
                @if($company->struktur_image)
                    {{-- Layout 2 kolom: gambar kiri, info+aksi kanan (di md ke atas) --}}
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:gap-6">

                        {{-- Preview thumbnail --}}
                        <div class="group relative w-full cursor-zoom-in overflow-hidden rounded-xl border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900 md:flex-1"
                             @click="openLightbox = true">
                            <img src="{{ str_starts_with($company->struktur_image, 'uploads/') ? asset($company->struktur_image) : asset('storage/' . $company->struktur_image) }}"
                                 alt="Struktur Organisasi {{ $company->name }}"
                                 class="h-48 w-full object-contain transition duration-300 group-hover:scale-[1.02] sm:h-64 md:h-72">
                            <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition duration-300 group-hover:bg-black/20">
                                <span class="scale-0 rounded-full bg-white/90 p-2.5 shadow-lg transition duration-300 group-hover:scale-100 sm:p-3">
                                    <svg class="h-5 w-5 text-gray-800 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        {{-- Info & Aksi (samping kanan di desktop, bawah di mobile) --}}
                        <div class="flex flex-col gap-3 md:w-48 md:shrink-0 lg:w-56">
                            <div class="rounded-lg bg-indigo-50 p-3 dark:bg-indigo-900/20">
                                <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">Petunjuk</p>
                                <ul class="mt-1.5 space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                    <li class="flex items-center gap-1.5">
                                        <svg class="h-3 w-3 shrink-0 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Klik gambar untuk memperbesar
                                    </li>
                                    <li class="flex items-center gap-1.5">
                                        <svg class="h-3 w-3 shrink-0 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Tekan Esc untuk menutup tampilan
                                    </li>
                                </ul>
                            </div>

                            <div class="flex flex-row gap-2 md:flex-col">
                                <button type="button" @click="openUpload = true"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100 dark:border-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400 dark:hover:bg-indigo-900/30 sm:text-sm">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Ganti Gambar
                                </button>
                                <button type="button" @click="confirmDelete = true"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-medium text-red-600 transition hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30 sm:text-sm">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus Gambar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Lightbox modal --}}
                    <div x-show="openLightbox" x-cloak
                         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/85 p-3 sm:p-6"
                         @keydown.escape.window="openLightbox = false"
                         @click.self="openLightbox = false">
                        <div class="relative max-h-[90vh] max-w-[95vw] sm:max-w-[90vw]">
                            <img src="{{ str_starts_with($company->struktur_image, 'uploads/') ? asset($company->struktur_image) : asset('storage/' . $company->struktur_image) }}"
                                 alt="Struktur Organisasi {{ $company->name }}"
                                 class="max-h-[85vh] max-w-[95vw] rounded-xl object-contain shadow-2xl sm:max-w-[90vw]">
                            <button @click="openLightbox = false"
                                    class="absolute -right-2 -top-2 flex h-7 w-7 items-center justify-center rounded-full bg-white shadow-lg transition hover:bg-gray-100 sm:-right-3 sm:-top-3 sm:h-8 sm:w-8">
                                <svg class="h-3.5 w-3.5 text-gray-700 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Hapus Modal --}}
                    <div x-show="confirmDelete" x-cloak
                         class="fixed inset-0 z-[9998] flex items-end justify-center bg-black/50 p-0 sm:items-center sm:p-4"
                         @keydown.escape.window="confirmDelete = false">
                        <div class="w-full rounded-t-2xl bg-white p-5 shadow-xl dark:bg-gray-800 sm:max-w-md sm:rounded-2xl sm:p-6">
                            <div class="mb-4 flex items-center gap-3 sm:gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:h-12 sm:w-12">
                                    <svg class="h-5 w-5 text-red-600 dark:text-red-400 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white sm:text-base">Hapus Gambar Struktur?</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 sm:text-sm">Gambar yang dihapus tidak dapat dikembalikan.</p>
                                </div>
                            </div>
                            <div class="flex gap-2 sm:gap-3">
                                <button @click="confirmDelete = false"
                                        class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    Batal
                                </button>
                                <form method="POST" action="{{ route('organization.company.delete-struktur', $company) }}" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                                        Ya, Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                {{-- *** BELUM ADA GAMBAR *** --}}
                @else
                    <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 px-4 py-10 text-center dark:border-gray-700 dark:bg-gray-900/30 sm:py-14"
                         :class="isDragging ? 'border-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : ''"
                         @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="handleDrop($event)">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 sm:h-16 sm:w-16">
                            <svg class="h-7 w-7 text-indigo-500 sm:h-8 sm:w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                            </svg>
                        </div>
                        <p class="mb-1 text-sm font-semibold text-gray-700 dark:text-gray-300 sm:text-base">
                            Belum ada gambar struktur organisasi
                        </p>
                        <p class="mb-5 max-w-xs text-xs text-gray-400 dark:text-gray-500 sm:text-sm">
                            Drag &amp; drop file ke sini, atau klik tombol di bawah untuk memilih gambar
                        </p>
                        <button type="button" @click="openUpload = true"
                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-500/30 transition hover:opacity-90 active:scale-95">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Upload Gambar Struktur
                        </button>
                        <p class="mt-3 text-xs text-gray-400 dark:text-gray-500">JPG, PNG, GIF, WEBP, SVG • Maks 5 MB</p>
                    </div>
                @endif

                {{-- ===== MODAL UPLOAD ===== --}}
                <div x-show="openUpload" x-cloak
                     class="fixed inset-0 z-[9998] flex items-end justify-center bg-black/50 sm:items-center sm:p-4"
                     @keydown.escape.window="openUpload = false">
                    <div class="w-full overflow-hidden rounded-t-2xl bg-white shadow-2xl dark:bg-gray-800 sm:max-w-lg sm:rounded-2xl"
                         @click.outside="openUpload = false">
                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 sm:px-6 sm:py-4">
                            <h3 class="text-sm font-semibold text-white sm:text-base">Upload Gambar Struktur Organisasi</h3>
                            <button @click="openUpload = false" class="rounded-lg p-1 text-white/80 transition hover:bg-white/20 hover:text-white">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <form method="POST"
                              action="{{ route('organization.company.upload-struktur', $company) }}"
                              enctype="multipart/form-data"
                              class="max-h-[80vh] overflow-y-auto p-4 sm:max-h-none sm:p-6">
                            @csrf
                            <div class="space-y-4">
                                {{-- Drop area / preview --}}
                                <div class="flex min-h-[160px] cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 p-4 transition dark:border-gray-700 dark:bg-gray-900/30 sm:min-h-[200px] sm:p-6"
                                     :class="previewUrl ? 'border-indigo-400' : 'hover:border-indigo-300 hover:bg-indigo-50/50'"
                                     @click="$refs.fileInput.click()"
                                     @dragover.prevent
                                     @drop.prevent="handleDropModal($event)">
                                    <template x-if="previewUrl">
                                        <img :src="previewUrl" class="max-h-40 w-auto rounded-lg object-contain sm:max-h-52">
                                    </template>
                                    <template x-if="!previewUrl">
                                        <div class="flex flex-col items-center text-center">
                                            <svg class="mb-3 h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                                            </svg>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Klik atau drag &amp; drop gambar</p>
                                            <p class="mt-1 text-xs text-gray-400">JPG, PNG, GIF, WEBP, SVG • Maks 5 MB</p>
                                        </div>
                                    </template>
                                </div>

                                <input type="file" name="struktur_image" accept="image/*"
                                       class="hidden" x-ref="fileInput"
                                       @change="handleFileChange($event)">

                                {{-- File name --}}
                                <div x-show="fileName" class="flex items-center gap-2 rounded-lg bg-indigo-50 px-3 py-2 text-sm text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    <span x-text="fileName" class="truncate text-xs sm:text-sm"></span>
                                </div>

                                {{-- Buttons --}}
                                <div class="flex gap-2 sm:gap-3">
                                    <button type="button" @click="openUpload = false"
                                            class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            :disabled="!previewUrl"
                                            :class="previewUrl ? 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:opacity-90 cursor-pointer' : 'cursor-not-allowed opacity-50 bg-gray-400'"
                                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition active:scale-95">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        Upload Sekarang
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- ===== END CARD STRUKTUR ORGANISASI ===== --}}





        <div x-data="companyDetails()" class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <!-- Tabs Header -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex gap-6 px-6" aria-label="Tabs">
                    <button 
                        @click="activeTab = 'divisions'"
                        :class="activeTab === 'divisions' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Divisi
                        <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300" x-text="data.divisions.length"></span>
                    </button>
                    <button 
                        @click="activeTab = 'departments'"
                        :class="activeTab === 'departments' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Departemen
                        <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300" x-text="data.departments.length"></span>
                    </button>
                    <button 
                        @click="activeTab = 'units'"
                        :class="activeTab === 'units' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Unit
                        <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300" x-text="data.units.length"></span>
                    </button>
                    <button 
                        @click="activeTab = 'subsidiaries'"
                        :class="activeTab === 'subsidiaries' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Anak Perusahaan
                        <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300" x-text="data.subsidiaries.length"></span>
                    </button>
                </nav>
            </div>

            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="tabTitle"></h2>

                <div class="flex items-center gap-3">
                    <div class="relative">
                        <button class="absolute text-gray-500 -translate-y-1/2 left-4 top-1/2">
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37363C3.04199 5.87693 5.87735 3.04199 9.37533 3.04199C12.8733 3.04199 15.7087 5.87693 15.7087 9.37363C15.7087 12.8703 12.8733 15.7053 9.37533 15.7053C5.87735 15.7053 3.04199 12.8703 3.04199 9.37363ZM9.37533 1.54199C5.04926 1.54199 1.54199 5.04817 1.54199 9.37363C1.54199 13.6991 5.04926 17.2053 9.37533 17.2053C11.2676 17.2053 13.0032 16.5344 14.3572 15.4176L17.1773 18.238C17.4702 18.5309 17.945 18.5309 18.2379 18.238C18.5308 17.9451 18.5309 17.4703 18.238 17.1773L15.4182 14.3573C16.5367 13.0033 17.2087 11.2669 17.2087 9.37363C17.2087 5.04817 13.7014 1.54199 9.37533 1.54199Z"/></svg>
                        </button>
                        <input
                            x-model="search"
                            type="text"
                            :placeholder="'Cari ' + tabLabel + '...'"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 xl:w-[300px]"
                        />
                    </div>

                    <!-- Dynamic Action Button -->
                    <template x-if="activeTab === 'divisions'">
                         <a href="{{ route('organization.division.create', ['company_id' => $company->id]) }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 transition">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                             Tambah Divisi
                         </a>
                    </template>
                     <template x-if="activeTab === 'departments'">
                         <a href="{{ route('organization.department.create', ['company_id' => $company->id]) }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 transition">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                             Tambah Departemen
                         </a>
                    </template>
                     <template x-if="activeTab === 'units'">
                         <a href="{{ route('organization.unit.create', ['company_id' => $company->id]) }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 transition">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                             Tambah Unit
                         </a>
                    </template>
                     <template x-if="activeTab === 'subsidiaries'">
                         <a href="{{ route('organization.subsidiary.create', ['parent_id' => $company->id]) }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 transition">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                             Tambah Anak Perusahaan
                         </a>
                    </template>
                </div>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 text-left">
                            <th class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400 w-16">No</th>
                            <th @click="sortBy('name')" class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400 cursor-pointer hover:text-blue-600 transition group">
                                <div class="flex items-center gap-1">
                                    <span x-text="'Nama ' + tabLabel"></span>
                                    <svg class="w-3 h-3 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5"/></svg>
                                </div>
                            </th>
                            <th class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="(item, index) in filteredItems" :key="item.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition">
                                <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="index + 1"></td>
                                <td class="px-5 py-4 text-sm font-medium text-gray-900 dark:text-white" x-text="item.name"></td>
                                <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400">
                                        Aktif
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <a :href="getItemUrl(item.id)" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium inline-flex items-center bg-blue-50 dark:bg-blue-900/20 p-2 rounded-lg mr-1 transition">
                                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </td>
                            </tr>
                        </template>

                        <template x-if="filteredItems.length === 0">
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                         <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                         <span x-text="'Tidak ada data ' + tabLabel + ' ditemukan.'"></span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="'Menampilkan ' + filteredItems.length + ' data'"></p>
            </div>
        </div>
    </div>
</div>

<script>
function companyDetails() {
    return {
        activeTab: 'divisions',
        search: '',
        sortCol: 'name',
        sortDir: 'asc',
        data: {
            divisions: @json($company->divisions ?? []),
            departments: @json($company->departments ?? []),
            units: @json($company->units ?? []),
            subsidiaries: @json($company->children ?? []),
        },

        get tabLabel() {
            switch(this.activeTab) {
                case 'divisions': return 'Divisi';
                case 'departments': return 'Departemen';
                case 'units': return 'Unit';
                case 'subsidiaries': return 'Anak Perusahaan';
                default: return '';
            }
        },

        get tabTitle() {
            return 'Daftar ' + this.tabLabel;
        },

        get currentData() {
             return this.data[this.activeTab] || [];
        },

        get filteredItems() {
            let items = this.currentData;
            if (this.search) {
                const q = this.search.toLowerCase();
                items = items.filter(item => 
                    item.name.toLowerCase().includes(q)
                );
            }
            return items.sort((a, b) => {
                let aVal = a[this.sortCol], bVal = b[this.sortCol];
                if (typeof aVal === 'string') aVal = aVal.toLowerCase();
                if (aVal < bVal) return this.sortDir === 'asc' ? -1 : 1;
                if (aVal > bVal) return this.sortDir === 'asc' ? 1 : -1;
                return 0;
            });
        },

        sortBy(column) {
            if (this.sortCol === column) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortCol = column;
                this.sortDir = 'asc';
            }
        },

        getItemUrl(id) {
            switch(this.activeTab) {
                case 'divisions': return '{{ url("organization/division") }}/' + id;
                case 'departments': return '{{ url("organization/department") }}/' + id;
                case 'units': return '{{ url("organization/unit") }}/' + id;
                case 'subsidiaries': return '{{ url("organization/subsidiary") }}/' + id;
                default: return '#';
            }
        }
    }
}

function strukturOrganisasi() {
    return {
        openLightbox: false,
        confirmDelete: false,
        openUpload: false,
        isDragging: false,
        previewUrl: null,
        fileName: null,

        handleFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                this.fileName = file.name;
                this.previewUrl = URL.createObjectURL(file);
            }
        },

        handleDrop(e) {
            this.isDragging = false;
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                this.openUpload = true;
                this.$nextTick(() => {
                    // Set the file on the hidden input manually via DataTransfer
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    this.$refs.fileInput.files = dt.files;
                    this.fileName = file.name;
                    this.previewUrl = URL.createObjectURL(file);
                });
            }
        },

        handleDropModal(e) {
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const dt = new DataTransfer();
                dt.items.add(file);
                this.$refs.fileInput.files = dt.files;
                this.fileName = file.name;
                this.previewUrl = URL.createObjectURL(file);
            }
        },
    };
}
</script>
@endsection

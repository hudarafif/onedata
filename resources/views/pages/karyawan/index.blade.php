@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    <!-- HEADER -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Data Karyawan
            </h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">
                Kelola seluruh data karyawan perusahaan
            </p>
        </div>

        <div class="flex gap-2">
            <button onclick="openImportModal()"
               class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-green-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Import
            </button>
            <a href="{{ route('karyawan.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Karyawan
            </a>
        </div>

    </div>

    <!-- SUCCESS ALERT -->
    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-900 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <!-- USER CREDENTIALS MODAL -->
    @if(session('user_created') && session('user_credentials'))
        <div id="credentialsModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div onclick="closeCredentialsModal()" class="absolute inset-0 bg-black/50 dark:bg-black/80"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full mx-4 p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/20 mb-4">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">User Berhasil Dibuat!</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Akun user telah otomatis dibuat untuk karyawan baru</p>
                </div>

                <div class="space-y-4 mb-6 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">NAMA</label>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1" id="credName">{{ session('user_credentials')['name'] ?? '' }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">EMAIL</label>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white flex-1 break-all" id="credEmail">{{ session('user_credentials')['email'] ?? '' }}</p>
                            <button type="button" onclick="copyToClipboard('credEmail')" class="text-blue-600 hover:text-blue-700 dark:text-blue-400" title="Copy">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">PASSWORD SEMENTARA</label>
                        <div class="flex items-center gap-2 mt-1">
                            <input type="password" id="credPassword" value="{{ session('user_credentials')['password'] ?? '' }}" readonly class="text-sm font-medium bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded px-2 py-1 flex-1" />
                            <button type="button" onclick="togglePasswordVisibility()" class="text-gray-600 hover:text-gray-700 dark:text-gray-400" title="Toggle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button type="button" onclick="copyToClipboard('credPassword')" class="text-blue-600 hover:text-blue-700 dark:text-blue-400" title="Copy">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">ROLE</label>
                        <div class="flex gap-2 mt-1 flex-wrap">
                            @foreach(session('user_credentials')['roles'] ?? [] as $role)
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/20 dark:text-blue-400">
                                    {{ ucfirst($role) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 mb-6">
                    <p class="text-xs text-blue-800 dark:text-blue-400">
                        <strong>Catatan:</strong> Pastikan user segera mengubah password saat login pertama kali. Password sementara ini hanya berlaku sekali.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeCredentialsModal()" class="flex-1 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition">
                        Tutup
                    </button>
                    <button type="button" onclick="copyAllCredentials()" class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition">
                        Salin Semua
                    </button>
                </div>
            </div>
        </div>

        <script>
        function closeCredentialsModal() {
            document.getElementById('credentialsModal').style.display = 'none';
        }

        function togglePasswordVisibility() {
            const input = document.getElementById('credPassword');
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent || element.value;

            navigator.clipboard.writeText(text).then(() => {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                }, 2000);
            });
        }

        function copyAllCredentials() {
            const name = document.getElementById('credName').textContent;
            const email = document.getElementById('credEmail').textContent;
            const password = document.getElementById('credPassword').value;

            const text = `
Nama: ${name}
Email: ${email}
Password: ${password}
            `.trim();

            navigator.clipboard.writeText(text).then(() => {
                alert('Semua kredensial berhasil disalin!');
            });
        }

        // Auto-close modal after 30 seconds
        setTimeout(() => {
            closeCredentialsModal();
        }, 30000);
        </script>
    @endif

    <!-- ERROR ALERT -->
    @if(session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-900 dark:bg-red-900/20 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif


    <!-- FILTER FORM -->
    @php
        $activeFilters = collect([
            'search'          => request('search'),
            'company_id'      => request('company_id'),
            'division_id'     => request('division_id'),
            'level_id'        => request('level_id'),
            'lokasi_kerja'    => request('lokasi_kerja'),
            'status_karyawan' => request('status_karyawan'),
        ])->filter()->count();
    @endphp

    <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800" id="filter-card">
        {{-- Header --}}
        <div class="flex items-center justify-between bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-white">Filter & Pencarian</h3>
                    <p class="text-xs text-blue-100">Saring data karyawan sesuai kebutuhan</p>
                </div>
                @if($activeFilters > 0)
                    <span class="ml-2 inline-flex items-center gap-1 rounded-full bg-white/25 px-2.5 py-0.5 text-xs font-semibold text-white ring-1 ring-white/40">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $activeFilters }} aktif
                    </span>
                @endif
            </div>
            <button type="button" onclick="toggleFilter()"
                    class="flex items-center gap-1.5 rounded-lg bg-white/15 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-white/25"
                    id="filter-toggle-btn">
                <svg class="h-4 w-4 transition-transform duration-300" id="filter-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
                <span id="filter-toggle-label">Sembunyikan</span>
            </button>
        </div>

        {{-- Form Body --}}
        <div id="filter-body" class="p-6">
            <form method="GET" action="{{ route('karyawan.index') }}" id="filter-form">

                {{-- Row 1: Search + Perusahaan + Divisi --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                    {{-- Search --}}
                    <div class="group">
                        <label for="search" class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Pencarian
                        </label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Nama, NIK, atau telepon…"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-4 pr-10 text-sm text-gray-900 transition placeholder:text-gray-400
                                          focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                          dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:bg-gray-700
                                          {{ request('search') ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/20' : '' }}">
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Perusahaan --}}
                    <div class="group">
                        <label for="company_id" class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Perusahaan
                        </label>
                        <div class="relative">
                            <select name="company_id" id="company_id"
                                    class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-4 pr-9 text-sm text-gray-900 transition
                                           focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                           dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:bg-gray-700
                                           {{ request('company_id') ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/20 font-medium text-blue-700 dark:text-blue-300' : '' }}">
                                <option value="">Semua Perusahaan</option>
                                @foreach($filterOptions['companies'] as $company)
                                    <option value="{{ $company['id'] }}" {{ request('company_id') == $company['id'] ? 'selected' : '' }}>
                                        {{ $company['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Divisi --}}
                    <div class="group">
                        <label for="division_id" class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Divisi
                        </label>
                        <div class="relative">
                            <select name="division_id" id="division_id"
                                    class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-4 pr-9 text-sm text-gray-900 transition
                                           focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                           dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:bg-gray-700
                                           {{ request('division_id') ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/20 font-medium text-blue-700 dark:text-blue-300' : '' }}">
                                <option value="">Semua Divisi</option>
                                @foreach($filterOptions['divisions'] as $div)
                                    <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>
                                        {{ $div->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Level + Lokasi + Status --}}
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">

                    {{-- Level --}}
                    <div class="group">
                        <label for="level_id" class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Level Jabatan
                        </label>
                        <div class="relative">
                            <select name="level_id" id="level_id"
                                    class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-4 pr-9 text-sm text-gray-900 transition
                                           focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                           dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:bg-gray-700
                                           {{ request('level_id') ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/20 font-medium text-blue-700 dark:text-blue-300' : '' }}">
                                <option value="">Semua Level</option>
                                @foreach($filterOptions['levels'] as $lvl)
                                    <option value="{{ $lvl->id }}" {{ request('level_id') == $lvl->id ? 'selected' : '' }}>
                                        {{ $lvl->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Lokasi --}}
                    <div class="group">
                        <label for="lokasi_kerja" class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Lokasi Kerja
                        </label>
                        <div class="relative">
                            <select name="lokasi_kerja" id="lokasi_kerja"
                                    class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-4 pr-9 text-sm text-gray-900 transition
                                           focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                           dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:bg-gray-700
                                           {{ request('lokasi_kerja') ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/20 font-medium text-blue-700 dark:text-blue-300' : '' }}">
                                <option value="">Semua Lokasi</option>
                                @foreach($filterOptions['lokasi_kerja'] as $lokasi)
                                    <option value="{{ $lokasi }}" {{ request('lokasi_kerja') == $lokasi ? 'selected' : '' }}>
                                        {{ $lokasi }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="group">
                        <label for="status_karyawan" class="mb-1.5 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status Karyawan
                        </label>
                        <div class="relative">
                            <select name="status_karyawan" id="status_karyawan"
                                    class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-4 pr-9 text-sm text-gray-900 transition
                                           focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                           dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:bg-gray-700
                                           {{ request('status_karyawan') ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/20 font-medium text-blue-700 dark:text-blue-300' : '' }}">
                                <option value="">Semua Status</option>
                                @foreach($filterOptions['statuses'] as $status)
                                    @if($status)
                                        <option value="{{ $status }}" {{ request('status_karyawan') == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-5 dark:border-gray-700">
                    {{-- Active filter pills --}}
                    <div class="flex flex-wrap items-center gap-2" id="active-filter-pills">
                        @if(request('search'))
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                "{{ Str::limit(request('search'), 20) }}"
                            </span>
                        @endif
                        @if(request('company_id'))
                            @php $selectedCompany = collect($filterOptions['companies'])->firstWhere('id', request('company_id')); @endphp
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                                {{ $selectedCompany['name'] ?? 'Perusahaan' }}
                            </span>
                        @endif
                        @if(request('division_id'))
                            @php $selectedDiv = $filterOptions['divisions']->firstWhere('id', request('division_id')); @endphp
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $selectedDiv->name ?? 'Divisi' }}
                            </span>
                        @endif
                        @if(request('level_id'))
                            @php $selectedLvl = $filterOptions['levels']->firstWhere('id', request('level_id')); @endphp
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-cyan-100 px-3 py-1 text-xs font-medium text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-300">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                {{ $selectedLvl->name ?? 'Level' }}
                            </span>
                        @endif
                        @if(request('lokasi_kerja'))
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ request('lokasi_kerja') }}
                            </span>
                        @endif
                        @if(request('status_karyawan'))
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ request('status_karyawan') }}
                            </span>
                        @endif
                        @if($activeFilters === 0)
                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">Belum ada filter aktif</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 ml-auto">
                        @if($activeFilters > 0)
                            <a href="{{ route('karyawan.index') }}"
                               class="inline-flex items-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-600 shadow-sm transition hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Hapus Filter
                            </a>
                        @else
                            <a href="{{ route('karyawan.index') }}"
                               class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-600 shadow-sm transition hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </a>
                        @endif
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-500/30 transition hover:from-blue-700 hover:to-indigo-700 hover:shadow-blue-500/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLE -->
    <form
        id="batch-delete-form"
        action="{{ route('karyawan.batchDelete') }}"
        method="POST"
    >
        @csrf

        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <!-- TOP BAR -->
            <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Menampilkan {{ $karyawans->firstItem() ?? 0 }} sampai {{ $karyawans->lastItem() ?? 0 }} dari {{ $karyawans->total() }} data</span>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Show</span>
                    <div class="relative z-20">
                        <select x-model.number="perPage" @change="resetPage"
                            class="h-11 w-20 appearance-none rounded-lg border
                                border-gray-300 bg-transparent px-4 py-2.5 pr-8
                                text-sm text-gray-800 outline-none
                                focus:border-blue-600 focus:ring-1 focus:ring-blue-600
                                dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                            <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20">
                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293
                                        a1 1 0 111.414 1.414l-4 4a1 1 0
                                        01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </span>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">entries</span>
                </div>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        id="batch-delete-btn"
                        onclick="batchDelete()"
                        class="hidden inline-flex items-center gap-2 rounded-lg bg-red-600 px-5 py-2.5 text-white font-medium hover:bg-red-700 transition shadow-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Terpilih
                    </button>

                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" @click.away="open = false" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 hover:bg-gray-50 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 border border-gray-200 dark:border-gray-700" style="display: none;">
                            <a href="{{ route('karyawan.export', ['type' => 'csv']) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Export CSV</a>
                            <a href="{{ route('karyawan.export', ['type' => 'excel']) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Export Excel</a>
                            <a href="{{ route('karyawan.export', ['type' => 'pdf']) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Export PDF</a>
                        </div>
                    </div>
                </div>
            </div>

        <!-- TABLE -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full min-w-full">
                <thead>
                    <tr class="border-y border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                        <th class="px-4 py-3 text-center w-10">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">NIK</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Nomor Telepon</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Level Jabatan</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Lokasi Kerja</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Divisi</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Perusahaan</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-600 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($karyawans as $karyawan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition">
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" value="{{ $karyawan->id_karyawan }}" name="selected_karyawan[]" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0 w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm font-semibold text-gray-700 dark:text-white">
                                        {{ substr($karyawan->Nama_Sesuai_KTP ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $karyawan->Nama_Sesuai_KTP }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $karyawan->Nomor_Telepon_Aktif_Karyawan ?? 'no phone' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $karyawan->NIK ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $karyawan->Nomor_Telepon_Aktif_Karyawan ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                @if ($karyawan->pekerjaan->first() && $karyawan->pekerjaan->first()->level)
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/20 dark:text-blue-400">
                                        {{ $karyawan->pekerjaan->first()->level->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $karyawan->pekerjaan->first()->Lokasi_Kerja ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $karyawan->pekerjaan->first()->division->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ $karyawan->pekerjaan->first()?->company->name ?? $karyawan->pekerjaan->first()?->holding->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('karyawan.show', $karyawan->id_karyawan) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-50 p-2 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 transition" title="Lihat Detail">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('karyawan.edit', $karyawan->id_karyawan) }}" class="inline-flex items-center justify-center rounded-lg bg-yellow-50 p-2 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:hover:bg-yellow-900/40 transition" title="Edit">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button type="button"
                                            onclick="deleteKaryawan('{{ $karyawan->id_karyawan }}', '{{ addslashes($karyawan->Nama_Sesuai_KTP) }}')"
                                            class="inline-flex items-center justify-center rounded-lg bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 transition" title="Hapus">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-gray-500">
                                Data tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="flex items-center justify-between px-6 py-4">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Menampilkan {{ $karyawans->firstItem() ?? 0 }} sampai {{ $karyawans->lastItem() ?? 0 }} dari {{ $karyawans->total() }} data
            </div>

            <div class="flex items-center gap-2">
                @if($karyawans->hasPages())
                    {{ $karyawans->links('vendor.pagination.tailwind') }}
                @endif
            </div>
        </div>
    </div>
    </form>
</div>

<script>
function toggleFilter() {
    const body    = document.getElementById('filter-body');
    const chevron = document.getElementById('filter-chevron');
    const label   = document.getElementById('filter-toggle-label');

    const isHidden = body.style.display === 'none';
    if (isHidden) {
        body.style.display = '';
        chevron.style.transform = 'rotate(0deg)';
        label.textContent = 'Sembunyikan';
    } else {
        body.style.display = 'none';
        chevron.style.transform = 'rotate(-90deg)';
        label.textContent = 'Tampilkan';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('input[name="selected_karyawan[]"]');
    const deleteBtn = document.getElementById('batch-delete-btn');

    function updateButton() {
        const checked = document.querySelectorAll('input[name="selected_karyawan[]"]:checked');

        if (checked.length > 0) {
            deleteBtn.classList.remove('hidden');
            deleteBtn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus (${checked.length})
            `;
        } else {
            deleteBtn.classList.add('hidden');
        }

        // Update select all state
        selectAll.checked = checked.length === checkboxes.length && checkboxes.length > 0;
        selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
    }

    // Add event listeners to individual checkboxes
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateButton);
    });

    // Add event listener to select all checkbox
    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateButton();
    });

    // Initialize button state
    updateButton();

    // Global batch delete function - using fetch API
    window.batchDelete = function () {
        const checked = document.querySelectorAll('input[name="selected_karyawan[]"]:checked');

        if (checked.length === 0) {
            alert('Pilih data yang ingin dihapus terlebih dahulu');
            return;
        }

        if (!confirm(`Yakin ingin menghapus ${checked.length} karyawan terpilih?`)) {
            return;
        }

        // Get selected IDs
        const selectedIds = Array.from(checked).map(cb => cb.value);
        
        // Create form data
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        selectedIds.forEach(id => {
            formData.append('selected_karyawan[]', id);
        });

        // Show loading state
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = `
            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menghapus...
        `;

        // Send request
        fetch('{{ route("karyawan.batchDelete") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
            deleteBtn.disabled = false;
            updateButton();
        });
    };

    // Individual delete function - creates a temporary form outside the batch form
    window.deleteKaryawan = function(id, name) {
        if (!confirm(`Yakin ingin menghapus karyawan "${name}"?`)) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('karyawan') }}/${id}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    };
});
</script>
@include('pages.karyawan.components.import-modal')
@endsection


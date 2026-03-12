@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl p-4 md:p-6 2xl:p-10">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit FPK</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Perbarui data formulir permintaan karyawan <strong class="text-blue-600 dark:text-blue-400">{{ $fpk->nomor_fpk }}</strong></p>
        </div>
        <a href="{{ route('rekrutmen.fpk.index') }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.05] transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Error -->
    @if ($errors->any())
    <div class="mb-6 flex rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-900 dark:bg-red-900/20 dark:text-red-400">
        <div class="mr-4 flex items-center">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="font-medium">Mohon perbaiki kesalahan berikut:</h3>
            <ul class="mt-2 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('rekrutmen.fpk.update', $fpk->id) }}" method="POST" id="fpkForm">
        @csrf
        @method('PUT')

        <!-- ===== SEKSI A ===== -->
        <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white text-sm font-bold flex-shrink-0">A</div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Posisi yang Dibutuhkan</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Lengkapi data jabatan dan lokasi kerja</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Divisi</label>
                        <select name="division_id" class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">-- Pilih Divisi --</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}" {{ old('division_id', $fpk->division_id) == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Departemen</label>
                        <select name="department_id" class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $fpk->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Nama Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_jabatan" required value="{{ old('nama_jabatan', $fpk->nama_jabatan) }}" placeholder="Cth: Staff Akuntansi"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Lokasi Kerja <span class="text-red-500">*</span></label>
                        <input type="text" name="lokasi_kerja" required value="{{ old('lokasi_kerja', $fpk->lokasi_kerja) }}" placeholder="Cth: Head Office Jakarta"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Tanggal Mulai Bekerja <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" name="tanggal_mulai_bekerja" required value="{{ old('tanggal_mulai_bekerja', $fpk->tanggal_mulai_bekerja) }}"
                                   onclick="this.showPicker()"
                                   class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 cursor-pointer dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                            <span class="absolute top-1/2 right-3.5 -translate-y-1/2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Jumlah Kebutuhan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="jumlah_kebutuhan" required min="1" value="{{ old('jumlah_kebutuhan', $fpk->jumlah_kebutuhan) }}"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                            <span class="absolute right-3 top-3 text-xs text-gray-400">Orang</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Grade <span class="text-xs font-normal text-gray-400">(Diisi Finance)</span></label>
                        <input type="text" name="grade" value="{{ old('grade', $fpk->grade) }}" placeholder="Diisi oleh Finance"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Level Jabatan <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Staf', 'Ka. Regu', 'Ka. Unit', 'Manager', 'General Manager'] as $lvl)
                        <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 py-2.5 px-4 text-sm text-gray-700 dark:text-gray-300 transition hover:border-blue-400 hover:bg-blue-50 dark:border-gray-700 dark:hover:border-blue-600 dark:hover:bg-blue-900/20 select-none">
                            <input type="radio" name="level" value="{{ $lvl }}" {{ old('level', $fpk->level) == $lvl ? 'checked' : '' }} required class="text-blue-600 focus:ring-blue-500" />
                            {{ $lvl }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SEKSI B ===== -->
        <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-yellow-500 text-white text-sm font-bold flex-shrink-0">B</div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">Keterangan Permintaan Karyawan</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Alasan & dampak kekosongan posisi</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Alasan Permintaan <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['Penggantian Karyawan', 'Penambahan Karyawan Baru', 'Jangka Waktu Kontrak'] as $alasan)
                        <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 py-2.5 px-4 text-sm text-gray-700 dark:text-gray-300 transition hover:border-yellow-400 hover:bg-yellow-50 dark:border-gray-700 dark:hover:border-yellow-600 dark:hover:bg-yellow-900/20 select-none">
                            <span class="inline-block h-4 w-4 flex-shrink-0 rounded-full border-2 border-gray-300 dark:border-gray-600 alasan-dot"></span>
                            <input type="hidden" />
                            {{ $alasan }}
                            <input type="checkbox" data-alasan="{{ $alasan }}" value="{{ $alasan }}" {{ old('alasan_permintaan', $fpk->alasan_permintaan) == $alasan ? 'checked' : '' }} class="sr-only" onchange="handleAlasan(this)" />
                        </label>
                        @endforeach
                    </div>
                    <input type="hidden" name="alasan_permintaan" id="alasan_permintaan_hidden" value="{{ old('alasan_permintaan', $fpk->alasan_permintaan) }}" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Nama Karyawan <span class="text-xs font-normal text-gray-400">(jika penggantian)</span></label>
                        <input type="text" name="nama_karyawan_pengganti" value="{{ old('nama_karyawan_pengganti', $fpk->nama_karyawan_pengganti) }}" placeholder="Nama karyawan yang digantikan"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Lama Kontrak <span class="text-xs font-normal text-gray-400">(jika kontrak)</span></label>
                        <input type="text" name="jangka_waktu_kontrak" value="{{ old('jangka_waktu_kontrak', $fpk->jangka_waktu_kontrak) }}" placeholder="Cth: 6 Bulan / 1 Tahun"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Dampak Kekosongan Posisi</label>
                    <textarea name="dampak_kekurangan_posisi" rows="3" placeholder="Uraikan dampak jika posisi ini tidak segera diisi..."
                              class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 resize-none">{{ old('dampak_kekurangan_posisi', $fpk->dampak_kekurangan_posisi) }}</textarea>
                </div>
            </div>
        </div>

        <!-- ===== SEKSI C ===== -->
        <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-600 text-white text-sm font-bold flex-shrink-0">C</div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">Rekrutmen & Deskripsi Jabatan</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Sumber rekrutmen dan uraian tugas jabatan</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Sumber Rekrutmen <span class="text-red-500">*</span></label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Internal', 'Eksternal', 'Outsource'] as $src)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 py-2.5 px-4 text-sm text-gray-700 dark:text-gray-300 transition hover:border-green-400 hover:bg-green-50 dark:border-gray-700 dark:hover:border-green-600 dark:hover:bg-green-900/20 select-none">
                                <input type="radio" name="sumber_rekrutmen" value="{{ $src }}" {{ old('sumber_rekrutmen', $fpk->sumber_rekrutmen) == $src ? 'checked' : '' }} required class="text-green-600 focus:ring-green-500" />
                                {{ $src }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Catatan Khusus</label>
                        <input type="text" name="catatan_khusus" value="{{ old('catatan_khusus', $fpk->catatan_khusus) }}" placeholder="Catatan tambahan..."
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Deskripsi Jabatan</label>
                    <textarea name="deskripsi_jabatan" rows="3" placeholder="Uraian singkat tentang jabatan ini..."
                              class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 resize-none">{{ old('deskripsi_jabatan', $fpk->deskripsi_jabatan) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Tanggung Jawab Jabatan <span class="text-xs font-normal text-gray-400">(Maks. 5 poin)</span></label>
                    @php $tjList = old('tanggungjawab_jabatan', $fpk->tanggungjawab_jabatan ?? []); @endphp
                    @for($i = 0; $i < 5; $i++)
                    <div class="flex gap-3 items-center mb-2">
                        <span class="text-sm text-gray-400 w-5 text-right">{{ $i+1 }}.</span>
                        <input type="text" name="tanggungjawab_jabatan[]" value="{{ $tjList[$i] ?? '' }}" placeholder="Tanggung jawab ke-{{ $i+1 }}"
                               class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- ===== SEKSI D ===== -->
        <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-600 text-white text-sm font-bold flex-shrink-0">D</div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">Tugas & Tolok Ukur Keberhasilan</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Daftar tugas dan indikator kinerja (maks. 10 poin)</p>
                </div>
            </div>
            <div class="p-6">
                @php
                    $tugasList = old('tugas', $fpk->tugas ?? []);
                    $tolakList = old('tolak_ukur_keberhasilan', $fpk->tolak_ukur_keberhasilan ?? []);
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white mb-3 pb-2 border-b border-gray-200 dark:border-gray-700">Tugas</p>
                        @for($i = 0; $i < 10; $i++)
                        <div class="flex gap-3 items-center mb-2">
                            <span class="text-sm text-gray-400 w-5 text-right">{{ $i+1 }}.</span>
                            <input type="text" name="tugas[]" value="{{ $tugasList[$i] ?? '' }}" placeholder="Tugas ke-{{ $i+1 }}"
                                   class="h-9 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                        </div>
                        @endfor
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white mb-3 pb-2 border-b border-gray-200 dark:border-gray-700">Tolok Ukur Keberhasilan</p>
                        @for($i = 0; $i < 10; $i++)
                        <div class="flex gap-3 items-center mb-2">
                            <span class="text-sm text-gray-400 w-5 text-right">{{ $i+1 }}.</span>
                            <input type="text" name="tolak_ukur_keberhasilan[]" value="{{ $tolakList[$i] ?? '' }}" placeholder="Indikator ke-{{ $i+1 }}"
                                   class="h-9 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SEKSI E ===== -->
        <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-orange-500 text-white text-sm font-bold flex-shrink-0">E</div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">Kualifikasi Tenaga Kerja</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Persyaratan kandidat yang dibutuhkan</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Jenis Kelamin</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Laki-laki', 'Perempuan', 'Bebas'] as $jk)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 py-2 px-3 text-sm text-gray-700 dark:text-gray-300 transition hover:border-blue-400 hover:bg-blue-50 dark:border-gray-700 dark:hover:border-blue-600 dark:hover:bg-blue-900/20 select-none">
                                <input type="radio" name="kualifikasi_jk" value="{{ $jk }}" {{ old('kualifikasi_jk', $fpk->kualifikasi_jk ?? 'Bebas') == $jk ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500" />
                                {{ $jk }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Usia</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['18-25 Tahun', '26-30 Tahun', '31-40 Tahun', '>41 Tahun'] as $usia)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 py-2 px-3 text-sm text-gray-700 dark:text-gray-300 transition hover:border-blue-400 hover:bg-blue-50 dark:border-gray-700 dark:hover:border-blue-600 dark:hover:bg-blue-900/20 select-none">
                                <input type="radio" name="kualifikasi_usia" value="{{ $usia }}" {{ old('kualifikasi_usia', $fpk->kualifikasi_usia) == $usia ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500" />
                                {{ $usia }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Pendidikan</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['SMA/K sederajat', 'Diploma', 'S1', 'S2/S3'] as $pend)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 py-2 px-3 text-sm text-gray-700 dark:text-gray-300 transition hover:border-blue-400 hover:bg-blue-50 dark:border-gray-700 dark:hover:border-blue-600 dark:hover:bg-blue-900/20 select-none">
                                <input type="radio" name="kualifikasi_pendidikan" value="{{ $pend }}" {{ old('kualifikasi_pendidikan', $fpk->kualifikasi_pendidikan) == $pend ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500" />
                                {{ $pend }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Pengalaman Kerja</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Freshgraduate', '1-3 Tahun', '>3-7 Tahun', '>7-10 Tahun', '> 10 Tahun'] as $exp)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 py-2 px-3 text-sm text-gray-700 dark:text-gray-300 transition hover:border-blue-400 hover:bg-blue-50 dark:border-gray-700 dark:hover:border-blue-600 dark:hover:bg-blue-900/20 select-none">
                                <input type="radio" name="kualifikasi_pengalaman" value="{{ $exp }}" {{ old('kualifikasi_pengalaman', $fpk->kualifikasi_pengalaman) == $exp ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500" />
                                {{ $exp }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Jurusan</label>
                        <input type="text" name="kualifikasi_jurusan" value="{{ old('kualifikasi_jurusan', $fpk->kualifikasi_jurusan) }}" placeholder="Cth: Teknik Informatika, Akuntansi, semua jurusan"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    </div>
                </div>

                @php
                    $hardList = old('hard_competency', $fpk->hard_competency ?? []);
                    $softList = old('soft_competency', $fpk->soft_competency ?? []);
                    $testList = old('test_dibutuhkan', $fpk->test_dibutuhkan ?? []);
                    $saranaList = old('sarana_prasarana', $fpk->sarana_prasarana ?? []);
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Hard Competency <span class="text-xs font-normal text-gray-400">(Maks. 5)</span></label>
                        @for($i = 0; $i < 5; $i++)
                        <div class="flex gap-2 items-center mb-2">
                            <span class="text-sm text-gray-400 w-4">{{ $i+1 }}.</span>
                            <input type="text" name="hard_competency[]" value="{{ $hardList[$i] ?? '' }}" placeholder="Kompetensi teknis ke-{{ $i+1 }}"
                                   class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                        </div>
                        @endfor
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Soft Competency <span class="text-xs font-normal text-gray-400">(Maks. 5)</span></label>
                        @for($i = 0; $i < 5; $i++)
                        <div class="flex gap-2 items-center mb-2">
                            <span class="text-sm text-gray-400 w-4">{{ $i+1 }}.</span>
                            <input type="text" name="soft_competency[]" value="{{ $softList[$i] ?? '' }}" placeholder="Soft skill ke-{{ $i+1 }}"
                                   class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                        </div>
                        @endfor
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Test yang Dibutuhkan</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Psikotes', 'TPA', 'Keahlian', 'Fisik', 'FGD'] as $test)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 py-2 px-3 text-sm text-gray-700 dark:text-gray-300 transition hover:border-blue-400 hover:bg-blue-50 dark:border-gray-700 dark:hover:border-blue-600 dark:hover:bg-blue-900/20 select-none">
                                <input type="checkbox" name="test_dibutuhkan[]" value="{{ $test }}" {{ in_array($test, $testList) ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500 rounded" />
                                {{ $test }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Sarana Prasarana</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Meja & Kursi', 'Laptop', 'Motor', 'Mobil', 'Tempat Tinggal'] as $sarana)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 py-2 px-3 text-sm text-gray-700 dark:text-gray-300 transition hover:border-blue-400 hover:bg-blue-50 dark:border-gray-700 dark:hover:border-blue-600 dark:hover:bg-blue-900/20 select-none">
                                <input type="checkbox" name="sarana_prasarana[]" value="{{ $sarana }}" {{ in_array($sarana, $saranaList) ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500 rounded" />
                                {{ $sarana }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('rekrutmen.fpk.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.05] transition">
                Batal
            </a>
            <button type="submit" name="action" value="draft"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.05] transition">
                Simpan Draft
            </button>
            <button type="submit" name="action" value="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-8 py-3 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Ajukan FPK ke HR
            </button>
        </div>
    </form>
</div>

<script>
function handleAlasan(el) {
    document.querySelectorAll('[data-alasan]').forEach(function(cb) {
        if (cb !== el) cb.checked = false;
        const label = cb.closest('label');
        const dot = label.querySelector('.alasan-dot');
        if (cb.checked) {
            label.classList.add('border-yellow-400', 'bg-yellow-50', 'dark:border-yellow-600', 'dark:bg-yellow-900/20');
            label.classList.remove('border-gray-200', 'dark:border-gray-700');
            if (dot) { dot.classList.add('bg-yellow-500', 'border-yellow-500'); dot.classList.remove('border-gray-300', 'dark:border-gray-600'); }
        } else {
            label.classList.remove('border-yellow-400', 'bg-yellow-50', 'dark:border-yellow-600', 'dark:bg-yellow-900/20');
            label.classList.add('border-gray-200', 'dark:border-gray-700');
            if (dot) { dot.classList.remove('bg-yellow-500', 'border-yellow-500'); dot.classList.add('border-gray-300', 'dark:border-gray-600'); }
        }
    });
    document.getElementById('alasan_permintaan_hidden').value = el.checked ? el.value : '';
}

// Init on load
document.querySelectorAll('[data-alasan]:checked').forEach(function(cb) {
    handleAlasan(cb);
});
</script>
@endsection

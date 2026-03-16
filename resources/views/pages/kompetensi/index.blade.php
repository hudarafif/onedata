@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Monitoring Kompetensi (LMS)" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 p-5 sm:p-6 shadow-theme-sm">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white/90 flex items-center gap-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl text-white text-base"
                      style="background:#f79009;">
                    <i class="fas fa-trophy"></i>
                </span>
                Top 10 Skor Tertinggi
            </h3>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <form action="{{ route('kompetensi.monitoring') }}" method="GET"
                      class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3">

                    {{-- Period selector --}}
                    <div class="hidden sm:flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                        <i class="far fa-calendar-alt text-gray-400 text-sm"></i>
                        <select name="period"
                                class="bg-transparent border-none p-0 text-theme-sm text-gray-700 dark:text-gray-300 focus:ring-0 outline-none cursor-pointer">
                            <option value="6_bulan">6 Bulan</option>
                            <option value="1_tahun">1 Tahun</option>
                        </select>
                    </div>

                    {{-- Search --}}
                    <div class="relative w-full sm:w-auto">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari Pegawai..."
                               class="h-10 w-full rounded-lg border border-gray-200 bg-white py-2 pl-9 pr-3 text-theme-sm text-gray-800 placeholder-gray-400
                                      focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10
                                      dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder-gray-500
                                      xl:w-[200px]" />
                    </div>

                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 transition dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.05]">
                        Terapkan
                    </button>
                </form>

                <div class="flex items-center gap-2 w-full sm:w-auto sm:border-l sm:border-gray-200 sm:dark:border-gray-700 sm:pl-3">
                    {{-- Export CSV --}}
                    <a href="{{ route('kompetensi.export', request()->all()) }}"
                       class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-theme-sm font-medium text-white transition shadow-theme-xs"
                       style="background:#059669;"
                       onmouseover="this.style.background='#047857';"
                       onmouseout="this.style.background='#059669';">
                        <i class="fas fa-file-csv"></i> Ekspor CSV
                    </a>

                    {{-- Sync --}}
                    <button type="button" id="btn-sync"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-theme-sm font-medium text-white hover:bg-brand-600 transition shadow-theme-xs">
                        <i class="fas fa-sync-alt" id="icon-sync"></i>
                        <span id="text-sync">Tarik Data Wadja</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-theme-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
                        <th class="px-5 py-3 text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Peringkat</th>
                        <th class="px-5 py-3 text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Nama</th>
                        <th class="px-5 py-3 text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">NIP</th>
                        <th class="px-5 py-3 text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Divisi</th>
                        <th class="px-5 py-3 text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Kompetensi Tersedia</th>
                        <th class="px-5 py-3 text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Kompetensi Diselesaikan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($kompetensiList as $index => $karyawan)
                    @php
                        $rank     = $kompetensiList->firstItem() + $index;
                        $available  = $karyawan->pegawaiKompetensi->where('status', 'available')->pluck('nama_kompetensi')->unique()->values();
                        $completed  = $karyawan->pegawaiKompetensi->where('status', 'completed')->pluck('nama_kompetensi')->unique()->values();
                        $job        = $karyawan->pekerjaanTerkini()->first() ?? $karyawan->pekerjaan()->first();
                        $kelas      = $job->department->name ?? '-';

                        // Rank styling
                        $rankBg = match($rank) {
                            1 => '#F59E0B',
                            2 => '#a7b6cbff',
                            3 => '#F97316',
                            default => '#D1D5DB'
                        };
                        $rankTextColor = $rank <= 3 ? '#fff' : '#374151';
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors duration-100"
                        x-data="{ openAvail: false, openDone: false }">

                        {{-- Peringkat --}}
                        <td class="px-5 py-4">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold"
                                 style="background:{{ $rankBg }}; color:{{ $rankTextColor }};">
                                @if($rank === 1)
                                    <i class="fas fa-crown text-xs"></i>
                                @else
                                    {{ $rank }}
                                @endif
                            </div>
                        </td>

                        {{-- Nama --}}
                        <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white/90">
                            {{ $karyawan->Nama_Lengkap_Sesuai_Ijazah ?? '-' }}
                        </td>

                        {{-- NIP --}}
                        <td class="px-5 py-4 text-gray-500 dark:text-gray-400 font-mono text-theme-xs">
                            {{ $karyawan->NIK ?? '-' }}
                        </td>

                        {{-- Kelas --}}
                        <td class="px-5 py-4 text-gray-700 dark:text-gray-300">
                            {{ $kelas }}
                        </td>

                        {{-- Kompetensi Tersedia --}}
                        <td class="px-5 py-4 max-w-[220px]">
                            @if($available->isEmpty())
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @else
                                {{-- Collapsed --}}
                                <div x-show="!openAvail" class="flex flex-wrap items-center gap-1">
                                    @foreach($available->take(2) as $k)
                                    <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-semibold whitespace-nowrap"
                                          style="background:#EFF6FF; color:#1D4ED8;">
                                        {{ $k }}
                                    </span>
                                    @endforeach
                                    @if($available->count() > 2)
                                    <button @click.stop="openAvail = true"
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold transition-colors"
                                            style="background:#F1F5F9; color:#64748B;"
                                            onmouseover="this.style.background='#EFF6FF'; this.style.color='#1D4ED8';"
                                            onmouseout="this.style.background='#F1F5F9'; this.style.color='#64748B';">
                                        +{{ $available->count() - 2 }} more
                                    </button>
                                    @endif
                                </div>

                                {{-- Expanded --}}
                                <div x-show="openAvail"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 -translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="flex flex-wrap items-center gap-1">
                                    @foreach($available as $k)
                                    <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-semibold whitespace-nowrap"
                                          style="background:#EFF6FF; color:#1D4ED8;">
                                        {{ $k }}
                                    </span>
                                    @endforeach
                                    <button @click.stop="openAvail = false"
                                            class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-semibold transition-colors"
                                            style="background:#F1F5F9; color:#64748B;"
                                            onmouseover="this.style.background='#E2E8F0';"
                                            onmouseout="this.style.background='#F1F5F9';">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                                        </svg>
                                        Tutup
                                    </button>
                                </div>
                            @endif
                        </td>

                        {{-- Kompetensi Diselesaikan --}}
                        <td class="px-5 py-4 max-w-[220px]">
                            @if($completed->isEmpty())
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @else
                                {{-- Collapsed --}}
                                <div x-show="!openDone" class="flex flex-wrap items-center gap-1">
                                    @foreach($completed->take(2) as $k)
                                    <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-semibold whitespace-nowrap"
                                          style="background:#DCFCE7; color:#15803D;">
                                        {{ $k }}
                                    </span>
                                    @endforeach
                                    @if($completed->count() > 2)
                                    <button @click.stop="openDone = true"
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold transition-colors"
                                            style="background:#F1F5F9; color:#64748B;"
                                            onmouseover="this.style.background='#DCFCE7'; this.style.color='#15803D';"
                                            onmouseout="this.style.background='#F1F5F9'; this.style.color='#64748B';">
                                        +{{ $completed->count() - 2 }} more
                                    </button>
                                    @endif
                                </div>

                                {{-- Expanded --}}
                                <div x-show="openDone"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 -translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="flex flex-wrap items-center gap-1">
                                    @foreach($completed as $k)
                                    <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-semibold whitespace-nowrap"
                                          style="background:#DCFCE7; color:#15803D;">
                                        {{ $k }}
                                    </span>
                                    @endforeach
                                    <button @click.stop="openDone = false"
                                            class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-semibold transition-colors"
                                            style="background:#F1F5F9; color:#64748B;"
                                            onmouseover="this.style.background='#E2E8F0';"
                                            onmouseout="this.style.background='#F1F5F9';">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                                        </svg>
                                        Tutup
                                    </button>
                                </div>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                    <i class="fas fa-inbox text-xl text-gray-400 dark:text-gray-600"></i>
                                </div>
                                <p class="font-medium text-gray-700 dark:text-white/90">Belum ada data kompetensi</p>
                                <p class="text-theme-sm text-gray-400 dark:text-gray-500">Coba ubah filter pencarian atau sinkronkan data dari Wadja</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($kompetensiList->hasPages())
        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
            {{ $kompetensiList->links() }}
        </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('btn-sync').addEventListener('click', function () {
            const btn  = this;
            const icon = document.getElementById('icon-sync');
            const text = document.getElementById('text-sync');

            Swal.fire({
                title: 'Sinkronisasi Data?',
                text: 'Sistem akan menarik data terbaru dari LMS Wadja.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#465fff',
                cancelButtonColor: '#f04438',
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (!result.isConfirmed) return;

                btn.disabled = true;
                icon.classList.add('fa-spin');
                text.innerText = 'Sinkronisasi...';

                fetch("{{ route('kompetensi.sync') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error'))
                .finally(() => {
                    btn.disabled = false;
                    icon.classList.remove('fa-spin');
                    text.innerText = 'Tarik Data Wadja';
                });
            });
        });
    </script>
    @endpush
@endsection
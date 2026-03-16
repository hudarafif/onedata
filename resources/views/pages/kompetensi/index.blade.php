@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Monitoring Kompetensi (LMS)" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5 sm:p-6 shadow-sm">
        <!-- Header / Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <i class="fas fa-trophy text-orange-500 text-2xl"></i> Top 10 Skor Tertinggi
            </h3>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <form action="{{ route('kompetensi.monitoring') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative flex items-center bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 shadow-xs hidden sm:flex">
                        <i class="far fa-calendar-alt text-gray-500 mr-2"></i>
                        <select name="period" class="bg-transparent border-none p-0 text-sm text-gray-700 dark:text-gray-300 focus:ring-0 cursor-pointer outline-none">
                            <option value="6_bulan">6 Bulan</option>
                            <option value="1_tahun">1 Tahun</option>
                        </select>
                    </div>

                    <div class="relative w-full sm:w-auto">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-search text-sm"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pegawai..."
                            class="h-10 w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm text-gray-800 shadow-xs placeholder-gray-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white xl:w-[200px]" />
                    </div>

                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-50 shadow-xs dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700 transition">
                        Terapkan
                    </button>
                </form>

                <div class="flex items-center gap-2 w-full sm:w-auto border-l border-gray-200 dark:border-gray-700 pl-0 sm:pl-3">
                    <a href="{{ route('kompetensi.export', request()->all()) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 shadow-sm transition gap-2">
                        <i class="fas fa-file-csv"></i> Ekspor CSV
                    </a>
                    
                    <button type="button" id="btn-sync" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 shadow-sm transition gap-2">
                        <i class="fas fa-sync-alt" id="icon-sync"></i> <span id="text-sync">Tarik Data Wadja</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left text-sm text-gray-700 dark:text-gray-300">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th scope="col" class="px-5 py-4 font-semibold">Peringkat</th>
                        <th scope="col" class="px-5 py-4 font-semibold">Nama</th>
                        <th scope="col" class="px-5 py-4 font-semibold">NIP</th>
                        <th scope="col" class="px-5 py-4 font-semibold">Kelas</th>
                        <th scope="col" class="px-5 py-4 font-semibold">Kompetensi Tersedia</th>
                        <th scope="col" class="px-5 py-4 font-semibold">Kompetensi Diselesaikan</th>
                        <th scope="col" class="px-5 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                    @forelse ($kompetensiList as $index => $karyawan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition">
                            <td class="px-5 py-4">
                                @php
                                    $rank = $kompetensiList->firstItem() + $index;
                                    $rankClass = 'bg-gray-400 text-white'; // default
                                    if($rank == 1) $rankClass = 'bg-yellow-500 text-white';
                                    elseif($rank == 2) $rankClass = 'bg-gray-400 text-white';
                                    elseif($rank == 3) $rankClass = 'bg-orange-500 text-white';
                                @endphp
                                <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold {{ $rankClass }}">
                                    {{ $rank }}
                                </div>
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ $karyawan->Nama_Lengkap_Sesuai_Ijazah ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-gray-500">
                                {{ $karyawan->NIK ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-gray-800 dark:text-gray-200">
                                @php
                                    $job = $karyawan->pekerjaanTerkini()->first() ?? $karyawan->pekerjaan()->first();
                                    $kelas = $job->department->name ?? '-';
                                @endphp
                                {{ $kelas }}
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400 max-w-[200px] truncate">
                                {{ $karyawan->pegawaiKompetensi->where('status', 'available')->pluck('nama_kompetensi')->unique()->implode(', ') ?: '-' }}
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400 max-w-[200px] truncate">
                                {{ $karyawan->pegawaiKompetensi->where('status', 'completed')->pluck('nama_kompetensi')->unique()->implode(', ') ?: '-' }}
                            </td>
                            <!-- <td class="px-5 py-4 text-center">
                                <button type="button" 
                                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 btn-detail"
                                    data-name="{{ $karyawan->Nama_Lengkap_Sesuai_Ijazah }}"
                                    data-kompetensi='@json($karyawan->pegawaiKompetensi)'>
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </button>
                            </td> -->
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data kompetensi pegawai yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kompetensiList->hasPages())
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                {{ $kompetensiList->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail Kompetensi -->
    <div id="modal-detail" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl dark:bg-gray-900 sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">Detail Kompetensi Pegawai</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="px-6 py-6" id="modal-body">
                    <!-- Content will be injected by JS -->
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 text-right">
                    <button onclick="closeModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Synchronize with Wadja LMS
        document.getElementById('btn-sync').addEventListener('click', function() {
            const btn = this;
            const icon = document.getElementById('icon-sync');
            const text = document.getElementById('text-sync');

            Swal.fire({
                title: 'Sinkronisasi Data?',
                text: "Sistem akan menarik data terbaru dari LMS Wadja.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Loading State
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
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil!', data.message, 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error!', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        icon.classList.remove('fa-spin');
                        text.innerText = 'Tarik Data Wadja';
                    });
                }
            });
        });

        // Detail Modal Logic
        const modal = document.getElementById('modal-detail');
        const modalBody = document.getElementById('modal-body');
        const modalTitle = document.getElementById('modal-title');

        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', function() {
                const name = this.getAttribute('data-name');
                const kompetensi = JSON.parse(this.getAttribute('data-kompetensi'));
                
                modalTitle.innerText = `Detail Kompetensi - ${name}`;
                
                let html = `
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Kompetensi Diselesaikan (${kompetensi.filter(k => k.status === 'completed').length})</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                ${kompetensi.filter(k => k.status === 'completed').map(k => `
                                    <div class="p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30">
                                        <div class="flex justify-between items-start">
                                            <div class="font-semibold text-gray-900 dark:text-white">${k.nama_kompetensi}</div>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">SELESAI</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">${k.level || '-'} • ${k.sumber}</div>
                                    </div>
                                `).join('') || '<p class="text-sm text-gray-400 italic">Belum ada kompetensi diselesaikan.</p>'}
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Kompetensi Tersedia (${kompetensi.filter(k => k.status === 'available').length})</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                ${kompetensi.filter(k => k.status === 'available').map(k => `
                                    <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/30 border border-gray-200 dark:border-gray-700/50">
                                        <div class="flex justify-between items-start">
                                            <div class="font-semibold text-gray-900 dark:text-white">${k.nama_kompetensi}</div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">${k.level || '-'} • ${k.sumber}</div>
                                    </div>
                                `).join('') || '<p class="text-sm text-gray-400 italic">Tidak ada kompetensi tersedia tambahan.</p>'}
                            </div>
                        </div>
                    </div>
                `;
                
                modalBody.innerHTML = html;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
    @endpush
@endsection

@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    <!-- HEADER -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Form Permintaan Karyawan
            </h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">
                Kelola seluruh pengajuan kebutuhan rekrutmen dari tim Anda
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('rekrutmen.fpk.history') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow hover:bg-gray-50 transition dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                History
            </a>
            <a href="{{ route('rekrutmen.fpk.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat FPK
            </a>
        </div>
    </div>

    <!-- SUCCESS ALERT -->
    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-900 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <!-- STATISTIK -->
    @php
        $total    = $fpk->total();
        $pending  = $fpk->getCollection()->whereIn('status_fpk', ['Pending HR Admin', 'Reviewing by HR Manager'])->count();
        $approved = $fpk->getCollection()->where('status_fpk', 'Approved')->count();
        $rejected = $fpk->getCollection()->where('status_fpk', 'Rejected')->count();
    @endphp
    <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total FPK</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $total }}</p>
        </div>
        <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-5 dark:border-yellow-800 dark:bg-yellow-900/20">
            <p class="text-xs font-semibold uppercase tracking-wide text-yellow-600 dark:text-yellow-400">Dalam Proses</p>
            <p class="mt-2 text-3xl font-bold text-yellow-700 dark:text-yellow-300">{{ $pending }}</p>
        </div>
        <div class="rounded-xl border border-green-200 bg-green-50 p-5 dark:border-green-800 dark:bg-green-900/20">
            <p class="text-xs font-semibold uppercase tracking-wide text-green-600 dark:text-green-400">Disetujui</p>
            <p class="mt-2 text-3xl font-bold text-green-700 dark:text-green-300">{{ $approved }}</p>
        </div>
        <div class="rounded-xl border border-red-200 bg-red-50 p-5 dark:border-red-800 dark:bg-red-900/20">
            <p class="text-xs font-semibold uppercase tracking-wide text-red-600 dark:text-red-400">Ditolak</p>
            <p class="mt-2 text-3xl font-bold text-red-700 dark:text-red-300">{{ $rejected }}</p>
        </div>
    </div>

    <!-- TABLE -->
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        <!-- TOP BAR -->
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4">
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan {{ $fpk->firstItem() ?? 0 }} - {{ $fpk->lastItem() ?? 0 }} dari {{ $fpk->total() }} pengajuan
                </span>
            </div>
            <div>
                <input type="text" placeholder="Cari nomor FPK atau posisi..."
                       class="w-full md:w-72 rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-4 pr-10 text-sm text-gray-900 transition placeholder:text-gray-400
                              focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20
                              dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:bg-gray-700" />
            </div>
        </div>

        <!-- TABLE -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full min-w-full">
                <thead>
                    <tr class="border-y border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400 xl:pl-10">Nomor FPK</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Posisi</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Divisi</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Mulai Kerja</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-600 dark:text-gray-400">Kebutuhan</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-600 dark:text-gray-400">Status</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-600 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($fpk as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition">
                        <!-- Nomor FPK -->
                        <td class="px-4 py-4 xl:pl-10">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $row->nomor_fpk }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $row->created_at->format('d M Y') }}</span>
                            </div>
                        </td>

                        <!-- Posisi -->
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $row->nama_jabatan }}</span>
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/20 dark:text-blue-400 mt-1 w-fit">{{ $row->level }}</span>
                            </div>
                        </td>

                        <!-- Divisi -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $row->division->name ?? '-' }}
                        </td>

                        <!-- Tanggal Mulai -->
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($row->tanggal_mulai_bekerja)->format('d M Y') }}
                        </td>

                        <!-- Kebutuhan -->
                        <td class="px-4 py-4 text-center">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $row->jumlah_kebutuhan }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400"> org</span>
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-4 text-center">
                            @if($row->status_fpk == 'Approved')
                                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">
                                    <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                    Approved
                                </span>
                            @elseif($row->status_fpk == 'Rejected')
                                <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-900/20 dark:text-red-400">
                                    <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                    Rejected
                                </span>
                            @elseif($row->status_fpk == 'Reviewing by HR Manager')
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/20 dark:text-blue-400">
                                    <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                    HR Review
                                </span>
                            @elseif($row->status_fpk == 'Pending HR Admin')
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400">
                                    <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                                    Admin Review
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $row->status_fpk }}
                                </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('rekrutmen.fpk.show', $row->id) }}"
                                   class="inline-flex items-center justify-center rounded-lg bg-blue-50 p-2 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 transition"
                                   title="Detail">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                @if(in_array($row->status_fpk, ['Pending HR Admin', 'Draft']))
                                <a href="{{ route('rekrutmen.fpk.edit', $row->id) }}"
                                   class="inline-flex items-center justify-center rounded-lg bg-yellow-50 p-2 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:hover:bg-yellow-900/40 transition"
                                   title="Edit">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('rekrutmen.fpk.destroy', $row->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus FPK ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center rounded-lg bg-red-50 p-2 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 transition"
                                            title="Hapus">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                                    <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="font-medium text-gray-900 dark:text-white">Belum ada pengajuan FPK</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Mulai ajukan kebutuhan karyawan baru dari tim Anda</p>
                                <a href="{{ route('rekrutmen.fpk.create') }}"
                                   class="mt-2 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Buat FPK Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if($fpk->hasPages())
        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Halaman {{ $fpk->currentPage() }} dari {{ $fpk->lastPage() }}
            </div>
            <div class="flex items-center gap-2">
                {{ $fpk->links('vendor.pagination.tailwind') }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
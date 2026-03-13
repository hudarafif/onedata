@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-title-sm font-bold text-gray-900 dark:text-white/90">Form Permintaan Karyawan</h1>
            <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                Kelola seluruh pengajuan kebutuhan rekrutmen dari tim Anda
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('rekrutmen.fpk.history') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                History
            </a>
            <a href="{{ route('rekrutmen.fpk.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat FPK
            </a>
        </div>
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
    <div class="mb-6 rounded-xl border border-success-200 bg-success-50 py-3 px-4 text-theme-sm font-medium text-success-700 dark:border-success-500/20 dark:bg-success-500/10 dark:text-success-400">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
    @endif

    {{-- STATISTIK --}}
    @php
        $total    = $fpk->total();
        $pending  = $fpk->getCollection()->whereNotIn('status_fpk', ['Approved', 'Rejected', 'Draft'])->count();
        $approved = $fpk->getCollection()->where('status_fpk', 'Approved')->count();
        $rejected = $fpk->getCollection()->where('status_fpk', 'Rejected')->count();
    @endphp
    <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 dark:border-gray-800 dark:bg-gray-900 stat-border-blue">
            <p class="text-theme-xs font-semibold uppercase tracking-wide mb-2 text-brand-500">Total FPK</p>
            <p class="text-title-md font-bold text-gray-900 dark:text-white/90">{{ $total }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 dark:border-gray-800 dark:bg-gray-900 stat-border-yellow">
            <p class="text-theme-xs font-semibold uppercase tracking-wide mb-2 text-warning-500">Dalam Proses</p>
            <p class="text-title-md font-bold text-warning-500">{{ $pending }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 dark:border-gray-800 dark:bg-gray-900 stat-border-green">
            <p class="text-theme-xs font-semibold uppercase tracking-wide mb-2 text-success-500">Disetujui</p>
            <p class="text-title-md font-bold text-success-500">{{ $approved }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 dark:border-gray-800 dark:bg-gray-900 stat-border-red">
            <p class="text-theme-xs font-semibold uppercase tracking-wide mb-2 text-error-500">Ditolak</p>
            <p class="text-title-md font-bold text-error-500">{{ $rejected }}</p>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 shadow-theme-sm">

        {{-- TOP BAR --}}
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                Menampilkan
                <span class="font-semibold text-gray-800 dark:text-white/90">{{ $fpk->firstItem() ?? 0 }} – {{ $fpk->lastItem() ?? 0 }}</span>
                dari
                <span class="font-semibold text-gray-800 dark:text-white/90">{{ $fpk->total() }}</span>
                pengajuan
            </p>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-500 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1116.65 2a7.5 7.5 0 010 14.65z"/>
                </svg>
                <input type="text" id="fpk-search" placeholder="Cari nomor FPK atau posisi..."
                       class="w-full md:w-72 rounded-lg border border-gray-200 bg-gray-50 py-2 pl-9 pr-4 text-theme-sm text-gray-800 transition
                              placeholder:text-gray-400
                              focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10
                              dark:border-gray-700 dark:bg-gray-800 dark:text-white/90
                              dark:placeholder:text-gray-500
                              dark:focus:border-brand-700" />
            </div>
        </div>

        {{-- TABLE --}}
        <div class="max-w-full overflow-x-auto">
            <table class="w-full min-w-full" id="fpk-table">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
                        <th class="px-4 py-3 text-left text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 xl:pl-10">Nomor FPK</th>
                        <th class="px-4 py-3 text-left text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Posisi</th>
                        <th class="px-4 py-3 text-left text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Divisi</th>
                        <th class="px-4 py-3 text-left text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-4 py-3 text-center text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($fpk as $row)
                    @php
                        $badgeClass = match($row->status_fpk) {
                            'Approved'                  => 'badge-approved',
                            'Rejected'                  => 'badge-rejected',
                            'Draft'                     => 'badge-draft',
                            'Pending HR Admin'          => 'badge-pending',
                            'Pending Finance Approval'  => 'badge-finance',
                            'Reviewing by HR Manager'   => 'badge-reviewing',
                            'Revision Required'         => 'badge-revision',
                            default                     => 'badge-draft',
                        };
                        $dotColor = match($row->status_fpk) {
                            'Approved'                  => '#12b76a',
                            'Rejected'                  => '#f04438',
                            'Draft'                     => '#98a2b3',
                            'Pending HR Admin'          => '#f79009',
                            'Pending Finance Approval'  => '#0ba5ec',
                            'Reviewing by HR Manager'   => '#465fff',
                            'Revision Required'         => '#fb6514',
                            default                     => '#98a2b3',
                        };
                        $isPulse = !in_array($row->status_fpk, ['Approved', 'Rejected', 'Draft']);
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors duration-100">

                        {{-- Nomor FPK --}}
                        <td class="px-4 py-4 xl:pl-10">
                            <span class="block text-theme-sm font-semibold text-brand-500">{{ $row->nomor_fpk }}</span>
                            <span class="block text-theme-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $row->created_at->format('d M Y') }}</span>
                        </td>

                        {{-- Posisi --}}
                        <td class="px-4 py-4">
                            <span class="block text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $row->nama_jabatan }}</span>
                            <span class="mt-1 inline-block rounded-full bg-brand-50 px-2 py-0.5 text-[10px] font-bold text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                                {{ $row->level }}
                            </span>
                        </td>

                        {{-- Divisi --}}
                        <td class="px-4 py-4 text-theme-sm text-gray-500 dark:text-gray-400">
                            {{ $row->division->name ?? '-' }}
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-theme-xs font-semibold {{ $badgeClass }}">
                                <span class="h-1.5 w-1.5 rounded-full flex-shrink-0 {{ $isPulse ? 'animate-pulse' : '' }}"
                                      style="background:{{ $dotColor }};"></span>
                                {{ $row->status_fpk }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-4 text-center">
                            <div class="inline-flex items-center justify-center gap-1.5">

                                {{-- View --}}
                                <a href="{{ route('rekrutmen.fpk.show', $row->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-all duration-150 act-view"
                                   title="Lihat Detail">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                @if(in_array($row->status_fpk, ['Draft', 'Revision Required']))
                                {{-- Edit --}}
                                <a href="{{ route('rekrutmen.fpk.edit', $row->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-all duration-150 act-edit"
                                   title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @endif

                                @if($row->status_fpk === 'Draft')
                                {{-- Delete --}}
                                <form action="{{ route('rekrutmen.fpk.destroy', $row->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus FPK {{ $row->nomor_fpk }}? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-all duration-150 act-delete"
                                            title="Hapus">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                    <svg class="h-7 w-7 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="font-medium text-gray-800 dark:text-white/90">Belum ada data pengajuan</p>
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Mulai ajukan kebutuhan karyawan baru dari tim Anda</p>
                                <a href="{{ route('rekrutmen.fpk.create') }}"
                                   class="mt-1 inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-theme-sm font-medium text-white hover:bg-brand-600 transition">
                                    + Buat FPK Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($fpk->hasPages())
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-800">
            <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                Halaman <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $fpk->currentPage() }}</span>
                dari <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $fpk->lastPage() }}</span>
            </p>
            {{ $fpk->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>

<script>
document.getElementById('fpk-search')?.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('#fpk-table tbody tr').forEach(row => {
        if (row.querySelector('td[colspan]')) return;
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endsection
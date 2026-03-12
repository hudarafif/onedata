@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-black dark:text-white">Form Permintaan Karyawan</h1>
            <p class="mt-1 text-sm text-black dark:text-white opacity-60">
                Kelola seluruh pengajuan kebutuhan rekrutmen dari tim Anda
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('rekrutmen.fpk.history') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-stroke bg-white px-4 py-2 text-sm font-medium text-black shadow-sm hover:bg-gray-50 transition dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-meta-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                History
            </a>
            <a href="{{ route('rekrutmen.fpk.create') }}"
               class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white transition shadow-lg"
               style="background:#3B82F6;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat FPK
            </a>    
        </div>
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
    <div class="mb-6 rounded-lg border py-3 px-4 text-sm font-medium bg-green-50 border-green-200 text-green-800">
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
        // Pending = All statuses except Approved, Rejected, and Draft
        $pending  = $fpk->getCollection()->whereNotIn('status_fpk', ['Approved', 'Rejected', 'Draft'])->count();
        $approved = $fpk->getCollection()->where('status_fpk', 'Approved')->count();
        $rejected = $fpk->getCollection()->where('status_fpk', 'Rejected')->count();
    @endphp
    <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
        <div class="rounded-xl border border-stroke bg-white px-5 py-4 dark:border-strokedark dark:bg-boxdark border-t-4 border-t-blue-500">
            <p class="text-xs font-semibold uppercase tracking-wide mb-2 text-blue-500">Total FPK</p>
            <p class="text-3xl font-bold text-black dark:text-white">{{ $total }}</p>
        </div>
        <div class="rounded-xl border border-stroke bg-white px-5 py-4 dark:border-strokedark dark:bg-boxdark border-t-4 border-t-yellow-500">
            <p class="text-xs font-semibold uppercase tracking-wide mb-2 text-yellow-500">Dalam Proses</p>
            <p class="text-3xl font-bold text-yellow-500">{{ $pending }}</p>
        </div>
        <div class="rounded-xl border border-stroke bg-white px-5 py-4 dark:border-strokedark dark:bg-boxdark border-t-4 border-t-green-500">
            <p class="text-xs font-semibold uppercase tracking-wide mb-2 text-green-500">Disetujui</p>
            <p class="text-3xl font-bold text-green-500">{{ $approved }}</p>
        </div>
        <div class="rounded-xl border border-stroke bg-white px-5 py-4 dark:border-strokedark dark:bg-boxdark border-t-4 border-t-red-500">
            <p class="text-xs font-semibold uppercase tracking-wide mb-2 text-red-500">Ditolak</p>
            <p class="text-3xl font-bold text-red-500">{{ $rejected }}</p>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="rounded-xl border border-stroke bg-white dark:border-strokedark dark:bg-boxdark shadow-sm">

        {{-- TOP BAR --}}
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b border-stroke dark:border-strokedark">
            <p class="text-sm text-black dark:text-white opacity-60">
                Menampilkan
                <span class="font-semibold opacity-100 text-black dark:text-white">{{ $fpk->firstItem() ?? 0 }} – {{ $fpk->lastItem() ?? 0 }}</span>
                dari
                <span class="font-semibold opacity-100 text-black dark:text-white">{{ $fpk->total() }}</span>
                pengajuan
            </p>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-black dark:text-white opacity-40 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1116.65 2a7.5 7.5 0 010 14.65z"/>
                </svg>
                <input type="text" id="fpk-search" placeholder="Cari nomor FPK atau posisi..."
                       class="w-full md:w-72 rounded-lg border border-stroke bg-gray-2 py-2 pl-9 pr-4 text-sm text-black transition placeholder:text-black placeholder:opacity-40 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white" />
            </div>
        </div>

        {{-- TABLE --}}
        <div class="max-w-full overflow-x-auto">
            <table class="w-full min-w-full" id="fpk-table">
                <thead>
                    <tr class="border-b border-stroke bg-gray-2 dark:border-strokedark dark:bg-meta-4">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-black dark:text-white opacity-60 xl:pl-10">Nomor FPK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-black dark:text-white opacity-60">Posisi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-black dark:text-white opacity-60">Divisi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-black dark:text-white opacity-60">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-black dark:text-white opacity-60">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stroke dark:divide-strokedark">
                    @forelse($fpk as $row)
                    <tr class="hover:bg-gray-2 dark:hover:bg-meta-4 transition-colors duration-100">
                        <td class="px-4 py-4 xl:pl-10">
                            <span class="block text-sm font-semibold text-blue-600">{{ $row->nomor_fpk }}</span>
                            <span class="block text-xs text-black dark:text-white opacity-50 mt-0.5">{{ $row->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="block text-sm font-medium text-black dark:text-white">{{ $row->nama_jabatan }}</span>
                            <span class="mt-1 inline-block rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700">{{ $row->level }}</span>
                        </td>
                        <td class="px-4 py-4 text-sm text-black dark:text-white opacity-70">
                            {{ $row->division->name ?? '-' }}
                        </td>
                        <td class="px-4 py-4">
                            @php
                                $statusMap = [
                                    'Approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'circle' => 'bg-green-500'],
                                    'Rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'circle' => 'bg-red-500'],
                                    'Draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'circle' => 'bg-gray-500'],
                                    'Pending HR Admin' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'circle' => 'bg-yellow-500', 'pulse' => true],
                                    'Pending Finance Approval' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'circle' => 'bg-purple-500', 'pulse' => true],
                                    'Reviewing by HR Manager' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'circle' => 'bg-blue-500', 'pulse' => true],
                                    'Revision Required' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'circle' => 'bg-orange-500', 'pulse' => true],
                                ];
                                $s = $statusMap[$row->status_fpk] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-500', 'circle' => 'bg-gray-400'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $s['bg'] }} {{ $s['text'] }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $s['circle'] }} {{ ($s['pulse'] ?? false) ? 'animate-pulse' : '' }}"></span>
                                {{ $row->status_fpk }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="inline-flex items-center justify-center gap-2">
                                <a href="{{ route('rekrutmen.fpk.show', $row->id) }}" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm" title="View Detail">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>

                                @if(in_array($row->status_fpk, ['Draft', 'Revision Required']))
                                <a href="{{ route('rekrutmen.fpk.edit', $row->id) }}" class="p-2 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-600 hover:text-white transition shadow-sm" title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @endif

                                @if($row->status_fpk === 'Draft')
                                <form action="{{ route('rekrutmen.fpk.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Hapus FPK ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition shadow-sm" title="Delete">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center opacity-50">Belum ada data pengajuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($fpk->hasPages())
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-stroke px-6 py-4 dark:border-strokedark">
            <p class="text-xs text-black dark:text-white opacity-60">Halaman {{ $fpk->currentPage() }} dari {{ $fpk->lastPage() }}</p>
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
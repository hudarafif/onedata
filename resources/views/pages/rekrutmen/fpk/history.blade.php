@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-bold text-gray-900 dark:text-white/90">History Pengajuan FPK</h1>
            <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                Pantau status pengajuan Form Permintaan Karyawan Anda secara real-time
            </p>
        </div>
        <a href="{{ route('rekrutmen.fpk.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat FPK Baru
        </a>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
    <div class="mb-5 flex items-center gap-2 rounded-xl border px-4 py-3 text-theme-sm font-medium alert-success">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Statistik --}}
    @php
        $total    = $fpk->count();
        $pending  = $fpk->whereNotIn('status_fpk', ['Approved', 'Rejected', 'Draft'])->count();
        $approved = $fpk->where('status_fpk', 'Approved')->count();
        $rejected = $fpk->where('status_fpk', 'Rejected')->count();
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 stat-border-blue">
            <p class="text-theme-xs font-semibold uppercase tracking-wide mb-2 text-brand-500">Total FPK</p>
            <p class="text-title-md font-bold text-gray-900 dark:text-white/90">{{ $total }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 stat-border-yellow">
            <p class="text-theme-xs font-semibold uppercase tracking-wide mb-2 text-warning-500">Processing</p>
            <p class="text-title-md font-bold text-warning-500">{{ $pending }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 stat-border-green">
            <p class="text-theme-xs font-semibold uppercase tracking-wide mb-2 text-success-500">Approved</p>
            <p class="text-title-md font-bold text-success-500">{{ $approved }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900 stat-border-red">
            <p class="text-theme-xs font-semibold uppercase tracking-wide mb-2 text-error-500">Rejected</p>
            <p class="text-title-md font-bold text-error-500">{{ $rejected }}</p>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="font-semibold text-gray-800 dark:text-white/90">Riwayat Pengajuan</h3>
        </div>

        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
                        <th class="py-3 px-4 text-left text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 xl:pl-10">Nomor FPK</th>
                        <th class="py-3 px-4 text-left text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Posisi & Level</th>
                        <th class="py-3 px-4 text-center text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status Alur</th>
                        <th class="py-3 px-4 text-center text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($fpk as $row)
                    @php
                        $status           = $row->status_fpk;
                        $isRejected       = $status === 'Rejected';
                        $isApproved       = $status === 'Approved';
                        $atLeastHrDone    = in_array($status, ['Pending Finance Approval', 'Reviewing by HR Manager', 'Approved', 'Rejected']);
                        $atLeastFinDone   = in_array($status, ['Reviewing by HR Manager', 'Approved', 'Rejected']);
                        $atLeastFinalDone = in_array($status, ['Approved', 'Rejected']);

                        // Colors pakai CSS variable TailAdmin
                        $cGreen   = '#12b76a';
                        $cGray    = '#d0d5dd';
                        $cRed     = '#f04438';
                        $cGrayTxt = '#98a2b3';
                        $cGreenTxt= '#039855';
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors duration-100">

                        {{-- Nomor FPK --}}
                        <td class="py-5 px-4 xl:pl-10">
                            <p class="text-theme-sm font-semibold text-brand-500">{{ $row->nomor_fpk }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 uppercase font-semibold tracking-wide">
                                {{ $row->created_at->format('d M Y') }}
                            </p>
                        </td>

                        {{-- Posisi & Level --}}
                        <td class="py-5 px-4">
                            <p class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $row->nama_jabatan }}</p>
                            <span class="mt-1 inline-block rounded-full px-2 py-0.5 text-[10px] font-bold bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                                {{ $row->level }}
                            </span>
                        </td>

                        {{-- Progress Tracker --}}
                        <td class="py-5 px-4">
                            <div style="display:flex; align-items:center; justify-content:center; gap:0;">

                                {{-- Step 1: Submit (selalu done) --}}
                                <div style="display:flex; flex-direction:column; align-items:center; gap:3px;">
                                    <div style="width:24px; height:24px; border-radius:50%; background:{{ $cGreen }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; box-shadow:0 1px 3px rgba(18,183,106,.4);">✓</div>
                                    <span style="font-size:8px; color:{{ $cGreenTxt }}; text-transform:uppercase; font-weight:800; letter-spacing:.04em; white-space:nowrap;">Submit</span>
                                </div>

                                {{-- Line 1→2 --}}
                                <div style="width:24px; height:2px; margin-bottom:16px; background:{{ $atLeastHrDone ? $cGreen : $cGray }};"></div>

                                {{-- Step 2: HR Admin --}}
                                <div style="display:flex; flex-direction:column; align-items:center; gap:3px;">
                                    @if($atLeastHrDone)
                                        <div style="width:24px; height:24px; border-radius:50%; background:{{ $cGreen }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; box-shadow:0 1px 3px rgba(18,183,106,.4);">✓</div>
                                        <span style="font-size:8px; color:{{ $cGreenTxt }}; text-transform:uppercase; font-weight:800; letter-spacing:.04em; white-space:nowrap;">HR Admin</span>
                                    @else
                                        <div style="width:24px; height:24px; border-radius:50%; background:{{ $cGray }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800;">2</div>
                                        <span style="font-size:8px; color:{{ $cGrayTxt }}; text-transform:uppercase; font-weight:800; letter-spacing:.04em; white-space:nowrap;">HR Admin</span>
                                    @endif
                                </div>

                                {{-- Line 2→3 --}}
                                <div style="width:24px; height:2px; margin-bottom:16px; background:{{ $atLeastFinDone ? $cGreen : $cGray }};"></div>

                                {{-- Step 3: Finance --}}
                                <div style="display:flex; flex-direction:column; align-items:center; gap:3px;">
                                    @if($atLeastFinDone)
                                        <div style="width:24px; height:24px; border-radius:50%; background:{{ $cGreen }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; box-shadow:0 1px 3px rgba(18,183,106,.4);">✓</div>
                                        <span style="font-size:8px; color:{{ $cGreenTxt }}; text-transform:uppercase; font-weight:800; letter-spacing:.04em; white-space:nowrap;">Finance</span>
                                    @else
                                        <div style="width:24px; height:24px; border-radius:50%; background:{{ $cGray }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800;">3</div>
                                        <span style="font-size:8px; color:{{ $cGrayTxt }}; text-transform:uppercase; font-weight:800; letter-spacing:.04em; white-space:nowrap;">Finance</span>
                                    @endif
                                </div>

                                {{-- Line 3→4 --}}
                                <div style="width:24px; height:2px; margin-bottom:16px; background:{{ $atLeastFinalDone ? ($isRejected ? $cRed : $cGreen) : $cGray }};"></div>

                                {{-- Step 4: Final --}}
                                <div style="display:flex; flex-direction:column; align-items:center; gap:3px;">
                                    @if($isApproved)
                                        <div style="width:24px; height:24px; border-radius:50%; background:{{ $cGreen }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; box-shadow:0 1px 3px rgba(18,183,106,.4);">✓</div>
                                        <span style="font-size:8px; color:{{ $cGreenTxt }}; text-transform:uppercase; font-weight:800; letter-spacing:.04em; white-space:nowrap;">Final</span>
                                    @elseif($isRejected)
                                        <div style="width:24px; height:24px; border-radius:50%; background:{{ $cRed }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; box-shadow:0 1px 3px rgba(240,68,56,.4);">✗</div>
                                        <span style="font-size:8px; color:{{ $cRed }}; text-transform:uppercase; font-weight:800; letter-spacing:.04em; white-space:nowrap;">Final</span>
                                    @else
                                        <div style="width:24px; height:24px; border-radius:50%; background:{{ $cGray }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800;">4</div>
                                        <span style="font-size:8px; color:{{ $cGrayTxt }}; text-transform:uppercase; font-weight:800; letter-spacing:.04em; white-space:nowrap;">Final</span>
                                    @endif
                                </div>

                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="py-5 px-4 text-center">
                            <a href="{{ route('rekrutmen.fpk.show', $row->id) }}"
                               class="inline-flex items-center gap-1.5 rounded-lg px-4 py-1.5 text-theme-xs font-bold transition-all duration-150 act-view">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                    <svg class="h-7 w-7 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="font-semibold text-gray-800 dark:text-white/90">Belum ada riwayat pengajuan FPK</p>
                                <a href="{{ route('rekrutmen.fpk.create') }}"
                                   class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2 text-theme-sm font-medium text-white hover:bg-brand-600 transition">
                                    + Buat Pengajuan Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
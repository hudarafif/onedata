@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">History Pengajuan FPK</h2>
            <p class="text-sm text-black dark:text-white opacity-60 mt-1">Pantau status pengajuan Form Permintaan Karyawan Anda secara real-time</p>
        </div>
        <a href="{{ route('rekrutmen.fpk.create') }}"
           class="inline-flex items-center gap-2 rounded-md bg-primary py-2 px-6 font-medium text-white hover:bg-opacity-90">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat FPK Baru
        </a>
    </div>

    @if(session('success'))
    <div class="mb-5 rounded py-3 px-4 border bg-green-50 border-green-200 text-green-700 font-medium">
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
        <div class="rounded-xl border border-stroke bg-white py-5 px-6 shadow-sm dark:border-strokedark dark:bg-boxdark border-t-4 border-t-blue-500">
            <p class="text-xs font-bold uppercase tracking-widest mb-1 text-blue-500">Total FPK</p>
            <p class="text-3xl font-extrabold text-black dark:text-white">{{ $total }}</p>
        </div>
        <div class="rounded-xl border border-stroke bg-white py-5 px-6 shadow-sm dark:border-strokedark dark:bg-boxdark border-t-4 border-t-yellow-500">
            <p class="text-xs font-bold uppercase tracking-widest mb-1 text-yellow-500">Processing</p>
            <p class="text-3xl font-extrabold text-yellow-500">{{ $pending }}</p>
        </div>
        <div class="rounded-xl border border-stroke bg-white py-5 px-6 shadow-sm dark:border-strokedark dark:bg-boxdark border-t-4 border-t-green-500">
            <p class="text-xs font-bold uppercase tracking-widest mb-1 text-green-500">Approved</p>
            <p class="text-3xl font-extrabold text-green-500">{{ $approved }}</p>
        </div>
        <div class="rounded-xl border border-stroke bg-white py-5 px-6 shadow-sm dark:border-strokedark dark:bg-boxdark border-t-4 border-t-red-500">
            <p class="text-xs font-bold uppercase tracking-widest mb-1 text-red-500">Rejected</p>
            <p class="text-3xl font-extrabold text-red-500">{{ $rejected }}</p>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="rounded-xl border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark overflow-hidden">
        <div class="px-6 py-4 border-b border-stroke dark:border-strokedark bg-gray-50/50 dark:bg-meta-4/20">
            <h3 class="font-bold text-black dark:text-white">Riwayat Pengajuan</h3>
        </div>
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-100 text-left dark:bg-meta-4 border-b border-stroke dark:border-strokedark">
                        <th class="py-4 px-4 font-bold text-xs uppercase text-gray-500 dark:text-gray-400 xl:pl-11">Nomor FPK</th>
                        <th class="py-4 px-4 font-bold text-xs uppercase text-gray-500 dark:text-gray-400">Posisi & Level</th>
                        <th class="py-4 px-4 font-bold text-xs uppercase text-gray-500 dark:text-gray-400 text-center">Status Alur</th>
                        <th class="py-4 px-4 font-bold text-xs uppercase text-gray-500 dark:text-gray-400 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-strokedark">
                    @forelse($fpk as $row)
                    @php
                        $status = $row->status_fpk;
                        $isRejected = $status === 'Rejected';
                        $isApproved = $status === 'Approved';
                        
                        // Tracker Logic
                        $atLeastHrDone    = in_array($status, ['Pending Finance Approval', 'Reviewing by HR Manager', 'Approved', 'Rejected']);
                        $atLeastFinDone   = in_array($status, ['Reviewing by HR Manager', 'Approved', 'Rejected']);
                        $atLeastFinalDone = in_array($status, ['Approved', 'Rejected']);

                        $colorActive = '#10B981'; // Green
                        $colorPending = '#CBD5E1'; // Gray
                        $colorReject = '#EF4444'; // Red
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-boxdark-2 transition-colors">
                        <td class="py-5 px-4 pl-9 xl:pl-11">
                            <p class="font-bold text-primary text-sm">{{ $row->nomor_fpk }}</p>
                            <p class="text-[10px] text-gray-400 mt-1 uppercase font-semibold">{{ $row->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="py-5 px-4">
                            <p class="font-bold text-black dark:text-white text-sm">{{ $row->nama_jabatan }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded bg-blue-50 text-[10px] font-bold text-blue-600 dark:bg-blue-900/20">{{ $row->level }}</span>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Step 1: Submit --}}
                                <div class="flex flex-col items-center group">
                                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center text-white text-[10px] font-bold shadow-sm">✓</div>
                                    <span class="text-[8px] mt-1 text-gray-400 uppercase font-black">Submit</span>
                                </div>
                                <div class="w-8 h-[2px] mb-4 {{ $atLeastHrDone ? 'bg-green-500' : 'bg-gray-200' }}"></div>

                                {{-- Step 2: HR Admin --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-[10px] font-bold shadow-sm {{ $atLeastHrDone ? 'bg-green-500' : 'bg-gray-200 text-gray-400' }}">
                                        {{ $atLeastHrDone ? '✓' : '2' }}
                                    </div>
                                    <span class="text-[8px] mt-1 {{ $atLeastHrDone ? 'text-green-600' : 'text-gray-400' }} uppercase font-black">HR Admin</span>
                                </div>
                                <div class="w-8 h-[2px] mb-4 {{ $atLeastFinDone ? 'bg-green-500' : 'bg-gray-200' }}"></div>

                                {{-- Step 3: Finance --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-[10px] font-bold shadow-sm {{ $atLeastFinDone ? 'bg-green-500' : 'bg-gray-200 text-gray-400' }}">
                                        {{ $atLeastFinDone ? '✓' : '3' }}
                                    </div>
                                    <span class="text-[8px] mt-1 {{ $atLeastFinDone ? 'text-green-600' : 'text-gray-400' }} uppercase font-black">Finance</span>
                                </div>
                                <div class="w-8 h-[2px] mb-4 {{ $atLeastFinalDone ? 'bg-green-500' : 'bg-gray-200' }}"></div>

                                {{-- Step 4: Final --}}
                                <div class="flex flex-col items-center">
                                    @php $finalColor = $isRejected ? 'bg-red-500' : ($isApproved ? 'bg-green-500' : 'bg-gray-200 text-gray-400'); @endphp
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-[10px] font-bold shadow-sm {{ $finalColor }}">
                                        {{ $isApproved ? '✓' : ($isRejected ? '✗' : '4') }}
                                    </div>
                                    <span class="text-[8px] mt-1 uppercase font-black {{ $isRejected ? 'text-red-500' : ($isApproved ? 'text-green-600' : 'text-gray-400') }}">Final</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-4 text-center">
                            <a href="{{ route('rekrutmen.fpk.show', $row->id) }}"
                               class="inline-flex items-center rounded-lg bg-blue-50 py-1.5 px-4 text-xs font-bold text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center opacity-50 italic">Belum ada riwayat pengajuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
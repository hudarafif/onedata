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
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat FPK Baru
        </a>
    </div>

    @if(session('success'))
    <div class="mb-5 rounded bg-success/20 py-3 px-4 text-success border border-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- Statistik --}}
    @php
        $total    = $fpk->count();
        $pending  = $fpk->whereIn('status_fpk', ['Pending HR Admin', 'Reviewing by HR Manager'])->count();
        $approved = $fpk->where('status_fpk', 'Approved')->count();
        $rejected = $fpk->where('status_fpk', 'Rejected')->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="rounded-sm border border-stroke bg-white py-5 px-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <p class="text-sm font-medium text-black dark:text-white opacity-60 mb-1">Total FPK</p>
            <p class="text-3xl font-bold text-black dark:text-white">{{ $total }}</p>
        </div>
        <div class="rounded-sm border border-stroke bg-white py-5 px-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <p class="text-sm font-medium text-warning mb-1">Dalam Proses</p>
            <p class="text-3xl font-bold text-warning">{{ $pending }}</p>
        </div>
        <div class="rounded-sm border border-stroke bg-white py-5 px-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <p class="text-sm font-medium text-success mb-1">Disetujui</p>
            <p class="text-3xl font-bold text-success">{{ $approved }}</p>
        </div>
        <div class="rounded-sm border border-stroke bg-white py-5 px-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <p class="text-sm font-medium text-danger mb-1">Ditolak</p>
            <p class="text-3xl font-bold text-danger">{{ $rejected }}</p>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="px-6 py-4 border-b border-stroke dark:border-strokedark">
            <h3 class="font-medium text-black dark:text-white">Riwayat Pengajuan</h3>
        </div>
        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-2 text-left dark:bg-meta-4">
                        <th class="py-4 px-4 font-medium text-black dark:text-white xl:pl-11">Nomor FPK</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white">Posisi</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white">Divisi</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white">Tgl Mulai</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white text-center">Kebutuhan</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white text-center">Alur Status</th>
                        <th class="py-4 px-4 font-medium text-black dark:text-white text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fpk as $row)
                    <tr>
                        <td class="border-b border-[#eee] py-5 px-4 pl-9 dark:border-strokedark xl:pl-11">
                            <p class="font-medium text-primary text-sm">{{ $row->nomor_fpk }}</p>
                            <p class="text-xs text-black dark:text-white opacity-50">{{ $row->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                            <p class="font-medium text-black dark:text-white">{{ $row->nama_jabatan }}</p>
                            <p class="text-xs text-black dark:text-white opacity-50">{{ $row->level }}</p>
                        </td>
                        <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                            <p class="text-black dark:text-white">{{ $row->division->name ?? '-' }}</p>
                        </td>
                        <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                            <p class="text-black dark:text-white">{{ \Carbon\Carbon::parse($row->tanggal_mulai_bekerja)->format('d M Y') }}</p>
                        </td>
                        <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark text-center">
                            <p class="font-bold text-black dark:text-white">{{ $row->jumlah_kebutuhan }} <span class="font-normal text-sm">org</span></p>
                        </td>
                        <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark">
                            {{-- Progress Tracker --}}
                            @php
                                $steps = [
                                    'Diajukan'   => true,
                                    'HR Admin'   => in_array($row->status_fpk, ['Reviewing by HR Manager', 'Approved', 'Rejected']),
                                    'HR Manager' => in_array($row->status_fpk, ['Approved', 'Rejected']),
                                ];
                                $isRejected = $row->status_fpk === 'Rejected';
                            @endphp
                            <div class="flex items-end gap-1 justify-center">
                                @foreach($steps as $stepName => $done)
                                <div class="flex items-center gap-1">
                                    <div class="flex flex-col items-center">
                                        <div class="h-6 w-6 rounded-full flex items-center justify-center text-xs font-bold text-white
                                            {{ $done ? ($isRejected && $stepName === 'HR Manager' ? 'bg-danger' : 'bg-success') : 'bg-stroke dark:bg-strokedark' }}">
                                            @if($done && !($isRejected && $stepName === 'HR Manager'))
                                                ✓
                                            @elseif($isRejected && $stepName === 'HR Manager')
                                                ✗
                                            @else
                                                <span class="text-black dark:text-white opacity-40">·</span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-black dark:text-white opacity-60 mt-1 whitespace-nowrap">{{ $stepName }}</span>
                                    </div>
                                    @if(!$loop->last)
                                        <div class="h-0.5 w-5 mb-4 {{ $done ? 'bg-success' : 'bg-stroke dark:bg-strokedark' }}"></div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="border-b border-[#eee] py-5 px-4 dark:border-strokedark text-center">
                            <a href="{{ route('rekrutmen.fpk.show', $row->id) }}"
                               class="inline-flex items-center rounded bg-primary py-1.5 px-4 text-sm font-medium text-white hover:bg-opacity-90">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="border-b border-[#eee] py-16 px-4 text-center dark:border-strokedark">
                            <p class="font-medium text-black dark:text-white mb-2">Belum ada riwayat pengajuan FPK</p>
                            <a href="{{ route('rekrutmen.fpk.create') }}" class="text-sm text-primary hover:underline">+ Buat Pengajuan Pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl p-4 md:p-6 2xl:p-10">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-title-sm font-bold text-gray-900 dark:text-white/90">Detail FPK</h1>
            <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                Nomor: <strong class="font-semibold text-brand-500">{{ $fpk->nomor_fpk }}</strong>
            </p>
        </div>
        <a href="{{ route('rekrutmen.fpk.index') }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="mb-5 flex items-center gap-2 rounded-xl border px-4 py-3 text-theme-sm font-medium alert-success">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-5 rounded-xl border px-4 py-3 text-theme-sm alert-error">
        @foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach
    </div>
    @endif

    {{-- Status Badge --}}
    @php
        $badgeClass = match($fpk->status_fpk) {
            'Approved'                 => 'badge-approved',
            'Rejected'                 => 'badge-rejected',
            'Draft'                    => 'badge-draft',
            'Pending HR Admin'         => 'badge-pending',
            'Pending Finance Approval' => 'badge-finance',
            'Reviewing by HR Manager'  => 'badge-reviewing',
            'Revision Required'        => 'badge-revision',
            default                    => 'badge-draft',
        };
        $dotColor = match($fpk->status_fpk) {
            'Approved'                 => '#12b76a',
            'Rejected'                 => '#f04438',
            'Draft'                    => '#98a2b3',
            'Pending HR Admin'         => '#f79009',
            'Pending Finance Approval' => '#0ba5ec',
            'Reviewing by HR Manager'  => '#465fff',
            'Revision Required'        => '#fb6514',
            default                    => '#98a2b3',
        };
        $isPulse = !in_array($fpk->status_fpk, ['Approved', 'Rejected', 'Draft']);
    @endphp

    <div class="mb-6 flex flex-wrap items-center gap-3">
        <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-theme-sm font-semibold {{ $badgeClass }}">
            <span class="h-2 w-2 rounded-full flex-shrink-0 {{ $isPulse ? 'animate-pulse' : '' }}"
                  style="background:{{ $dotColor }};"></span>
            {{ $fpk->status_fpk }}
        </span>

        @if($fpk->status_fpk === 'Rejected' && $fpk->alasan_reject)
        <div class="w-full rounded-xl border px-4 py-3 alert-error">
            <p class="text-theme-sm"><strong class="font-bold">Alasan Penolakan:</strong> {{ $fpk->alasan_reject }}</p>
        </div>
        @endif

        @if($fpk->status_fpk === 'Revision Required' && $fpk->revision_comment)
        <div class="w-full rounded-xl border px-4 py-3 alert-orange">
            <p class="text-theme-sm"><strong class="font-bold">Catatan Revisi:</strong> {{ $fpk->revision_comment }}</p>
        </div>
        @endif
    </div>

    {{-- CARD A: Informasi Posisi --}}
    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-800 px-6 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl text-white text-theme-xs font-bold flex-shrink-0"
                 style="background:#465fff;">A</div>
            <h4 class="font-semibold text-gray-900 dark:text-white/90">Informasi Posisi</h4>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-0 text-theme-sm">
                <div class="space-y-3">
                    <div class="flex gap-2">
                        <span class="w-40 flex-shrink-0 font-medium text-gray-500 dark:text-gray-400">Divisi</span>
                        <span class="text-gray-800 dark:text-white/90">{{ $fpk->division?->name ?? '-' }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-40 flex-shrink-0 font-medium text-gray-500 dark:text-gray-400">Departemen</span>
                        <span class="text-gray-800 dark:text-white/90">{{ $fpk->department?->name ?? '-' }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-40 flex-shrink-0 font-medium text-gray-500 dark:text-gray-400">Nama Jabatan</span>
                        <span class="font-semibold text-gray-900 dark:text-white/90">{{ $fpk->nama_jabatan }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-40 flex-shrink-0 font-medium text-gray-500 dark:text-gray-400">Level</span>
                        <span class="text-gray-800 dark:text-white/90">{{ $fpk->level }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-40 flex-shrink-0 font-medium text-gray-500 dark:text-gray-400">Lokasi Kerja</span>
                        <span class="text-gray-800 dark:text-white/90">{{ $fpk->lokasi_kerja }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex gap-2">
                        <span class="w-40 flex-shrink-0 font-medium text-gray-500 dark:text-gray-400">Tgl Mulai Kerja</span>
                        <span class="text-gray-800 dark:text-white/90">{{ \Carbon\Carbon::parse($fpk->tanggal_mulai_bekerja)->format('d F Y') }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-40 flex-shrink-0 font-medium text-gray-500 dark:text-gray-400">Jumlah</span>
                        <span class="font-semibold text-gray-900 dark:text-white/90">{{ $fpk->jumlah_kebutuhan }} Orang</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-40 flex-shrink-0 font-medium text-gray-500 dark:text-gray-400">Grade</span>
                        <span class="text-gray-800 dark:text-white/90">{{ $fpk->grade ?? '-' }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-40 flex-shrink-0 font-medium text-gray-500 dark:text-gray-400">Alasan</span>
                        <span class="text-gray-800 dark:text-white/90">{{ $fpk->alasan_permintaan }}</span>
                    </div>
                    @if($fpk->alasan_permintaan == 'Penggantian Karyawan')
                    <div class="flex gap-2">
                        <span class="w-40 flex-shrink-0 font-medium text-gray-500 dark:text-gray-400">Pengganti</span>
                        <span class="text-error-600 dark:text-error-400">{{ $fpk->nama_karyawan_pengganti ?? '-' }}</span>
                    </div>
                    @endif
                    @if($fpk->jangka_waktu_kontrak)
                    <div class="flex gap-2">
                        <span class="w-40 flex-shrink-0 font-medium text-gray-500 dark:text-gray-400">Lama Kontrak</span>
                        <span class="text-gray-800 dark:text-white/90">{{ $fpk->jangka_waktu_kontrak }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($fpk->dampak_kekurangan_posisi)
            <div class="mt-5 rounded-xl border px-4 py-3 alert-warning">
                <p class="text-theme-xs font-semibold mb-1">Dampak Kekosongan Posisi</p>
                <p class="text-theme-sm">{{ $fpk->dampak_kekurangan_posisi }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- CARD B: Kualifikasi --}}
    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-800 px-6 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl text-white text-theme-xs font-bold flex-shrink-0"
                 style="background:#12b76a;">B</div>
            <h4 class="font-semibold text-gray-900 dark:text-white/90">Kualifikasi & Spesifikasi</h4>
        </div>
        <div class="p-6 text-theme-sm">
            {{-- Kualifikasi chips --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                @foreach([
                    ['label' => 'Jenis Kelamin', 'value' => $fpk->kualifikasi_jk ?? 'Bebas'],
                    ['label' => 'Usia',           'value' => $fpk->kualifikasi_usia ?? 'Bebas'],
                    ['label' => 'Pendidikan',     'value' => $fpk->kualifikasi_pendidikan ?? 'Bebas'],
                    ['label' => 'Pengalaman',     'value' => $fpk->kualifikasi_pengalaman ?? 'Bebas'],
                ] as $qual)
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-800/60">
                    <p class="text-theme-xs text-gray-500 dark:text-gray-400 mb-1">{{ $qual['label'] }}</p>
                    <p class="font-semibold text-gray-800 dark:text-white/90">{{ $qual['value'] }}</p>
                </div>
                @endforeach
            </div>

            @if($fpk->kualifikasi_jurusan)
            <div class="flex gap-2 mb-2">
                <span class="font-medium text-gray-500 dark:text-gray-400 w-36 flex-shrink-0">Jurusan</span>
                <span class="text-gray-800 dark:text-white/90">{{ $fpk->kualifikasi_jurusan }}</span>
            </div>
            @endif
            <div class="flex gap-2 mb-4">
                <span class="font-medium text-gray-500 dark:text-gray-400 w-36 flex-shrink-0">Sumber Rekrutmen</span>
                <span class="text-gray-800 dark:text-white/90">{{ $fpk->sumber_rekrutmen }}</span>
            </div>

            @if($fpk->deskripsi_jabatan)
            <div class="mb-4 rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-800/60">
                <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi Jabatan</p>
                <div class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    {!! nl2br(e($fpk->deskripsi_jabatan)) !!}
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @if($fpk->tanggungjawab_jabatan && count($fpk->tanggungjawab_jabatan))
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-800/60">
                    <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggung Jawab</p>
                    <ul class="space-y-1.5">
                        @foreach($fpk->tanggungjawab_jabatan as $tj)
                        <li class="flex items-start gap-2 text-gray-600 dark:text-gray-400">
                            <span class="mt-1.5 h-1.5 w-1.5 rounded-full flex-shrink-0" style="background:#465fff;"></span>
                            {{ $tj }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($fpk->tugas && count($fpk->tugas))
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-800/60">
                    <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Tugas Pokok</p>
                    <ul class="space-y-1.5">
                        @foreach($fpk->tugas as $t)
                        <li class="flex items-start gap-2 text-gray-600 dark:text-gray-400">
                            <span class="mt-1.5 h-1.5 w-1.5 rounded-full flex-shrink-0" style="background:#12b76a;"></span>
                            {{ $t }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($fpk->hard_competency && count($fpk->hard_competency))
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-800/60">
                    <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Hard Competency</p>
                    <ul class="space-y-1.5">
                        @foreach($fpk->hard_competency as $hc)
                        <li class="flex items-start gap-2 text-gray-600 dark:text-gray-400">
                            <span class="mt-1.5 h-1.5 w-1.5 rounded-full flex-shrink-0" style="background:#f79009;"></span>
                            {{ $hc }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($fpk->soft_competency && count($fpk->soft_competency))
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-800/60">
                    <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Soft Competency</p>
                    <ul class="space-y-1.5">
                        @foreach($fpk->soft_competency as $sc)
                        <li class="flex items-start gap-2 text-gray-600 dark:text-gray-400">
                            <span class="mt-1.5 h-1.5 w-1.5 rounded-full flex-shrink-0" style="background:#7a5af8;"></span>
                            {{ $sc }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- CARD C: Approval Checklist --}}
    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-800 px-6 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl text-white text-theme-xs font-bold flex-shrink-0"
                 style="background:#7a5af8;">C</div>
            <h4 class="font-semibold text-gray-900 dark:text-white/90">Approval Checklist</h4>
        </div>
        <div class="p-6">
            @php
                $steps = [
                    ['label' => 'HR Verifier',  'at' => $fpk->approval_hrd_at,      'by' => $fpk->approvalHrdBy?->name],
                    ['label' => 'Finance',       'at' => $fpk->approval_finance_at,  'by' => $fpk->approvalFinanceBy?->name],
                    ['label' => 'HR Manager',    'at' => $fpk->approval_direktur_at, 'by' => $fpk->approvalDirekturBy?->name],
                ];
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($steps as $i => $step)
                <div class="relative flex flex-col items-center rounded-xl border p-5 text-center
                            {{ $step['at'] ? 'step-done' : 'step-pending' }}">
                    {{-- Step number --}}
                    <div class="mb-3 text-theme-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
                        Step {{ $i + 1 }}
                    </div>
                    {{-- Icon --}}
                    @if($step['at'])
                        <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-full"
                             style="background:#12b76a;">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    @else
                        <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-full border-2 border-dashed border-gray-300 dark:border-gray-600">
                            <span class="text-xl text-gray-300 dark:text-gray-600">○</span>
                        </div>
                    @endif
                    {{-- Label --}}
                    <p class="font-semibold text-theme-sm text-gray-800 dark:text-white/90 mb-1">{{ $step['label'] }}</p>
                    @if($step['at'])
                        <p class="text-theme-xs font-medium text-gray-700 dark:text-gray-300 line-clamp-1">{{ $step['by'] }}</p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                            {{ \Carbon\Carbon::parse($step['at'])->format('d/m/Y H:i') }}
                        </p>
                    @else
                        <p class="text-theme-xs text-gray-400 dark:text-gray-500 italic">Menunggu</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- CARD L: Approval Logs --}}
    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-800 px-6 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl text-white text-theme-xs font-bold flex-shrink-0"
                 style="background:#475467;">L</div>
            <h4 class="font-semibold text-gray-900 dark:text-white/90">Approval History Logs</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-theme-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
                        <th class="px-6 py-3 text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Waktu</th>
                        <th class="px-6 py-3 text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">User</th>
                        <th class="px-6 py-3 text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Aksi</th>
                        <th class="px-6 py-3 text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status Akhir</th>
                        <th class="px-6 py-3 text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($fpk->approvalLogs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="whitespace-nowrap px-6 py-4 text-gray-500 dark:text-gray-400">
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800 dark:text-white/90">
                            {{ $log->user?->name ?? 'System' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400 capitalize">
                            {{ str_replace('_', ' ', $log->action) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-brand-500">{{ $log->to_status }}</span>
                        </td>
                        <td class="px-6 py-4 italic text-gray-400 dark:text-gray-500">
                            {{ $log->notes ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-theme-sm text-gray-400 dark:text-gray-500 italic">
                            Belum ada riwayat log.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- CARD: Aksi Workflow --}}
    @php
        $userAuth = Auth::user();
        $isHR = false; $isHRManager = false; $isFinance = false;
        $karyawanData = \App\Models\Karyawan::where('user_id', $userAuth->id)->first()
                     ?: \App\Models\Karyawan::where('nik', $userAuth->nik)->first();
        if ($karyawanData) {
            $job = $karyawanData->pekerjaanTerkini()->first() ?? $karyawanData->pekerjaan()->first();
            if ($job) {
                $d = strtolower($job->department->name ?? '');
                $v = strtolower($job->division->name ?? '');
                $l = strtolower($job->level->name ?? '');
                if (str_contains($d, 'hr') || str_contains($v, 'hr')) {
                    $isHR = true;
                    if (str_contains($l, 'manager')) $isHRManager = true;
                }
                if (str_contains($d, 'finance') || str_contains($v, 'finance')) $isFinance = true;
            }
        }
        if ($userAuth->hasRole(['admin', 'superadmin'])) {
            $isHR = true; $isHRManager = true; $isFinance = true;
        }
    @endphp

    <div class="rounded-xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-900 p-6">
        <h4 class="text-theme-sm font-semibold text-gray-900 dark:text-white/90 mb-4">Aksi Workflow</h4>
        <div class="flex flex-wrap gap-3">

            @if(in_array($fpk->status_fpk, ['Draft', 'Revision Required']))
            <form action="{{ route('rekrutmen.fpk.submit', $fpk->id) }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-theme-sm font-bold transition shadow-theme-sm btn-submit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    {{ $fpk->status_fpk === 'Draft' ? 'Submit FPK' : 'Submit Ulang (Resubmit)' }}
                </button>
            </form>
            <a href="{{ route('rekrutmen.fpk.edit', $fpk->id) }}"
               class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-theme-sm font-bold transition shadow-theme-sm btn-neutral">
                Edit Form
            </a>
            @endif

            @if($fpk->status_fpk === 'Pending HR Admin' && $isHR)
            <form action="{{ route('rekrutmen.fpk.approveHrAdmin', $fpk->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Verifikasi & teruskan ke Finance?')"
                        class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-theme-sm font-bold transition shadow-theme-sm btn-approve">
                    Approve ke Finance
                </button>
            </form>
            <button type="button" onclick="openRevisionDialog()"
                    class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-theme-sm font-bold transition shadow-theme-sm btn-revision">
                Minta Revisi
            </button>
            <button type="button" onclick="openRejectDialog()"
                    class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-theme-sm font-bold transition shadow-theme-sm btn-reject">
                Reject
            </button>
            @endif

            @if($fpk->status_fpk === 'Pending Finance Approval' && $isFinance)
            <form action="{{ route('rekrutmen.fpk.approveFinance', $fpk->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Approve & teruskan ke HR Manager?')"
                        class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-theme-sm font-bold transition shadow-theme-sm btn-approve">
                    Approve (Finance)
                </button>
            </form>
            <button type="button" onclick="openRejectDialog()"
                    class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-theme-sm font-bold transition shadow-theme-sm btn-reject">
                Reject
            </button>
            @endif

            @if($fpk->status_fpk === 'Reviewing by HR Manager' && $isHRManager)
            <form action="{{ route('rekrutmen.fpk.approveHrManager', $fpk->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Final Approve FPK ini?')"
                        class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-theme-sm font-bold transition shadow-theme-sm btn-approve">
                    Final Approve FPK
                </button>
            </form>
            <button type="button" onclick="openRejectDialog()"
                    class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-theme-sm font-bold transition shadow-theme-sm btn-reject">
                Reject
            </button>
            @endif

        </div>
    </div>

    {{-- Modal --}}
    <div id="actionModal"
         class="fixed inset-0 z-99999 hidden items-center justify-center bg-gray-900/70 backdrop-blur-sm px-4"
         onclick="if(event.target===this) closeModal()">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-theme-xl dark:bg-gray-900 dark:border dark:border-gray-800">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-900 dark:text-white/90">Action</h3>
            </div>
            <div class="px-6 py-5">
                <form id="actionForm" method="POST">
                    @csrf
                    <label class="block text-theme-sm font-medium text-gray-700 dark:text-gray-300 mb-2" id="modalLabel">Catatan</label>
                    <textarea id="modalTextarea" name="" rows="4" required
                              class="w-full rounded-xl border border-gray-300 bg-transparent px-4 py-3 text-theme-sm text-gray-800
                                     dark:border-gray-700 dark:bg-gray-800 dark:text-white/90
                                     focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10
                                     placeholder:text-gray-400 dark:placeholder:text-gray-500
                                     resize-none"></textarea>
                </form>
            </div>
            <div class="flex justify-end gap-3 border-t border-gray-200 dark:border-gray-800 px-6 py-4">
                <button type="button" onclick="closeModal()"
                        class="rounded-lg px-5 py-2 text-theme-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.05] transition">
                    Batal
                </button>
                <button type="submit" form="actionForm" id="modalSubmitBtn"
                        class="rounded-lg px-5 py-2 text-theme-sm font-bold text-white transition shadow-theme-sm">
                    Submit
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    const modal = document.getElementById('actionModal');

    function openRevisionDialog() {
        document.getElementById('modalTitle').innerText    = 'Permintaan Revisi FPK';
        document.getElementById('modalLabel').innerText    = 'Bagian yang perlu diperbaiki';
        document.getElementById('modalTextarea').name        = 'revision_comment';
        document.getElementById('modalTextarea').placeholder = 'Tuliskan bagian mana yang perlu diperbaiki oleh Pengaju...';
        const btn = document.getElementById('modalSubmitBtn');
        btn.innerText = 'Kirim Revisi';
        btn.style.background = '#f79009';
        document.getElementById('actionForm').action = "{{ route('rekrutmen.fpk.requestRevision', $fpk->id) }}";
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openRejectDialog() {
        document.getElementById('modalTitle').innerText    = 'Penolakan FPK';
        document.getElementById('modalLabel').innerText    = 'Alasan penolakan';
        document.getElementById('modalTextarea').name        = 'alasan_reject';
        document.getElementById('modalTextarea').placeholder = 'Tuliskan alasan penolakan FPK ini...';
        const btn = document.getElementById('modalSubmitBtn');
        btn.innerText = 'Reject FPK';
        btn.style.background = '#f04438';
        document.getElementById('actionForm').action = "{{ route('rekrutmen.fpk.reject', $fpk->id) }}";
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
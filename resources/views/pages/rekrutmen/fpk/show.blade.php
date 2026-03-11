@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl p-4 md:p-6 2xl:p-10">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Detail FPK</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Nomor: <strong class="text-blue-600 dark:text-blue-400">{{ $fpk->nomor_fpk }}</strong></p>
        </div>
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.05] transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-900 dark:bg-green-900/20 dark:text-green-400">
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-900 dark:bg-red-900/20 dark:text-red-400">
        @foreach($errors->all() as $err) <p>{{ $err }}</p> @endforeach
    </div>
    @endif

    <!-- Status Badge -->
    <div class="mb-6 flex flex-wrap items-center gap-3">
        @if($fpk->status_fpk == 'Approved')
            <span class="inline-flex items-center rounded-full bg-green-100 px-4 py-1.5 text-sm font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">
                <span class="mr-2 h-2 w-2 rounded-full bg-green-500"></span> Approved
            </span>
        @elseif($fpk->status_fpk == 'Rejected')
            <span class="inline-flex items-center rounded-full bg-red-100 px-4 py-1.5 text-sm font-medium text-red-700 dark:bg-red-900/20 dark:text-red-400">
                <span class="mr-2 h-2 w-2 rounded-full bg-red-500"></span> Rejected
            </span>
        @elseif($fpk->status_fpk == 'Reviewing by HR Manager')
            <span class="inline-flex items-center rounded-full bg-blue-100 px-4 py-1.5 text-sm font-medium text-blue-700 dark:bg-blue-900/20 dark:text-blue-400">
                <span class="mr-2 h-2 w-2 rounded-full bg-blue-500"></span> Reviewing by HR Manager
            </span>
        @elseif($fpk->status_fpk == 'Pending HR Admin')
            <span class="inline-flex items-center rounded-full bg-yellow-100 px-4 py-1.5 text-sm font-medium text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400">
                <span class="mr-2 h-2 w-2 rounded-full bg-yellow-500"></span> Pending HR Admin
            </span>
        @else
            <span class="inline-flex items-center rounded-full bg-gray-100 px-4 py-1.5 text-sm font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                {{ $fpk->status_fpk }}
            </span>
        @endif

        @if($fpk->status_fpk === 'Rejected' && $fpk->alasan_reject)
            <span class="text-sm text-red-600 dark:text-red-400"><strong>Alasan:</strong> {{ $fpk->alasan_reject }}</span>
        @endif
    </div>

    <!-- CARD: Informasi Umum -->
    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white text-sm font-bold flex-shrink-0">A</div>
            <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Posisi</h4>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="space-y-3">
                    <div class="flex"><span class="w-40 font-medium text-gray-500 dark:text-gray-400">Divisi</span><span class="text-gray-900 dark:text-white">{{ $fpk->division?->name ?? '-' }}</span></div>
                    <div class="flex"><span class="w-40 font-medium text-gray-500 dark:text-gray-400">Departemen</span><span class="text-gray-900 dark:text-white">{{ $fpk->department?->name ?? '-' }}</span></div>
                    <div class="flex"><span class="w-40 font-medium text-gray-500 dark:text-gray-400">Nama Jabatan</span><span class="text-gray-900 dark:text-white font-semibold">{{ $fpk->nama_jabatan }}</span></div>
                    <div class="flex"><span class="w-40 font-medium text-gray-500 dark:text-gray-400">Level</span><span class="text-gray-900 dark:text-white">{{ $fpk->level }}</span></div>
                    <div class="flex"><span class="w-40 font-medium text-gray-500 dark:text-gray-400">Lokasi Kerja</span><span class="text-gray-900 dark:text-white">{{ $fpk->lokasi_kerja }}</span></div>
                </div>
                <div class="space-y-3">
                    <div class="flex"><span class="w-40 font-medium text-gray-500 dark:text-gray-400">Tgl Mulai Kerja</span><span class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($fpk->tanggal_mulai_bekerja)->format('d F Y') }}</span></div>
                    <div class="flex"><span class="w-40 font-medium text-gray-500 dark:text-gray-400">Jumlah</span><span class="text-gray-900 dark:text-white font-semibold">{{ $fpk->jumlah_kebutuhan }} Orang</span></div>
                    <div class="flex"><span class="w-40 font-medium text-gray-500 dark:text-gray-400">Grade</span><span class="text-gray-900 dark:text-white">{{ $fpk->grade ?? '-' }}</span></div>
                    <div class="flex"><span class="w-40 font-medium text-gray-500 dark:text-gray-400">Alasan</span><span class="text-gray-900 dark:text-white">{{ $fpk->alasan_permintaan }}</span></div>
                    @if($fpk->alasan_permintaan == 'Penggantian Karyawan')
                    <div class="flex"><span class="w-40 font-medium text-gray-500 dark:text-gray-400">Pengganti</span><span class="text-red-600 dark:text-red-400">{{ $fpk->nama_karyawan_pengganti ?? '-' }}</span></div>
                    @endif
                    @if($fpk->jangka_waktu_kontrak)
                    <div class="flex"><span class="w-40 font-medium text-gray-500 dark:text-gray-400">Lama Kontrak</span><span class="text-gray-900 dark:text-white">{{ $fpk->jangka_waktu_kontrak }}</span></div>
                    @endif
                </div>
            </div>
            @if($fpk->dampak_kekurangan_posisi)
            <div class="mt-4 rounded-lg bg-yellow-50 p-3 dark:bg-yellow-900/20">
                <p class="text-xs font-medium text-yellow-600 dark:text-yellow-400 mb-1">Dampak Kekosongan Posisi:</p>
                <p class="text-sm text-yellow-800 dark:text-yellow-300">{{ $fpk->dampak_kekurangan_posisi }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- CARD: Spesifikasi Pekerjaan -->
    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-600 text-white text-sm font-bold flex-shrink-0">B</div>
            <h4 class="font-semibold text-gray-900 dark:text-white">Kualifikasi & Spesifikasi</h4>
        </div>
        <div class="p-6 text-sm">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $fpk->kualifikasi_jk ?? 'Bebas' }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Usia</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $fpk->kualifikasi_usia ?? 'Bebas' }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pendidikan</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $fpk->kualifikasi_pendidikan ?? 'Bebas' }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pengalaman</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $fpk->kualifikasi_pengalaman ?? 'Bebas' }}</p>
                </div>
            </div>
            @if($fpk->kualifikasi_jurusan)
            <p class="mb-2"><span class="font-medium text-gray-500 dark:text-gray-400">Jurusan:</span> <span class="text-gray-900 dark:text-white">{{ $fpk->kualifikasi_jurusan }}</span></p>
            @endif
            <p class="mb-2"><span class="font-medium text-gray-500 dark:text-gray-400">Sumber Rekrutmen:</span> <span class="text-gray-900 dark:text-white">{{ $fpk->sumber_rekrutmen }}</span></p>

            @if($fpk->deskripsi_jabatan)
            <div class="mt-3">
                <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Deskripsi Jabatan:</p>
                <p class="text-gray-900 dark:text-white">{{ $fpk->deskripsi_jabatan }}</p>
            </div>
            @endif

            @if($fpk->tanggungjawab_jabatan && count($fpk->tanggungjawab_jabatan))
            <div class="mt-3">
                <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggung Jawab:</p>
                <ol class="list-decimal ml-5 text-gray-900 dark:text-white space-y-0.5">
                    @foreach($fpk->tanggungjawab_jabatan as $tj) <li>{{ $tj }}</li> @endforeach
                </ol>
            </div>
            @endif

            @if($fpk->tugas && count($fpk->tugas))
            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Tugas:</p>
                    <ol class="list-decimal ml-5 text-gray-900 dark:text-white space-y-0.5">
                        @foreach($fpk->tugas as $t) <li>{{ $t }}</li> @endforeach
                    </ol>
                </div>
                @if($fpk->tolak_ukur_keberhasilan && count($fpk->tolak_ukur_keberhasilan))
                <div>
                    <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Tolok Ukur Keberhasilan:</p>
                    <ol class="list-decimal ml-5 text-gray-900 dark:text-white space-y-0.5">
                        @foreach($fpk->tolak_ukur_keberhasilan as $tk) <li>{{ $tk }}</li> @endforeach
                    </ol>
                </div>
                @endif
            </div>
            @endif

            @if(($fpk->hard_competency && count($fpk->hard_competency)) || ($fpk->soft_competency && count($fpk->soft_competency)))
            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($fpk->hard_competency && count($fpk->hard_competency))
                <div>
                    <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Hard Competency:</p>
                    <ul class="list-disc ml-5 text-gray-900 dark:text-white space-y-0.5">
                        @foreach($fpk->hard_competency as $hc) <li>{{ $hc }}</li> @endforeach
                    </ul>
                </div>
                @endif
                @if($fpk->soft_competency && count($fpk->soft_competency))
                <div>
                    <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Soft Competency:</p>
                    <ul class="list-disc ml-5 text-gray-900 dark:text-white space-y-0.5">
                        @foreach($fpk->soft_competency as $sc) <li>{{ $sc }}</li> @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endif

            @if($fpk->test_dibutuhkan && count($fpk->test_dibutuhkan))
            <div class="mt-3 flex flex-wrap gap-2">
                <span class="font-medium text-gray-500 dark:text-gray-400">Test:</span>
                @foreach($fpk->test_dibutuhkan as $td)
                <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/20 dark:text-blue-400">{{ $td }}</span>
                @endforeach
            </div>
            @endif

            @if($fpk->sarana_prasarana && count($fpk->sarana_prasarana))
            <div class="mt-2 flex flex-wrap gap-2">
                <span class="font-medium text-gray-500 dark:text-gray-400">Sarana:</span>
                @foreach($fpk->sarana_prasarana as $sp)
                <span class="inline-flex rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">{{ $sp }}</span>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- CARD: Tanda Tangan Persetujuan -->
    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-600 text-white text-sm font-bold flex-shrink-0">C</div>
            <h4 class="font-semibold text-gray-900 dark:text-white">Tanda Tangan Persetujuan</h4>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                @php
                    $approvals = [
                        ['label' => 'Departement', 'by' => $fpk->approvalDepartemenBy, 'at' => $fpk->approval_departemen_at],
                        ['label' => 'Divisi', 'by' => $fpk->approvalDivisiBy, 'at' => $fpk->approval_divisi_at],
                        ['label' => 'HRD', 'by' => $fpk->approvalHrdBy, 'at' => $fpk->approval_hrd_at],
                        ['label' => 'Finance', 'by' => $fpk->approvalFinanceBy, 'at' => $fpk->approval_finance_at],
                        ['label' => 'Direktur', 'by' => $fpk->approvalDirekturBy, 'at' => $fpk->approval_direktur_at],
                    ];
                @endphp
                @foreach($approvals as $ap)
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 {{ $ap['by'] ? 'bg-green-50 border-green-200 dark:bg-green-900/10 dark:border-green-800' : '' }}">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-6">{{ $ap['label'] }}</p>
                    @if($ap['by'])
                        <svg class="mx-auto mb-1 w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    @endif
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $ap['by']?->name ?? '........' }}</p>
                    <p class="text-xs text-gray-400 border-t border-gray-200 dark:border-gray-700 mt-2 pt-1">
                        {{ $ap['at'] ? \Carbon\Carbon::parse($ap['at'])->format('d/m/Y') : 'Tgl: ....' }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- TOMBOL AKSI -->
    @php
        $userAuth = Auth::user();
        $isHR = false;
        $isHRManager = false;
        $isManagerOrLeader = false;

        $karyawanData = \App\Models\Karyawan::where('user_id', $userAuth->id)->first();
        if (!$karyawanData && !empty($userAuth->nik)) {
            $karyawanData = \App\Models\Karyawan::where('nik', $userAuth->nik)->first();
        }

        if ($karyawanData) {
            $pekerjaanData = $karyawanData->pekerjaanTerkini()->first() ?? $karyawanData->pekerjaan()->first();
            if ($pekerjaanData) {
                $dn = strtolower($pekerjaanData->department->name ?? '');
                $vn = strtolower($pekerjaanData->division->name ?? '');
                $ln = strtolower($pekerjaanData->level->name ?? '');

                if (str_contains($dn, 'hr') || str_contains($dn, 'human') || str_contains($vn, 'hr') || str_contains($vn, 'human')) {
                    $isHR = true; // Semua staf HR bisa Forward
                    // HR Manager = level manager/supervisor/senior di divisi HR
                    if (str_contains($ln, 'manager') || str_contains($ln, 'supervisor') || str_contains($ln, 'gm') || str_contains($ln, 'direktur')) {
                        $isHRManager = true;
                    }
                }

                // Cek apakah manager/leader (untuk approval departemen/divisi)
                if (str_contains($ln, 'manager') || str_contains($ln, 'supervisor') || str_contains($ln, 'gm') || str_contains($ln, 'direktur')) {
                    $isManagerOrLeader = true;
                }
            }
        }

        // Admin/Superadmin bisa segalanya
        if ($userAuth->hasRole(['admin', 'superadmin'])) {
            $isHR = true;
            $isHRManager = true;
            $isManagerOrLeader = true;
        }
    @endphp

    @if($fpk->status_fpk !== 'Approved' && $fpk->status_fpk !== 'Rejected')
    <div class="rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Aksi</h4>
        <div class="flex flex-wrap gap-3">

            {{-- HR Admin: Forward ke HR Manager --}}
            @if($fpk->status_fpk === 'Pending HR Admin' && $isHR)
            <form action="{{ route('rekrutmen.fpk.forward', $fpk->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Forward FPK ini ke HR Manager?')"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    Forward ke HR Manager
                </button>
            </form>
            @endif

            {{-- HR Manager (atasan HR): Approve & Reject --}}
            @if($fpk->status_fpk === 'Reviewing by HR Manager' && $isHRManager)
            <form action="{{ route('rekrutmen.fpk.approve', $fpk->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Setujui FPK ini?')"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Approve FPK
                </button>
            </form>
            <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reject
            </button>
            @endif

            {{-- Manager non-HR: Approve di level departemen --}}
            @if($isManagerOrLeader && !$isHR && !$fpk->approval_departemen_by && in_array($fpk->status_fpk, ['Approved by HRD', 'Reviewing by HR Manager']))
            <form action="{{ route('rekrutmen.fpk.approve', $fpk->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Setujui FPK ini sebagai Manager Departemen?')"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Approve (Departemen)
                </button>
            </form>
            @endif

            {{-- Direktur / Superadmin: Final Approval --}}
            @if(in_array($fpk->status_fpk, ['Approved by HRD', 'Pending Approval', 'Reviewing by HR Manager']) && $userAuth->hasRole(['direktur', 'superadmin']))
            <form action="{{ route('rekrutmen.fpk.approve', $fpk->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Final Approval untuk FPK ini?')"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Final Approve
                </button>
            </form>
            @endif
        </div>
    </div>
    @endif

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl dark:bg-gray-800">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Alasan Penolakan FPK</h3>
                    <form action="{{ route('rekrutmen.fpk.reject', $fpk->id) }}" method="POST" id="rejectForm">
                        @csrf
                        <textarea name="alasan_reject" rows="4" required placeholder="Tuliskan alasan mengapa FPK ini ditolak..."
                                  class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
                    </form>
                </div>
                <div class="flex justify-end gap-3 border-t border-gray-200 dark:border-gray-700 px-6 py-4">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button type="submit" form="rejectForm"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Submit Reject
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

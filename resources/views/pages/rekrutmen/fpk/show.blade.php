@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl p-4 md:p-6 2xl:p-10">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Detail FPK</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Nomor: <strong class="text-blue-600 dark:text-blue-400">{{ $fpk->nomor_fpk }}</strong></p>
        </div>
        <a href="{{ route('rekrutmen.fpk.index') }}"
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
        @php
            $statusColors = [
                'Draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                'Pending HR Admin' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                'Pending Finance Approval' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/20 dark:text-purple-400',
                'Reviewing by HR Manager' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400',
                'Revision Required' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/20 dark:text-orange-400',
                'Approved' => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                'Rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
            ];
            $color = $statusColors[$fpk->status_fpk] ?? 'bg-gray-100 text-gray-700';
        @endphp
        <span class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-medium {{ $color }}">
            {{ $fpk->status_fpk }}
        </span>

        @if($fpk->status_fpk === 'Rejected' && $fpk->alasan_reject)
            <div class="w-full mt-2 rounded-lg bg-red-50 p-3 border border-red-100 dark:bg-red-900/10 dark:border-red-800/50">
                <p class="text-sm text-red-700 dark:text-red-400"><strong class="font-bold">Alasan Penolakan:</strong> {{ $fpk->alasan_reject }}</p>
            </div>
        @endif

        @if($fpk->status_fpk === 'Revision Required' && $fpk->revision_comment)
            <div class="w-full mt-2 rounded-lg bg-orange-50 p-3 border border-orange-100 dark:bg-orange-900/10 dark:border-orange-800/50">
                <p class="text-sm text-orange-700 dark:text-orange-400"><strong class="font-bold">Catatan Revisi:</strong> {{ $fpk->revision_comment }}</p>
            </div>
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
                <div class="prose prose-sm dark:prose-invert max-w-none text-gray-900 dark:text-white">
                    {!! nl2br(e($fpk->deskripsi_jabatan)) !!}
                </div>
            </div>
            @endif

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($fpk->tanggungjawab_jabatan && count($fpk->tanggungjawab_jabatan))
                <div class="space-y-2">
                    <p class="font-bold text-gray-700 dark:text-gray-300">Tanggung Jawab:</p>
                    <ul class="list-inside list-disc space-y-1 text-gray-600 dark:text-gray-400">
                        @foreach($fpk->tanggungjawab_jabatan as $tj) <li>{{ $tj }}</li> @endforeach
                    </ul>
                </div>
                @endif

                @if($fpk->tugas && count($fpk->tugas))
                <div class="space-y-2">
                    <p class="font-bold text-gray-700 dark:text-gray-300">Tugas Pokok:</p>
                    <ul class="list-inside list-disc space-y-1 text-gray-600 dark:text-gray-400">
                        @foreach($fpk->tugas as $t) <li>{{ $t }}</li> @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($fpk->hard_competency && count($fpk->hard_competency))
                <div class="space-y-2">
                    <p class="font-bold text-gray-700 dark:text-gray-300">Hard Competency:</p>
                    <ul class="list-inside list-disc space-y-1 text-gray-600 dark:text-gray-400">
                        @foreach($fpk->hard_competency as $hc) <li>{{ $hc }}</li> @endforeach
                    </ul>
                </div>
                @endif

                @if($fpk->soft_competency && count($fpk->soft_competency))
                <div class="space-y-2">
                    <p class="font-bold text-gray-700 dark:text-gray-300">Soft Competency:</p>
                    <ul class="list-inside list-disc space-y-1 text-gray-600 dark:text-gray-400">
                        @foreach($fpk->soft_competency as $sc) <li>{{ $sc }}</li> @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- CARD: Approval Progress (Visual) -->
    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-600 text-white text-sm font-bold flex-shrink-0">C</div>
            <h4 class="font-semibold text-gray-900 dark:text-white">Approval Checklist</h4>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @php
                    $steps = [
                        ['label' => 'HR Verifier', 'at' => $fpk->approval_hrd_at, 'by' => $fpk->approvalHrdBy?->name],
                        ['label' => 'Finance', 'at' => $fpk->approval_finance_at, 'by' => $fpk->approvalFinanceBy?->name],
                        ['label' => 'HR Manager', 'at' => $fpk->approval_direktur_at, 'by' => $fpk->approvalDirekturBy?->name], // Final approve by HR manager in this system logic
                    ];
                @endphp
                @foreach($steps as $step)
                <div class="relative flex flex-col items-center p-4 rounded-xl border {{ $step['at'] ? 'bg-green-50 border-green-200 dark:bg-green-900/10 dark:border-green-800' : 'bg-gray-50 border-gray-100 dark:bg-gray-800/50 dark:border-gray-700' }}">
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2">{{ $step['label'] }}</p>
                    @if($step['at'])
                        <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $step['by'] }}</p>
                        <p class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($step['at'])->format('d/m/Y H:i') }}</p>
                    @else
                        <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-400">
                             <span class="text-lg">○</span>
                        </div>
                        <p class="text-xs text-gray-400 italic">Pending</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CARD: Approval Logs -->
    <div class="mb-5 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-600 text-white text-sm font-bold flex-shrink-0">L</div>
            <h4 class="font-semibold text-gray-900 dark:text-white">Approval History Logs</h4>
        </div>
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">Waktu</th>
                            <th class="px-6 py-3 font-medium">User</th>
                            <th class="px-6 py-3 font-medium">Aksi</th>
                            <th class="px-6 py-3 font-medium">Status Akhir</th>
                            <th class="px-6 py-3 font-medium">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($fpk->approvalLogs as $log)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-400">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $log->user?->name ?? 'System' }}</td>
                            <td class="px-6 py-4">
                                <span class="capitalize">{{ str_replace('_', ' ', $log->action) }}</span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-blue-600 dark:text-blue-400">{{ $log->to_status }}</td>
                            <td class="px-6 py-4 italic text-gray-500">{{ $log->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">Belum ada riwayat log.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TOMBOL AKSI BERDASARKAN WORKFLOW JSON -->
    @php
        $userAuth = Auth::user();
        $isHR = false;
        $isHRManager = false;
        $isFinance = false;
        
        $karyawanData = \App\Models\Karyawan::where('user_id', $userAuth->id)->first() ?: \App\Models\Karyawan::where('nik', $userAuth->nik)->first();
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
                if (str_contains($d, 'finance') || str_contains($v, 'finance')) {
                    $isFinance = true;
                }
            }
        }
        if ($userAuth->hasRole(['admin', 'superadmin'])) {
            $isHR = true; $isHRManager = true; $isFinance = true;
        }
    @endphp

    <div class="rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-white/[0.03] p-6">
        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Aksi Workflow</h4>
        <div class="flex flex-wrap gap-3">

            {{-- 1. Requester: Submit if Draft/Revision --}}
            @if(in_array($fpk->status_fpk, ['Draft', 'Revision Required']))
                <form action="{{ route('rekrutmen.fpk.submit', $fpk->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-blue-700 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        {{ $fpk->status_fpk === 'Draft' ? 'Submit FPK' : 'Submit Ulang (Resubmit)' }}
                    </button>
                </form>
                <a href="{{ route('rekrutmen.fpk.edit', $fpk->id) }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-500 px-6 py-2.5 text-sm font-bold text-white hover:bg-gray-600 transition shadow-md">
                    Edit Form
                </a>
            @endif

            {{-- 2. HR Admin Actions --}}
            @if($fpk->status_fpk === 'Pending HR Admin' && $isHR)
                <form action="{{ route('rekrutmen.fpk.approveHrAdmin', $fpk->id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Verifikasi & teruskan ke Finance?')" 
                            class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-green-700 transition shadow-md">
                        Approve ke Finance
                    </button>
                </form>
                <button type="button" onclick="openRevisionDialog()" 
                        class="inline-flex items-center gap-2 rounded-lg bg-yellow-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-yellow-700 transition shadow-md">
                    Minta Revisi
                </button>
                <button type="button" onclick="openRejectDialog()" 
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-red-700 transition shadow-md">
                    Reject
                </button>
            @endif

            {{-- 3. Finance Actions --}}
            @if($fpk->status_fpk === 'Pending Finance Approval' && $isFinance)
                <form action="{{ route('rekrutmen.fpk.approveFinance', $fpk->id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Approve & teruskan ke HR Manager?')" 
                            class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-green-700 transition shadow-md">
                        Approve (Finance)
                    </button>
                </form>
                <button type="button" onclick="openRejectDialog()" 
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-red-700 transition shadow-md">
                    Reject
                </button>
            @endif

            {{-- 4. HR Manager Actions --}}
            @if($fpk->status_fpk === 'Reviewing by HR Manager' && $isHRManager)
                <form action="{{ route('rekrutmen.fpk.approveHrManager', $fpk->id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Final Approve FPK ini?')" 
                            class="inline-flex items-center gap-2 rounded-lg bg-green-700 px-6 py-2.5 text-sm font-bold text-white hover:bg-green-800 transition shadow-md">
                        Final Approve FPK
                    </button>
                </form>
                <button type="button" onclick="openRejectDialog()" 
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-red-700 transition shadow-md">
                    Reject
                </button>
            @endif
        </div>
    </div>

    <!-- Modals -->
    <div id="actionModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm transition-opacity">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl dark:bg-gray-800">
                <div class="p-6">
                    <h3 id="modalTitle" class="text-xl font-bold text-gray-900 dark:text-white mb-4">Action</h3>
                    <form id="actionForm" method="POST">
                        @csrf
                        <textarea id="modalTextarea" name="" rows="4" required class="w-full rounded-xl border border-gray-300 bg-transparent px-4 py-3 text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10"></textarea>
                    </form>
                </div>
                <div class="flex justify-end gap-3 border-t border-gray-200 dark:border-gray-700 px-6 py-4">
                    <button type="button" onclick="closeModal()" class="rounded-lg px-6 py-2 font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">Batal</button>
                    <button type="submit" form="actionForm" id="modalSubmitBtn" class="rounded-lg px-6 py-2 font-bold text-white transition shadow-lg">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openRevisionDialog() {
            const modal = document.getElementById('actionModal');
            const title = document.getElementById('modalTitle');
            const area = document.getElementById('modalTextarea');
            const btn = document.getElementById('modalSubmitBtn');
            const form = document.getElementById('actionForm');

            title.innerText = 'Permintaan Revisi FPK';
            area.name = 'revision_comment';
            area.placeholder = 'Tuliskan bagian mana yang perlu diperbaiki oleh Pengaju...';
            btn.innerText = 'Kirim Revisi';
            btn.className = 'rounded-lg px-6 py-2 font-bold text-white bg-yellow-600 hover:bg-yellow-700 shadow-lg';
            form.action = "{{ route('rekrutmen.fpk.requestRevision', $fpk->id) }}";
            modal.classList.remove('hidden');
        }

        function openRejectDialog() {
            const modal = document.getElementById('actionModal');
            const title = document.getElementById('modalTitle');
            const area = document.getElementById('modalTextarea');
            const btn = document.getElementById('modalSubmitBtn');
            const form = document.getElementById('actionForm');

            title.innerText = 'Penolakan FPK (Rejected)';
            area.name = 'alasan_reject';
            area.placeholder = 'Tuliskan alasan penolakan FPK ini...';
            btn.innerText = 'Reject FPK';
            btn.className = 'rounded-lg px-6 py-2 font-bold text-white bg-red-600 hover:bg-red-700 shadow-lg';
            form.action = "{{ route('rekrutmen.fpk.reject', $fpk->id) }}";
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('actionModal').classList.add('hidden');
        }
    </script>

</div>
@endsection

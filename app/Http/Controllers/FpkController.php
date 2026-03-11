<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fpk;
use App\Models\Division;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class FpkController extends Controller
{
    public function __construct()
    {
        // 1. Dapatkan user admin/superadmin (langsung lolos)
        $this->middleware(\Closure::fromCallable(function ($request, $next) {
            $user = Auth::user();
            if ($user && $user->hasRole(['admin', 'superadmin'])) {
                return $next($request);
            }

            // 2. Jika bukan admin, cek apakah dia manajer atau punya divisi HR
            $isHR = false;
            $isManagerOrLeader = false;
            
            $karyawan = \App\Models\Karyawan::where('user_id', $user->id)->first();
            if (!$karyawan && !empty($user->nik)) {
                $karyawan = \App\Models\Karyawan::where('nik', $user->nik)->first();
            }

            if ($karyawan) {
                $pekerjaan = $karyawan->pekerjaanTerkini()->first() ?? $karyawan->pekerjaan()->first();
                if ($pekerjaan) {
                    $levelName = strtolower($pekerjaan->level->name ?? '');
                    $posName   = strtolower($pekerjaan->position->name ?? '');
                    $jabatan   = strtolower($pekerjaan->Jabatan ?? '');

                    $derivedRoles = [$levelName, $posName, $jabatan];

                    foreach ($derivedRoles as $dRole) {
                        $r = strtolower($dRole);
                        if (str_contains($r, 'manager') || str_contains($r, 'supervisor') || str_contains($r, 'gm') || str_contains($r, 'direktur')) {
                            $isManagerOrLeader = true;
                            break;
                        }
                    }

                    $deptName = strtolower($pekerjaan->department->name ?? '');
                    $divName  = strtolower($pekerjaan->division->name ?? '');
                    if (str_contains($deptName, 'hr') || str_contains($deptName, 'human') || str_contains($divName, 'hr') || str_contains($divName, 'human resource')) {
                        $isHR = true;
                    }
                }
            }

            // Izinkan jika HR atau Manajer
            if ($isHR || $isManagerOrLeader) {
                return $next($request);
            }

            // Jika tidak memenuhi kriteria di atas, redirect / error
            abort(403, 'Akses Ditolak. Anda bukan bagian dari Manajemen atau Tim HR.');
        }));
    }
    public function index()
    {
        // Akan menampilkan daftar FPK (buat role manager khusus miliknya, GM bisa lihat semua dll)
        $fpk = Fpk::with('division', 'department')->orderBy('created_at', 'desc')->paginate(10);
        return view('pages.rekrutmen.fpk.index', compact('fpk'));
    }

    public function create()
    {
        $divisions = Division::all();
        $departments = Department::all();
        return view('pages.rekrutmen.fpk.create', compact('divisions', 'departments'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_jabatan'          => 'required|string|max:255',
            'jumlah_kebutuhan'      => 'required|integer|min:1',
            'tanggal_mulai_bekerja' => 'required|date',
            'level'                 => 'required|string',
            'alasan_permintaan'     => 'required|string',
            'sumber_rekrutmen'      => 'required|string',
            'lokasi_kerja'          => 'required|string|max:255',
        ]);

        // Generate Nomor FPK unik (misal: FPK/2026/03/001)
        $bulan = date('m');
        $tahun = date('Y');
        $count = Fpk::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->count() + 1;
        $nomorUrut = str_pad($count, 3, "0", STR_PAD_LEFT);

        // Bersihkan array yang kosong sebelum dimasukkan ke DB
        $filterArray = fn($arr) => !empty($arr) ? array_values(array_filter($arr, fn($v) => trim($v) !== '')) : null;

        Fpk::create([
            'nomor_fpk'                => "FPK/$tahun/$bulan/$nomorUrut",
            'division_id'              => $request->division_id ?: null,
            'department_id'            => $request->department_id ?: null,
            'nama_jabatan'             => $request->nama_jabatan,
            'grade'                    => $request->grade ?: null,
            'perkiraan_gaji'           => $request->perkiraan_gaji ?: null,
            'tanggal_mulai_bekerja'    => $request->tanggal_mulai_bekerja,
            'jumlah_kebutuhan'         => $request->jumlah_kebutuhan,
            'lokasi_kerja'             => $request->lokasi_kerja,
            'level'                    => $request->level,
            'alasan_permintaan'        => $request->alasan_permintaan,
            'nama_karyawan_pengganti'  => $request->nama_karyawan_pengganti ?: null,
            'jangka_waktu_kontrak'     => $request->jangka_waktu_kontrak ?: null,
            'dampak_kekurangan_posisi' => $request->dampak_kekurangan_posisi ?: null,
            'sumber_rekrutmen'         => $request->sumber_rekrutmen,
            'catatan_khusus'           => $request->catatan_khusus ?: null,
            'deskripsi_jabatan'        => $request->deskripsi_jabatan ?: null,
            'tanggungjawab_jabatan'    => $filterArray($request->tanggungjawab_jabatan),
            'tugas'                    => $filterArray($request->tugas),
            'tolak_ukur_keberhasilan'  => $filterArray($request->tolak_ukur_keberhasilan),
            'kualifikasi_jk'           => $request->kualifikasi_jk ?? 'Bebas',
            'kualifikasi_usia'         => $request->kualifikasi_usia ?: null,
            'kualifikasi_pendidikan'   => $request->kualifikasi_pendidikan ?: null,
            'kualifikasi_jurusan'      => $request->kualifikasi_jurusan ?: null,
            'kualifikasi_pengalaman'   => $request->kualifikasi_pengalaman ?: null,
            'hard_competency'          => $filterArray($request->hard_competency),
            'soft_competency'          => $filterArray($request->soft_competency),
            'test_dibutuhkan'          => $request->test_dibutuhkan ?: null,
            'sarana_prasarana'         => $request->sarana_prasarana ?: null,
            'status_fpk'               => 'Pending HR Admin',
        ]);

        return redirect()->route('rekrutmen.fpk.history')->with('success', 'Form Permintaan Karyawan berhasil diajukan dan sedang direviu oleh HR Admin.');
    }

    public function show($id)
    {
        $fpk = Fpk::with(['division', 'department', 'approvalDepartemenBy', 'approvalDivisiBy', 'approvalHrdBy', 'approvalFinanceBy', 'approvalDirekturBy'])->findOrFail($id);
        return view('pages.rekrutmen.fpk.show', compact('fpk'));
    }

    public function edit($id)
    {
        $fpk = Fpk::findOrFail($id);
        $divisions = Division::all();
        $departments = Department::all();
        return view('pages.rekrutmen.fpk.edit', compact('fpk', 'divisions', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $fpk = Fpk::findOrFail($id);
        
        $request->validate([
            'nama_jabatan'          => 'required|string|max:255',
            'jumlah_kebutuhan'      => 'required|integer|min:1',
            'tanggal_mulai_bekerja' => 'required|date',
            'level'                 => 'required|string',
            'alasan_permintaan'     => 'required|string',
            'sumber_rekrutmen'      => 'required|string',
            'lokasi_kerja'          => 'required|string|max:255',
        ]);

        $filterArray = fn($arr) => !empty($arr) ? array_values(array_filter($arr, fn($v) => trim($v) !== '')) : null;

        $fpk->update([
            'division_id'              => $request->division_id ?: null,
            'department_id'            => $request->department_id ?: null,
            'nama_jabatan'             => $request->nama_jabatan,
            'grade'                    => $request->grade ?: null,
            'tanggal_mulai_bekerja'    => $request->tanggal_mulai_bekerja,
            'jumlah_kebutuhan'         => $request->jumlah_kebutuhan,
            'lokasi_kerja'             => $request->lokasi_kerja,
            'level'                    => $request->level,
            'alasan_permintaan'        => $request->alasan_permintaan,
            'nama_karyawan_pengganti'  => $request->nama_karyawan_pengganti ?: null,
            'jangka_waktu_kontrak'     => $request->jangka_waktu_kontrak ?: null,
            'dampak_kekurangan_posisi' => $request->dampak_kekurangan_posisi ?: null,
            'sumber_rekrutmen'         => $request->sumber_rekrutmen,
            'catatan_khusus'           => $request->catatan_khusus ?: null,
            'deskripsi_jabatan'        => $request->deskripsi_jabatan ?: null,
            'tanggungjawab_jabatan'    => $filterArray($request->tanggungjawab_jabatan),
            'tugas'                    => $filterArray($request->tugas),
            'tolak_ukur_keberhasilan'  => $filterArray($request->tolak_ukur_keberhasilan),
            'kualifikasi_jk'           => $request->kualifikasi_jk ?? 'Bebas',
            'kualifikasi_usia'         => $request->kualifikasi_usia ?: null,
            'kualifikasi_pendidikan'   => $request->kualifikasi_pendidikan ?: null,
            'kualifikasi_jurusan'      => $request->kualifikasi_jurusan ?: null,
            'kualifikasi_pengalaman'   => $request->kualifikasi_pengalaman ?: null,
            'hard_competency'          => $filterArray($request->hard_competency),
            'soft_competency'          => $filterArray($request->soft_competency),
            'test_dibutuhkan'          => $request->test_dibutuhkan ?: null,
            'sarana_prasarana'         => $request->sarana_prasarana ?: null,
        ]);

        return redirect()->route('rekrutmen.fpk.index')->with('success', 'Data FPK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $fpk = Fpk::findOrFail($id);
        $fpk->delete();

        return redirect()->route('rekrutmen.fpk.index')->with('success', 'Data FPK berhasil dihapus.');
    }

    public function history()
    {
        $user = Auth::user();

        // Cari tahu divisi & dept user (sebagai Manager)
        $karyawan = \App\Models\Karyawan::where('user_id', $user->id)->first();
        if (!$karyawan && !empty($user->nik)) {
            $karyawan = \App\Models\Karyawan::where('nik', $user->nik)->first();
        }

        $deptId = null;
        $divId = null;

        if ($karyawan) {
            $pekerjaan = $karyawan->pekerjaanTerkini()->first() ?? $karyawan->pekerjaan()->first();
            if ($pekerjaan) {
                $deptId = $pekerjaan->department_id;
                $divId = $pekerjaan->division_id;
            }
        }

        // Ambil riwayat FPK yang sesuai dengan Divisi / Dept Manajer terkait
        $fpkQuery = Fpk::with('division', 'department')->orderBy('created_at', 'desc');
        
        // Filter FPK hanya untuk departemen mereka (kecuali kalau dia orang HR bisa lihat semua)
        if ($user->hasRole(['admin', 'superadmin'])) {
            // Bisa lihat semua
        } else {
            // Cek jika bukan HR dan merupakan Manager
            $isHR = false;
            if ($pekerjaan) {
                $dn = strtolower($pekerjaan->department->name ?? '');
                $vn = strtolower($pekerjaan->division->name ?? '');
                if (str_contains($dn, 'hr') || str_contains($dn, 'human') || str_contains($vn, 'hr') || str_contains($vn, 'human resource')) {
                    $isHR = true;
                }
            }
            if (! $isHR) {
                $fpkQuery->where(function ($q) use ($deptId, $divId) {
                    $q->where('department_id', $deptId)
                      ->orWhere('division_id', $divId);
                });
            }
        }

        $fpk = $fpkQuery->get();
        return view('pages.rekrutmen.fpk.history', compact('fpk'));
    }

    public function forwardToHrManager($id)
    {
        $fpk = Fpk::findOrFail($id);
        
        // Validasi, pastikan posisinya sedang 'Pending HR Admin'
        if ($fpk->status_fpk !== 'Pending HR Admin') {
            return redirect()->back()->withErrors('FPK tidak berada dalam status yang dapat diforward oleh HR Admin.');
        }

        // Proses forward (biasanya HR Admin yang tekan ini)
        $fpk->status_fpk = 'Reviewing by HR Manager';
        $fpk->save();

        return redirect()->back()->with('success', 'FPK berhasil di-Forward dan siap untuk di-review oleh HR Manager.');
    }

    public function reject(Request $request, $id)
    {
        $fpk = Fpk::findOrFail($id);
        
        // Update data reject
        $request->validate([
            'alasan_reject' => 'required|string|max:1000',
        ]);

        $fpk->status_fpk = 'Rejected';
        $fpk->alasan_reject = $request->input('alasan_reject');
        $fpk->save();

        return redirect()->back()->with('success', 'Form Pengajuan FPK berhasil di Reject.');
    }

    public function approve(Request $request, $id)
    {
        $fpk = Fpk::findOrFail($id);
        $user = Auth::user();

        // Cari tahu role dan level pekerjaan dari $user via relasi Karyawan
        $karyawan = \App\Models\Karyawan::where('user_id', $user->id)->first();
        if (!$karyawan && !empty($user->nik)) {
            $karyawan = \App\Models\Karyawan::where('nik', $user->nik)->first();
        }

        $isDepartmentManager = false;
        $isDivisionGM        = false;
        $isHRManager         = false;
        $isFinance           = false;
        $isDirector          = false;

        if ($karyawan) {
            $pekerjaan = $karyawan->pekerjaanTerkini()->first() ?? $karyawan->pekerjaan()->first();
            if ($pekerjaan) {
                $levelName = strtolower($pekerjaan->level->name ?? '');
                $deptName  = strtolower($pekerjaan->department->name ?? '');
                $divName   = strtolower($pekerjaan->division->name ?? '');
                
                // Cek manajer departemen
                if (in_array($levelName, ['manager', 'senior_manager', 'supervisor'])) {
                    $isDepartmentManager = true;
                }
                
                // Cek GM / Leader divisi
                if (in_array($levelName, ['gm', 'gm_divisi', 'general manager'])) {
                    $isDivisionGM = true;
                }

                // Cek Spesial HR Manager
                if ($isDepartmentManager && (str_contains($deptName, 'hr') || str_contains($deptName, 'human') || str_contains($divName, 'hr') || str_contains($divName, 'human resource'))) {
                    $isHRManager = true;
                }

                if (str_contains($deptName, 'finance') || str_contains($divName, 'finance')) {
                    $isFinance = true;
                }
            }
        }

        // Jika user memiliki role direktur eksplisit dari Auth
        if ($user->hasRole(['direktur', 'superadmin'])) {
            $isDirector = true;
        }

        // Tentukan Kolom mana yang disetujui berdasarkan Role
        $approvedByCol = null;
        $approvedAtCol = null;

        if ($isHRManager) {
            $approvedByCol = 'approval_hrd_by';
            $approvedAtCol = 'approval_hrd_at';
            $fpk->status_fpk = 'Approved by HRD'; // Update status parsial (opsional)
        } elseif ($isDivisionGM) {
            $approvedByCol = 'approval_divisi_by';
            $approvedAtCol = 'approval_divisi_at';
        } elseif ($isDepartmentManager) {
            $approvedByCol = 'approval_departemen_by';
            $approvedAtCol = 'approval_departemen_at';
        } elseif ($isFinance) {
            $approvedByCol = 'approval_finance_by';
            $approvedAtCol = 'approval_finance_at';
        } elseif ($isDirector) {
            $approvedByCol = 'approval_direktur_by';
            $approvedAtCol = 'approval_direktur_at';
            $fpk->status_fpk = 'Approved'; // Final status
        }

        if ($approvedByCol) {
            $fpk->$approvedByCol = $user->id;
            $fpk->$approvedAtCol = now();
            
            // Cek kondisi Final Approve 
            if ($fpk->approval_hrd_by != null && $fpk->approval_direktur_by != null) {
                $fpk->status_fpk = 'Approved';
            }
            // Jika manager HR yang approve, pastikan status utama juga berubah jika dibutuhkan
            // Khusus perbaikan alur FPK:
            if ($isHRManager && $fpk->status_fpk == 'Reviewing by HR Manager') {
                 $fpk->status_fpk = 'Approved by HRD'; // Update label sesuai plan user jika diperlukan, namun krn default database hanya Approved/Rejected maka:
                 // Update status_fpk string nya 'Approved by HRD' sudah saya hapus di Enum, jadi dibiarkan ke next stage atau Final
            }
            
            $fpk->save();
            return redirect()->back()->with('success', 'Berhasil memberikan persetujuan FPK.');
        }

        return redirect()->back()->withErrors('Anda tidak memiliki wewenang yang sesuai untuk menyetujui FPK ini.');
    }
}

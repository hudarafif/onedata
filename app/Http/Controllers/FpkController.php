<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fpk;
use App\Models\FpkApprovalLog;
use App\Models\Division;
use App\Models\Department;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FpkController extends Controller
{
    public function __construct()
    {
        $this->middleware(\Closure::fromCallable(function ($request, $next) {
            $user = Auth::user();
            if ($user && $user->hasRole(['admin', 'superadmin'])) {
                return $next($request);
            }

            $karyawan = Karyawan::where('user_id', $user->id)->first() ?: Karyawan::where('nik', $user->nik)->first();
            
            if ($karyawan) {
                $pekerjaan = $karyawan->pekerjaanTerkini()->first() ?? $karyawan->pekerjaan()->first();
                if ($pekerjaan) {
                    $levelName = strtolower($pekerjaan->level->name ?? '');
                    $deptName = strtolower($pekerjaan->department->name ?? '');
                    $divName  = strtolower($pekerjaan->division->name ?? '');

                    if (str_contains($levelName, 'manager') || str_contains($levelName, 'supervisor') || str_contains($levelName, 'gm') || str_contains($levelName, 'direktur')) {
                        return $next($request);
                    }
                    if (str_contains($deptName, 'hr') || str_contains($divName, 'hr')) {
                        return $next($request);
                    }
                    if (str_contains($deptName, 'finance') || str_contains($divName, 'finance')) {
                        return $next($request);
                    }
                }
            }

            abort(403, 'Akses Ditolak. Anda bukan bagian dari Manajemen, Finance, atau Tim HR.');
        }));
    }
    

    public function index()
    {
        $fpk = Fpk::with('division', 'department')->orderBy('created_at', 'desc')->paginate(10);
        return view('pages.rekrutmen.fpk.index', compact('fpk'));
    }

    public function history()
    {
        $user = Auth::user();
        $karyawan = Karyawan::where('user_id', $user->id)->first() ?: Karyawan::where('nik', $user->nik)->first();
        
        $fpkQuery = Fpk::with('division', 'department')->orderBy('created_at', 'desc');

        if (!$user->hasRole(['admin', 'superadmin'])) {
            $pekerjaanDesc = $karyawan ? ($karyawan->pekerjaanTerkini()->first() ?? $karyawan->pekerjaan()->first()) : null;
            $isHR = $pekerjaanDesc && (str_contains(strtolower($pekerjaanDesc->department->name ?? ''), 'human resource') || str_contains(strtolower($pekerjaanDesc->division->name ?? ''), 'human resource'));

            if (!$isHR) {
                $deptId = $pekerjaanDesc ? $pekerjaanDesc->department_id : null;
                $divId = $pekerjaanDesc ? $pekerjaanDesc->division_id : null;
                $userId = $user->id;
                
                $fpkQuery->where(function ($q) use ($deptId, $divId, $userId) {
                    $q->where('department_id', $deptId)
                      ->orWhere('division_id', $divId)
                      ->orWhere('created_by', $userId);
                });
            }
        }

        $fpk = $fpkQuery->get();
        return view('pages.rekrutmen.fpk.history', compact('fpk'));
    }

    public function create()
    {
        $divisions = Division::all();
        $departments = Department::all();
        return view('pages.rekrutmen.fpk.create', compact('divisions', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'division_id'           => 'required|exists:divisions,id',
            'department_id'         => 'nullable|exists:departments,id',
            'nama_jabatan'          => 'required|string|max:255',
            'jumlah_kebutuhan'      => 'required|integer|min:1',
            'tanggal_mulai_bekerja' => 'required|date',
            'level'                 => 'required|string',
            'alasan_permintaan'     => 'required|string',
            'sumber_rekrutmen'      => 'required|string',
            'lokasi_kerja'          => 'required|string|max:255',
        ]);

        $bulan = date('m');
        $tahun = date('Y');
        $count = Fpk::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->count() + 1;
        $nomorUrut = str_pad($count, 3, "0", STR_PAD_LEFT);
        $filterArray = fn($arr) => !empty($arr) ? array_values(array_filter($arr, fn($v) => trim($v) !== '')) : null;

        $status = $request->input('action') === 'submit' ? 'Pending HR Admin' : 'Draft';

        $fpk = Fpk::create([
            'nomor_fpk'                => "FPK/$tahun/$bulan/$nomorUrut",
            'division_id'              => $request->division_id,
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
            'status_fpk'               => $status,
            'created_by'               => Auth::id(),
        ]);

        $this->storeApprovalLog($fpk->id, $status === 'Draft' ? 'create_draft' : 'submit', null, $status);

        return redirect()->route('rekrutmen.fpk.history')->with('success', $status === 'Draft' ? 'FPK disimpan sebagai Draft.' : 'FPK berhasil diajukan.');
    }

    public function show($id)
    {
        $fpk = Fpk::with(['division', 'department', 'approvalLogs.user', 'creator'])->findOrFail($id);
        return view('pages.rekrutmen.fpk.show', compact('fpk'));
    }

    public function edit($id)
    {
        $fpk = Fpk::findOrFail($id);
        if (!in_array($fpk->status_fpk, ['Draft', 'Revision Required'])) {
            abort(403, 'FPK yang sudah diproses tidak dapat diedit.');
        }
        $divisions = Division::all();
        $departments = Department::all();
        return view('pages.rekrutmen.fpk.edit', compact('fpk', 'divisions', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $fpk = Fpk::findOrFail($id);
        if (!in_array($fpk->status_fpk, ['Draft', 'Revision Required'])) {
            abort(403, 'FPK yang sudah diproses tidak dapat diedit.');
        }

        $request->validate([
            'division_id'           => 'required|exists:divisions,id',
            'department_id'         => 'nullable|exists:departments,id',
            'nama_jabatan'          => 'required|string|max:255',
            'jumlah_kebutuhan'      => 'required|integer|min:1',
            'tanggal_mulai_bekerja' => 'required|date',
            'level'                 => 'required|string',
            'alasan_permintaan'     => 'required|string',
            'sumber_rekrutmen'      => 'required|string',
            'lokasi_kerja'          => 'required|string|max:255',
        ]);

        $filterArray = fn($arr) => !empty($arr) ? array_values(array_filter($arr, fn($v) => trim($v) !== '')) : null;
        $prevStatus = $fpk->status_fpk;
        $status = $request->input('action') === 'submit' ? 'Pending HR Admin' : $prevStatus;

        $fpk->update([
            'division_id'              => $request->division_id,
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
            'status_fpk'               => $status,
        ]);

        if ($status !== $prevStatus) {
            $this->storeApprovalLog($fpk->id, 'resubmit', $prevStatus, $status);
        }

        return redirect()->route('rekrutmen.fpk.index')->with('success', 'Data FPK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $fpk = Fpk::findOrFail($id);
        if ($fpk->status_fpk !== 'Draft') {
            abort(403, 'Hanya FPK berstatus Draft yang dapat dihapus.');
        }
        $fpk->delete();
        return redirect()->route('rekrutmen.fpk.index')->with('success', 'FPK berhasil dihapus.');
    }

    public function submit($id)
    {
        $fpk = Fpk::findOrFail($id);
        if (!in_array($fpk->status_fpk, ['Draft', 'Revision Required'])) {
            return redirect()->back()->withErrors('FPK tidak dalam status untuk disubmit.');
        }
        $prev = $fpk->status_fpk;
        $fpk->status_fpk = 'Pending HR Admin';
        $fpk->save();
        $this->storeApprovalLog($fpk->id, 'submit', $prev, 'Pending HR Admin');
        return redirect()->back()->with('success', 'FPK berhasil diajukan.');
    }

    public function approveHrAdmin($id)
    {
        $this->checkRole('human resource');
        $fpk = Fpk::with('creator')->findOrFail($id);
        if ($fpk->status_fpk !== 'Pending HR Admin') abort(403);

        // Check condition: if requester is Finance Manager, bypass finance approval
        $isFinanceManager = false;
        if ($fpk->created_by) {
            $crUser = $fpk->creator;
            $karyawan = Karyawan::where('user_id', $crUser->id)->first() ?: Karyawan::where('nik', $crUser->nik)->first();
            if ($karyawan) {
                $pekerjaan = $karyawan->pekerjaanTerkini()->first() ?? $karyawan->pekerjaan()->first();
                if ($pekerjaan) {
                    $level = strtolower($pekerjaan->level->name ?? '');
                    $dept  = strtolower($pekerjaan->department->name ?? '');
                    if (str_contains($level, 'manager') && str_contains($dept, 'finance')) {
                        $isFinanceManager = true;
                    }
                }
            }
        }

        if ($isFinanceManager) {
            $fpk->status_fpk = 'Reviewing by HR Manager';
            $msg = 'FPK disetujui (Finance Manager requester - Bypass Finance Approval).';
            $toStatus = 'Reviewing by HR Manager';
        } else {
            $fpk->status_fpk = 'Pending Finance Approval';
            $msg = 'FPK disetujui HR Admin dan diteruskan ke Finance.';
            $toStatus = 'Pending Finance Approval';
        }

        $fpk->approval_hrd_by = Auth::id();
        $fpk->approval_hrd_at = now();
        $fpk->save();
        
        $this->storeApprovalLog($fpk->id, 'approve', 'Pending HR Admin', $toStatus);
        
        return redirect()->back()->with('success', $msg);
    }

    public function approveFinance($id)
    {
        $this->checkRole('finance');
        $fpk = Fpk::findOrFail($id);
        if ($fpk->status_fpk !== 'Pending Finance Approval') abort(403);

        $fpk->status_fpk = 'Reviewing by HR Manager';
        $fpk->approval_finance_by = Auth::id();
        $fpk->approval_finance_at = now();
        $fpk->save();
        $this->storeApprovalLog($fpk->id, 'approve', 'Pending Finance Approval', 'Reviewing by HR Manager');
        return redirect()->back()->with('success', 'FPK disetujui Finance dan diteruskan ke HR Manager.');
    }

    public function approveHrManager($id)
    {
        $this->checkRole('hr_manager');
        $fpk = Fpk::findOrFail($id);
        if ($fpk->status_fpk !== 'Reviewing by HR Manager') abort(403);

        $fpk->status_fpk = 'Approved';
        $fpk->approval_direktur_by = Auth::id(); // Misal HR Manager level tertinggi direksi
        $fpk->approval_direktur_at = now();
        $fpk->save();
        $this->storeApprovalLog($fpk->id, 'approve', 'Reviewing by HR Manager', 'Approved');
        return redirect()->back()->with('success', 'FPK resmi disetujui.');
    }

    public function requestRevision(Request $request, $id)
    {
        $this->checkRole('hr');
        $fpk = Fpk::findOrFail($id);
        if ($fpk->status_fpk !== 'Pending HR Admin') abort(403);

        $request->validate(['revision_comment' => 'required|string|max:1000']);
        
        $fpk->status_fpk = 'Revision Required';
        $fpk->revision_comment = $request->revision_comment;
        $fpk->save();
        $this->storeApprovalLog($fpk->id, 'request_revision', 'Pending HR Admin', 'Revision Required', $request->revision_comment);
        return redirect()->back()->with('success', 'Permintaan revisi dikirim ke Requester.');
    }

    public function reject(Request $request, $id)
    {
        $fpk = Fpk::findOrFail($id);
        $user = Auth::user();
        
        $request->validate(['alasan_reject' => 'required|string|max:1000']);
        
        $prev = $fpk->status_fpk;
        $fpk->status_fpk = 'Rejected';
        $fpk->alasan_reject = $request->alasan_reject;
        $fpk->save();

        $this->storeApprovalLog($fpk->id, 'reject', $prev, 'Rejected', $request->alasan_reject);
        return redirect()->back()->with('success', 'FPK ditolak.');
    }

    private function storeApprovalLog($fpkId, $action, $from, $to, $notes = null)
    {
        FpkApprovalLog::create([
            'fpk_id' => $fpkId,
            'user_id' => Auth::id(),
            'action' => $action,
            'from_status' => $from,
            'to_status' => $to,
            'notes' => $notes,
        ]);
    }

    private function checkRole($roleType)
    {
        $user = Auth::user();
        if ($user->hasRole(['admin', 'superadmin'])) return;

        $karyawan = Karyawan::where('user_id', $user->id)->first() ?: Karyawan::where('nik', $user->nik)->first();
        if (!$karyawan) abort(403);

        $pekerjaan = $karyawan->pekerjaanTerkini()->first() ?? $karyawan->pekerjaan()->first();
        if (!$pekerjaan) abort(403);

        $dept = strtolower($pekerjaan->department->name ?? '');
        $div  = strtolower($pekerjaan->division->name ?? '');
        $level = strtolower($pekerjaan->level->name ?? '');

        if ($roleType === 'hr' && (str_contains($dept, 'human resource') || str_contains($div, 'human resource'))) return;
        if ($roleType === 'finance' && (str_contains($dept, 'finance') || str_contains($div, 'finance'))) return;
        if ($roleType === 'hr_manager' && (str_contains($dept, 'human resource') || str_contains($div, 'human resource')) && str_contains($level, 'manager')) return;

        abort(403, 'Akses Ditolak. Anda tidak punya izin untuk aksi ini.');
    }
}

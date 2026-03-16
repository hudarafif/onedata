<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PegawaiKompetensi;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class KompetensiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Security Check: Hanya admin, superadmin, manager, direktur yang bisa melihat
        if (!$this->roleMatches($user, ['admin', 'superadmin', 'manager', 'GM', 'senior_manager', 'direktur'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Karyawan::with(['pegawaiKompetensi', 'pekerjaan.company', 'pekerjaan.division', 'pekerjaan.department'])
            ->whereHas('pegawaiKompetensi');

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Nama_Lengkap_Sesuai_Ijazah', 'LIKE', "%{$search}%")
                  ->orWhere('NIK', 'LIKE', "%{$search}%")
                  ->orWhereHas('pegawaiKompetensi', function($kompQuery) use ($search) {
                      $kompQuery->where('nama_kompetensi', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter Level (Opsional)
        if ($request->filled('level')) {
            $query->whereHas('pegawaiKompetensi', function ($q) use ($request) {
                $q->where('level', $request->level);
            });
        }

        // Urutkan data berdasarkan nama
        $kompetensiList = $query->orderBy('Nama_Lengkap_Sesuai_Ijazah', 'ASC')->paginate(10)->appends($request->all());

        // Untuk Dropdown Filter (Level Kompetensi unik dari database)
        $listLevel = PegawaiKompetensi::select('level')->whereNotNull('level')->distinct()->pluck('level');

        return view('pages.kompetensi.index', compact('kompetensiList', 'listLevel'));
    }

    /**
     * Trigger Manual Sync from LMS Wadja
     */
    public function sync()
    {
        $user = Auth::user();
        if (!$this->roleMatches($user, ['admin', 'superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Hanya Admin yang dapat melakukan sinkronisasi.'], 403);
        }

        try {
            Artisan::call('wadja:sync-competency');
            $output = Artisan::output();
            
            return response()->json([
                'success' => true, 
                'message' => 'Sinkronisasi berhasil dimulai.',
                'details' => $output
            ]);
        } catch (\Exception $e) {
            Log::error('Manual Sync Error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menjalankan sinkronisasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export Competency Data to CSV
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        if (!$this->roleMatches($user, ['admin', 'superadmin', 'manager', 'GM', 'senior_manager', 'direktur'])) {
            abort(403);
        }

        $query = Karyawan::with(['pegawaiKompetensi', 'pekerjaan.department'])
            ->whereHas('pegawaiKompetensi');

        // Apply filters same as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Nama_Lengkap_Sesuai_Ijazah', 'LIKE', "%{$search}%")
                  ->orWhere('NIK', 'LIKE', "%{$search}%");
            });
        }

        $employees = $query->get();

        $filename = "Monitoring_Kompetensi_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            // Header CSV
            fputcsv($file, ['Nama Lengkap', 'NIK', 'Kelas', 'Kompetensi Tersedia', 'Kompetensi Diselesaikan']);

            foreach ($employees as $emp) {
                $job = $emp->pekerjaanTerkini()->first() ?? $emp->pekerjaan()->first();
                $kelas = $job->department->name ?? '-';
                
                $tersedia = $emp->pegawaiKompetensi->where('status', 'available')->pluck('nama_kompetensi')->unique()->implode(', ');
                $diselesaikan = $emp->pegawaiKompetensi->where('status', 'completed')->pluck('nama_kompetensi')->unique()->implode(', ');

                fputcsv($file, [
                    $emp->Nama_Lengkap_Sesuai_Ijazah,
                    $emp->NIK,
                    $kelas,
                    $tersedia ?: '-',
                    $diselesaikan ?: '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Helper: Role Checker
    private function roleMatches($user, $roles)
    {
        if (!$user) return false;

        $userRoleNames = [];
        try {
            $userRoleNames = $user->roles()->pluck('name')->map(function ($r) {
                return strtolower($r);
            })->toArray();
        } catch (\Throwable $e) {}

        $derivedRoles = [];
        $karyawan = Karyawan::where('user_id', $user->id)->first();
        if (!$karyawan && !empty($user->nik)) {
            $karyawan = Karyawan::where('nik', $user->nik)->first();
        }
        if ($karyawan) {
            $pekerjaan = $karyawan->pekerjaanTerkini()->first() ?? $karyawan->pekerjaan()->first();
            if ($pekerjaan) {
                if (!empty($pekerjaan->level) && !empty($pekerjaan->level->name)) $derivedRoles[] = strtolower($pekerjaan->level->name);
                if (!empty($pekerjaan->position) && !empty($pekerjaan->position->name)) $derivedRoles[] = strtolower($pekerjaan->position->name);
            }
        }

        if (is_string($roles)) $roles = [$roles];
        $roles = array_map('strtolower', $roles);

        foreach ($roles as $r) {
            if (in_array($r, $userRoleNames)) return true;
            if (in_array($r, $derivedRoles)) return true;
        }
        return false;
    }
}

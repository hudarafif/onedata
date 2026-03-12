<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fpk extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_fpk';

    protected $fillable = [
        'nomor_fpk',
        'division_id',
        'department_id',
        'nama_jabatan',
        'grade',
        'perkiraan_gaji',
        'tanggal_mulai_bekerja',
        'jumlah_kebutuhan',
        'lokasi_kerja',
        'level',
        'alasan_permintaan',
        'nama_karyawan_pengganti',
        'jangka_waktu_kontrak',
        'dampak_kekurangan_posisi',
        'sumber_rekrutmen',
        'catatan_khusus',
        'deskripsi_jabatan',
        'tanggungjawab_jabatan',
        'tugas',
        'tolak_ukur_keberhasilan',
        'kualifikasi_jk',
        'kualifikasi_usia',
        'kualifikasi_pendidikan',
        'kualifikasi_jurusan',
        'kualifikasi_pengalaman',
        'hard_competency',
        'soft_competency',
        'test_dibutuhkan',
        'sarana_prasarana',
        'created_by',
        'status_fpk',
        'revision_comment',
        'alasan_reject',
        'approval_departemen_by',
        'approval_departemen_at',
        'approval_divisi_by',
        'approval_divisi_at',
        'approval_hrd_by',
        'approval_hrd_at',
        'approval_finance_by',
        'approval_finance_at',
        'approval_direktur_by',
        'approval_direktur_at',
    ];

    protected $casts = [
        'tanggal_mulai_bekerja' => 'date',
        'tanggungjawab_jabatan' => 'array',
        'tugas' => 'array',
        'tolak_ukur_keberhasilan' => 'array',
        'hard_competency' => 'array',
        'soft_competency' => 'array',
        'test_dibutuhkan' => 'array',
        'sarana_prasarana' => 'array',
        'approval_departemen_at' => 'datetime',
        'approval_divisi_at' => 'datetime',
        'approval_hrd_at' => 'datetime',
        'approval_finance_at' => 'datetime',
        'approval_direktur_at' => 'datetime',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function approvalDepartemenBy()
    {
        return $this->belongsTo(User::class, 'approval_departemen_by');
    }

    public function approvalDivisiBy()
    {
        return $this->belongsTo(User::class, 'approval_divisi_by');
    }

    public function approvalHrdBy()
    {
        return $this->belongsTo(User::class, 'approval_hrd_by');
    }

    public function approvalFinanceBy()
    {
        return $this->belongsTo(User::class, 'approval_finance_by');
    }

    public function approvalDirekturBy()
    {
        return $this->belongsTo(User::class, 'approval_direktur_by');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function approvalLogs()
    {
        return $this->hasMany(FpkApprovalLog::class, 'fpk_id');
    }
}

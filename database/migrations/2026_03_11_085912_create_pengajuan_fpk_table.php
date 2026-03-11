<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_fpk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_fpk', 50)->unique();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            
            // Informasi Pekerjaan (Header)
            $table->string('nama_jabatan');
            $table->string('grade')->nullable();
            $table->string('perkiraan_gaji')->nullable();
            $table->date('tanggal_mulai_bekerja');
            $table->integer('jumlah_kebutuhan');
            $table->string('lokasi_kerja');
            $table->enum('level', ['Staf', 'Ka. Regu', 'Ka. Unit', 'Manager', 'General Manager']);
            
            // Alasan Permintaan & Dampak
            $table->enum('alasan_permintaan', ['Penggantian Karyawan', 'Penambahan Karyawan Baru', 'Jangka Waktu Kontrak']);
            $table->string('nama_karyawan_pengganti')->nullable();
            $table->string('jangka_waktu_kontrak')->nullable();
            $table->text('dampak_kekurangan_posisi')->nullable();
            
            // Rekrutmen & Jobdesc
            $table->enum('sumber_rekrutmen', ['Internal', 'Eksternal', 'Outsource']);
            $table->text('catatan_khusus')->nullable();
            $table->text('deskripsi_jabatan')->nullable();
            $table->json('tanggungjawab_jabatan')->nullable();
            $table->json('tugas')->nullable();
            $table->json('tolak_ukur_keberhasilan')->nullable();
            
            // Kualifikasi Tenaga Kerja
            $table->enum('kualifikasi_jk', ['Laki-laki', 'Perempuan', 'Bebas'])->default('Bebas');
            $table->string('kualifikasi_usia')->nullable(); // misal '18-25 Tahun' dsb
            $table->string('kualifikasi_pendidikan')->nullable(); // misal 'SMA/K', 'S1'
            $table->string('kualifikasi_jurusan')->nullable();
            $table->string('kualifikasi_pengalaman')->nullable(); // misal 'Freshgraduate', '1-3 Tahun' dsb
            $table->json('hard_competency')->nullable();
            $table->json('soft_competency')->nullable();
            
            // Test & Sarana
            $table->json('test_dibutuhkan')->nullable(); // array of test types
            $table->json('sarana_prasarana')->nullable(); // array of facilities
            
            // Proses Persetujuan (Approval Tracking)
            $table->enum('status_fpk', ['Draft', 'Pending Approval', 'Approved', 'Rejected'])->default('Draft');
            
            $table->unsignedBigInteger('approval_departemen_by')->nullable();
            $table->timestamp('approval_departemen_at')->nullable();
            
            $table->unsignedBigInteger('approval_divisi_by')->nullable();
            $table->timestamp('approval_divisi_at')->nullable();
            
            $table->unsignedBigInteger('approval_hrd_by')->nullable();
            $table->timestamp('approval_hrd_at')->nullable();
            
            $table->unsignedBigInteger('approval_finance_by')->nullable();
            $table->timestamp('approval_finance_at')->nullable();
            
            $table->unsignedBigInteger('approval_direktur_by')->nullable();
            $table->timestamp('approval_direktur_at')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('approval_departemen_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approval_divisi_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approval_hrd_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approval_finance_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approval_direktur_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_fpk');
    }
};

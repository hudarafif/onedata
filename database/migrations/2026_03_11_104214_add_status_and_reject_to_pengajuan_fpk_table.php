<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ubah enum status_fpk yang sudah ada, atau cukup redefine 
        // Mengubah kolom Enum yang sudah ada di MySQL perlu raw query jika belum menggunakan Doctrine dbal
        DB::statement("ALTER TABLE pengajuan_fpk MODIFY COLUMN status_fpk ENUM('Draft', 'Pending HR Admin', 'Reviewing by HR Manager', 'Approved', 'Rejected') DEFAULT 'Draft'");

        Schema::table('pengajuan_fpk', function (Blueprint $table) {
            // 2. Tambah field alasan_reject untuk menampung argumen ketika FPK ditolak
            if (!Schema::hasColumn('pengajuan_fpk', 'alasan_reject')) {
                $table->text('alasan_reject')->nullable()->after('status_fpk');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke Enum semula (Draft, Pending Approval, Approved, Rejected)
        DB::statement("ALTER TABLE pengajuan_fpk MODIFY COLUMN status_fpk ENUM('Draft', 'Pending Approval', 'Approved', 'Rejected') DEFAULT 'Draft'");
        
        Schema::table('pengajuan_fpk', function (Blueprint $table) {
            $table->dropColumn('alasan_reject');
        });
    }
};

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
        // 1. Ubah enum status_fpk menjadi VARCHAR agar fleksibel sesuai workflow JSON
        // Karena enum susah diubah pakai dbal, kita gunakan raw SQL statement
        DB::statement("ALTER TABLE pengajuan_fpk MODIFY COLUMN status_fpk VARCHAR(100) DEFAULT 'Draft'");

        // 2. Tambahkan kolom revision_comment untuk menampung alasan revisi
        Schema::table('pengajuan_fpk', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_fpk', 'revision_comment')) {
                $table->text('revision_comment')->nullable()->after('status_fpk');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika di-rollback, status bisa bermasalah jika valuenya di luar Enum lama.
        // Kita paksa ubah kembali jadi ENUM versi dasar (beserta fallback)
        DB::statement("UPDATE pengajuan_fpk SET status_fpk = 'Pending HR Admin' WHERE status_fpk NOT IN ('Draft', 'Pending HR Admin', 'Reviewing by HR Manager', 'Approved', 'Rejected')");
        DB::statement("ALTER TABLE pengajuan_fpk MODIFY COLUMN status_fpk ENUM('Draft', 'Pending HR Admin', 'Reviewing by HR Manager', 'Approved', 'Rejected') DEFAULT 'Draft'");

        Schema::table('pengajuan_fpk', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_fpk', 'revision_comment')) {
                $table->dropColumn('revision_comment');
            }
        });
    }
};

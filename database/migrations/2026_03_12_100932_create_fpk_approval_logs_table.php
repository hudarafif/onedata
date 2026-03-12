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
        Schema::create('fpk_approval_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fpk_id')->constrained('pengajuan_fpk')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action', 100); // e.g., 'submit', 'approve', 'reject', 'request_revision'
            $table->string('from_status', 100)->nullable();
            $table->string('to_status', 100)->nullable();
            $table->text('notes')->nullable(); // Alasan/komentar dari aktor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fpk_approval_logs');
    }
};

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
        Schema::create('pengajuan_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perwakilan_user_id')->constrained('users');
            $table->foreignId('bidang_id')->constrained('bidang');
            $table->enum('status', ['Menunggu', 'Diterima', 'Ditolak', 'Revisi', 'Selesai'])->default('Menunggu');
            $table->text('komentar_revisi')->nullable();
            $table->string('surat_permohonan', 255);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('nama_pembimbing', 150)->nullable();
            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->timestamp('batas_verifikasi');
            $table->boolean('is_warned')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_magangs');
    }
};

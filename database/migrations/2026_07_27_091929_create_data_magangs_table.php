<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_magang', function (Blueprint $table) {
            $table->id();

            // Relasi 1:1 ke pengajuan yang sudah diterima
            $table->foreignId('pengajuan_id')
                ->unique()
                ->constrained('pengajuan_magang')
                ->cascadeOnDelete();

            // Status pelaksanaan magang (bukan status verifikasi berkas)
            $table->enum('status', ['Berlangsung', 'Selesai'])->default('Berlangsung');

            // Tanggal aktual magang selesai (bisa berbeda dari tanggal_selesai rencana di pengajuan_magang)
            $table->date('tanggal_selesai_aktual')->nullable();

            // Catatan admin terkait pelaksanaan magang (opsional)
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_magang');
    }
};

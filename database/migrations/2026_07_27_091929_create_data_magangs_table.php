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

            $table->foreignId('pengajuan_id')
                ->unique()
                ->constrained('pengajuan_magang')
                ->cascadeOnDelete();

            $table->enum('status', ['Terdaftar', 'Berlangsung', 'Selesai', 'Dibatalkan'])->default('Terdaftar');
            $table->string('nama_pembimbing', 150)->nullable();
            $table->string('no_hp_pembimbing', 20)->nullable();
            $table->date('tanggal_selesai_aktual')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_magang');
    }
};

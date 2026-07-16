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
    Schema::create('anggota_magang', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pengajuan_id')->constrained('pengajuan_magang')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users');
        $table->string('berkas_pendukung', 255);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_magangs');
    }
};

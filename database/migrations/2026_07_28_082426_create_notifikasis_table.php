<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skpd_id')->constrained('skpd')->cascadeOnDelete();
            $table->foreignId('pengajuan_id')->nullable()->constrained('pengajuan_magang')->cascadeOnDelete();

            $table->enum('type', ['baru', 'mendesak', 'terlambat', 'manual']);
            $table->string('judul', 150);
            $table->text('pesan');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['pengajuan_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
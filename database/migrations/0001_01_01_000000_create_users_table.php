<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name', 50)->unique();
        });

        Schema::create('skpd', function (Blueprint $table) {
            $table->id();
            $table->string('kode_skpd', 50)->unique();
            $table->string('nama_skpd', 150);
            $table->string('banner_path', 255)->nullable();
        });

        Schema::create('bidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skpd_id')->constrained('skpd')->onDelete('cascade');
            $table->string('nama_bidang', 100);
            $table->integer('kuota_total')->default(0);
            $table->integer('sisa_kuota')->default(0);
        });


        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('skpd_id')->nullable()->constrained('skpd');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('institusi_asal', 150)->nullable();
            $table->string('jurusan_prodi', 100)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('bidang');
        Schema::dropIfExists('skpd');
        Schema::dropIfExists('roles');
    }
};

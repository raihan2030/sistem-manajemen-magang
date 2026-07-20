<?php

namespace Database\Factories;

use App\Models\Bidang;
use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PengajuanMagang>
 */
class PengajuanMagangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalPengajuan = fake()->dateTimeBetween('-1 month', 'now');
        $batasVerifikasi = (clone $tanggalPengajuan)->modify('+24 hours');

        return [
            'perwakilan_user_id' => User::factory(),
            'bidang_id' => Bidang::inRandomOrder('')->first()->id ?? 1,
            'status' => fake()->randomElement(['Menunggu', 'Diterima', 'Ditolak', 'Revisi', 'Selesai']),
            'komentar_revisi' => null,
            'surat_permohonan' => 'documents/surat_permohonan_dummy.pdf',
            'tanggal_mulai' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'tanggal_selesai' => fake()->dateTimeBetween('+2 months', '+4 months')->format('Y-m-d'),
            'nama_pembimbing' => fake()->name(),
            'tanggal_pengajuan' => $tanggalPengajuan,
            'batas_verifikasi' => $batasVerifikasi,
            'is_warned' => false,
        ];
    }
}

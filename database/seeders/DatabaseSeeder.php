<?php

namespace Database\Seeders;

use App\Models\AnggotaMagang;
use App\Models\PengajuanMagang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SkpdSeeder::class,
        ]);

        User::factory()->superadmin()->create([
            'name' => 'Superadmin Pengawas',
            'email' => 'superadmin@banjarmasin.go.id',
        ]);

        User::factory()->adminSkpd(1)->create([
            'name' => 'Admin Diskominfotik',
            'email' => 'admin.kominfo@banjarmasin.go.id',
            'no_hp' => '081952925293'
        ]);

        User::factory()->adminSkpd(2)->create([
            'name' => 'Admin Dinkes',
            'email' => 'admin.dinkes@banjarmasin.go.id',
            'no_hp' => '085820306758'
        ]);

        User::factory()->create([
            'name' => 'Muhammad Raihan',
            'email' => 'mraihan@gmail.com',
        ]);

    //     PengajuanMagang::factory()
    //         ->count(20)
    //         // Mengatur batas_verifikasi acak pada rentang 1 - 24 jam dari sekarang untuk tiap baris
    //         ->state(function (array $attributes) {
    //             return [
    //                 'batas_verifikasi' => Carbon::now('+08:00')->addMinutes(rand(60, 1440)),
    //             ];
    //         })
    //         ->create()
    //         ->each(function ($pengajuan) {
    //             // Ambil data user yang merupakan perwakilan dari pengajuan magang ini
    //             $user = User::find($pengajuan->perwakilan_user_id);

    //             // 1. Buat Ketua/Anggota Pertama dengan data yang identik dengan user pengaju
    //             AnggotaMagang::factory()->create([
    //                 'pengajuan_id' => $pengajuan->id,
    //                 'nama_lengkap' => $user->name,
    //                 // Jika di tabel users Anda ada kolom pendukung lain, sinkronkan juga di sini:
    //                 // 'nim_nisn' => $user->nim ?? '123456789',
    //             ]);

    //             // 2. Buat sisa anggota kelompok lainnya (misal 1 hingga 3 orang tambahan)
    //             AnggotaMagang::factory()
    //                 ->count(rand(1, 3))
    //                 ->create([
    //                     'pengajuan_id' => $pengajuan->id
    //                 ]);
    //         });
    }
}

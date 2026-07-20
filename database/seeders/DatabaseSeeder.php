<?php

namespace Database\Seeders;

use App\Models\AnggotaMagang;
use App\Models\PengajuanMagang;
use App\Models\User;
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
        ]);

        PengajuanMagang::factory()
            ->count(10)
            ->create()
            ->each(function ($pengajuan) {
                AnggotaMagang::factory()
                    ->count(rand(2, 4))
                    ->create([
                        'pengajuan_id' => $pengajuan->id
                    ]);
            });
    }
}

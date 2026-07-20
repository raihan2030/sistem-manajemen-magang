<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\Skpd;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSkpd = [
            [
                'kode_skpd' => 'DISKOMINFOTIK-BJM',
                'nama_skpd' => 'Dinas Komunikasi, Informatika, dan Statistik Kota Banjarmasin',
                'banner_path' => 'banners/diskominfo-header.jpg', // Banner di tingkat SKPD
                'bidang' => [
                    [
                        'nama_bidang' => 'Layanan E-Government & Aplikasi',
                        'kuota_total' => 10,
                        'sisa_kuota' => 10,
                    ],
                    [
                        'nama_bidang' => 'Persandian & Keamanan Siber',
                        'kuota_total' => 5,
                        'sisa_kuota' => 5,
                    ],
                    [
                        'nama_bidang' => 'Infrastruktur TIK & Jaringan',
                        'kuota_total' => 8,
                        'sisa_kuota' => 8,
                    ],
                ]
            ],
            [
                'kode_skpd' => 'DINKES-BJM',
                'nama_skpd' => 'Dinas Kesehatan Kota Banjarmasin',
                'banner_path' => 'banners/dinkes-header.jpg', // Banner di tingkat SKPD
                'bidang' => [
                    [
                        'nama_bidang' => 'Sekretariat & Administrasi Umum',
                        'kuota_total' => 6,
                        'sisa_kuota' => 6,
                    ],
                    [
                        'nama_bidang' => 'Sistem Informasi Kesehatan',
                        'kuota_total' => 4,
                        'sisa_kuota' => 4,
                    ],
                ]
            ],
        ];

        foreach ($dataSkpd as $item) {
            $skpd = Skpd::updateOrCreate(
                ['kode_skpd' => $item['kode_skpd']],
                [
                    'nama_skpd' => $item['nama_skpd'],
                    'banner_path' => $item['banner_path']
                ]
            );

            foreach ($item['bidang'] as $b) {
                Bidang::updateOrCreate(
                    [
                        'skpd_id' => $skpd->id,
                        'nama_bidang' => $b['nama_bidang']
                    ],
                    [
                        'kuota_total' => $b['kuota_total'],
                        'sisa_kuota' => $b['sisa_kuota'],
                    ]
                );
            }
        }
    }
}

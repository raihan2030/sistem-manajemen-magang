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
                'kode_skpd' => '2.16.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Komunikasi, Informatika dan Statistik',
                'banner_path' => 'banners/diskominfo-header.jpg',
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
                'kode_skpd' => '1.02.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Kesehatan',
                'banner_path' => 'banners/dinkes-header.jpg',
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
            [
                'kode_skpd' => '4.01.0.00.0.00.01.0000',
                'nama_skpd' => 'Sekretariat Daerah',
                'banner_path' => 'banners/sekda-header.jpg',
            ],
            [
                'kode_skpd' => '4.02.0.00.0.00.01.0000',
                'nama_skpd' => 'Sekretariat Dewan Perwakilan Rakyat Daerah',
                'banner_path' => 'banners/setwan-header.jpg',
            ],
            [
                'kode_skpd' => '6.01.0.00.0.00.01.0000',
                'nama_skpd' => 'Inspektorat Daerah',
                'banner_path' => 'banners/inspektorat-header.jpg',
            ],
            [
                'kode_skpd' => '5.03.0.00.0.00.01.0000',
                'nama_skpd' => 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia',
                'banner_path' => 'banners/bkpsdm-header.jpg',
            ],
            [
                'kode_skpd' => '5.01.0.00.0.00.01.0000',
                'nama_skpd' => 'Badan Perencanaan, Penelitian, dan Pengembangan Daerah',
                'banner_path' => 'banners/bapperinda-header.jpg',
            ],
            [
                'kode_skpd' => '5.02.0.00.0.00.01.0000',
                'nama_skpd' => 'Badan Pengelolaan Keuangan, Pendapatan dan Aset Daerah',
                'banner_path' => 'banners/bpkpad-header.jpg',
            ],
            [
                'kode_skpd' => '1.05.0.00.0.00.02.0000',
                'nama_skpd' => 'Badan Penanggulangan Bencana Daerah',
                'banner_path' => 'banners/bpbd-header.jpg',
            ],
            [
                'kode_skpd' => '8.01.0.00.0.00.01.0000',
                'nama_skpd' => 'Badan Kesatuan Bangsa dan Politik',
                'banner_path' => 'banners/bakesbangpol-header.jpg',
            ],
            [
                'kode_skpd' => '1.01.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Pendidikan',
                'banner_path' => 'banners/disdik-header.jpg',
            ],
            [
                'kode_skpd' => '1.03.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'banner_path' => 'banners/dpupr-header.jpg',
            ],
            [
                'kode_skpd' => '1.04.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Perumahan Rakyat dan Kawasan Permukiman',
                'banner_path' => 'banners/disperkim-header.jpg',
            ],
            [
                'kode_skpd' => '1.05.0.00.0.00.01.0000',
                'nama_skpd' => 'Satuan Polisi Pamong Praja',
                'banner_path' => 'banners/satpolpp-header.jpg',
            ],
            [
                'kode_skpd' => '1.05.0.00.0.00.03.0000',
                'nama_skpd' => 'Dinas Pemadam Kebakaran dan Penyelamatan',
                'banner_path' => 'banners/dpkp-header.jpg',
            ],
            [
                'kode_skpd' => '1.06.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Sosial',
                'banner_path' => 'banners/dinsos-header.jpg',
            ],
            [
                'kode_skpd' => '2.08.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak',
                'banner_path' => 'banners/dpppa-header.jpg',
            ],
            [
                'kode_skpd' => '2.09.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Ketahanan Pangan, Pertanian dan Perikanan',
                'banner_path' => 'banners/dkp3-header.jpg',
            ],
            [
                'kode_skpd' => '2.11.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Lingkungan Hidup',
                'banner_path' => 'banners/dlh-header.jpg',
            ],
            [
                'kode_skpd' => '2.12.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Kependudukan dan Pencatatan Sipil',
                'banner_path' => 'banners/disdukcapil-header.jpg',
            ],
            [
                'kode_skpd' => '2.13.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Pengendalian Penduduk, Keluarga Berencana dan Pemberdayaan Masyarakat',
                'banner_path' => 'banners/dppkbpm-header.jpg',
            ],
            [
                'kode_skpd' => '2.15.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Perhubungan',
                'banner_path' => 'banners/dishub-header.jpg',
            ],
            [
                'kode_skpd' => '2.17.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Koperasi, Usaha Mikro dan Tenaga Kerja',
                'banner_path' => 'banners/diskopumker-header.jpg',
            ],
            [
                'kode_skpd' => '2.18.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
                'banner_path' => 'banners/dpmptsp-header.jpg',
            ],
            [
                'kode_skpd' => '2.19.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Kebudayaan, Kepemudaan, Olahraga dan Pariwisata',
                'banner_path' => 'banners/disbudporapar-header.jpg',
            ],
            [
                'kode_skpd' => '2.23.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Perpustakaan dan Kearsipan',
                'banner_path' => 'banners/dispersip-header.jpg',
            ],
            [
                'kode_skpd' => '3.30.0.00.0.00.01.0000',
                'nama_skpd' => 'Dinas Perdagangan dan Perindustrian',
                'banner_path' => 'banners/disperdagin-header.jpg',
            ],
        ];

        foreach ($dataSkpd as $item) {
            $skpd = Skpd::updateOrCreate(
                ['kode_skpd' => $item['kode_skpd']],
                [
                    'nama_skpd' => $item['nama_skpd'],
                    'banner_path' => $item['banner_path'] ?? null
                ]
            );

            foreach ($item['bidang'] ?? [] as $b) {
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

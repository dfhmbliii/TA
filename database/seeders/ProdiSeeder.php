<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        $prodis = [
            [
                'nama_prodi' => 'Sistem Informasi - Kampus Jakarta',
                'nama_fakultas' => 'Rekayasa Industri',
                'kode_prodi' => 'SI',
                'deskripsi' => 'Mempelajari perancangan, pengembangan, dan pengelolaan sistem informasi untuk mendukung proses bisnis dan pengambilan keputusan organisasi.'
            ],
            [
                'nama_prodi' => 'Teknologi Informasi - Kampus Jakarta',
                'nama_fakultas' => 'Informatika',
                'kode_prodi' => 'TI',
                'deskripsi' => 'Fokus pada implementasi dan pengelolaan infrastruktur TI, networking, cloud computing, dan keamanan sistem informasi untuk solusi teknologi terintegrasi.'
            ],
            [
                'nama_prodi' => 'Teknik Telekomunikasi - Kampus Jakarta',
                'nama_fakultas' => 'Teknik Elektro',
                'kode_prodi' => 'Tektel',
                'deskripsi' => 'Mempelajari sistem komunikasi digital, jaringan telekomunikasi, wireless technology, dan infrastruktur telekomunikasi modern.'
            ],
            [
                'nama_prodi' => 'Desain Komunikasi Visual - Kampus Jakarta',
                'nama_fakultas' => 'Industri Kreatif',
                'kode_prodi' => 'DKV',
                'deskripsi' => 'Menggabungkan seni, desain, dan teknologi untuk menciptakan komunikasi visual yang efektif melalui media digital, branding, dan multimedia.'
            ],
        ];

        foreach ($prodis as $prodi) {
            Prodi::updateOrCreate(
                ['kode_prodi' => $prodi['kode_prodi']], // Find by kode_prodi
                $prodi // Update or create with these values
            );
        }
    }
}
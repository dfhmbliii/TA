<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kriteria = [
            [
                'nama_kriteria' => 'Minat',
                'kode_kriteria' => 'MINAT',
                'deskripsi' => 'Ketertarikan siswa terhadap bidang studi',
                'urutan' => 1,
                'bobot' => 0.25,
                'is_active' => true,
            ],
            [
                'nama_kriteria' => 'Bakat',
                'kode_kriteria' => 'BAKAT',
                'deskripsi' => 'Kemampuan bawaan siswa di bidang tertentu',
                'urutan' => 2,
                'bobot' => 0.25,
                'is_active' => true,
            ],
            [
                'nama_kriteria' => 'Nilai Akademik',
                'kode_kriteria' => 'AKADEMIK',
                'deskripsi' => 'Prestasi akademik siswa (IPK, nilai rapor)',
                'urutan' => 3,
                'bobot' => 0.30,
                'is_active' => true,
            ],
            [
                'nama_kriteria' => 'Prospek Karir',
                'kode_kriteria' => 'KARIR',
                'deskripsi' => 'Peluang karir dan perkembangan industri di bidang terkait',
                'urutan' => 4,
                'bobot' => 0.20,
                'is_active' => true,
            ],
        ];

        foreach ($kriteria as $data) {
            \App\Models\Kriteria::firstOrCreate(
                ['kode_kriteria' => $data['kode_kriteria']],
                $data
            );
        }
    }
}

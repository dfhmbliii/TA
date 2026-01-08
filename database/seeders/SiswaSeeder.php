<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample siswa records
        $siswas = [
            [
                'name' => 'Ahmad Rizki',
                'nisn' => '0001234567',
                'email' => 'ahmad@example.com',
                'jurusan_sma' => 'IPA',
                'asal_sekolah' => 'SMA Negeri 1',
                'tahun_lulus' => 2023,
                'ipk' => 3.5,
                'prestasi_score' => 85.0,
                'kepemimpinan' => 80.0,
                'sosial' => 75.0,
                'komunikasi' => 85.0,
                'kreativitas' => 80.0,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'nisn' => '0002345678',
                'email' => 'siti@example.com',
                'jurusan_sma' => 'IPA',
                'asal_sekolah' => 'SMA Negeri 2',
                'tahun_lulus' => 2023,
                'ipk' => 3.8,
                'prestasi_score' => 90.0,
                'kepemimpinan' => 85.0,
                'sosial' => 88.0,
                'komunikasi' => 90.0,
                'kreativitas' => 85.0,
            ],
            [
                'name' => 'Budi Santoso',
                'nisn' => '0003456789',
                'email' => 'budi@example.com',
                'jurusan_sma' => 'IPS',
                'asal_sekolah' => 'SMA Negeri 3',
                'tahun_lulus' => 2023,
                'ipk' => 3.2,
                'prestasi_score' => 78.0,
                'kepemimpinan' => 70.0,
                'sosial' => 82.0,
                'komunikasi' => 80.0,
                'kreativitas' => 75.0,
            ],
        ];

        foreach ($siswas as $siswa) {
            DB::table('siswas')->updateOrInsert(
                ['email' => $siswa['email']],
                array_merge($siswa, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}

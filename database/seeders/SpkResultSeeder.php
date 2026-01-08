<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SpkResult;

class SpkResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch siswa records from actual table used by FK
        $siswas = DB::table('siswas')->get();

        if ($siswas->isEmpty()) {
            $this->command->warn('No siswa records found. Please create Siswa entries first.');
            return;
        }

        // Dummy prodi options
        $prodiList = ['Teknik Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Manajemen', 'Akuntansi'];

        foreach ($siswas as $index => $siswa) {
            // Create a reproducible dummy score profile
            $base = 0.6 + (($index % 5) * 0.05); // 0.60 .. 0.80
            $minat = round(min(1, $base + 0.05), 3);
            $bakat = round(max(0, $base - 0.02), 3);
            $akademik = round(min(1, $base + 0.1), 3);
            $karir = round(max(0, $base - 0.01), 3);

            // Weighted aggregate (example weights summing to 1)
            $weights = [
                'minat' => 0.25,
                'bakat' => 0.25,
                'akademik' => 0.30,
                'karir' => 0.20,
            ];

            $totalScore = round(
                $minat * $weights['minat'] +
                $bakat * $weights['bakat'] +
                $akademik * $weights['akademik'] +
                $karir * $weights['karir'],
                3
            );

            $recommendedProdi = $prodiList[$index % count($prodiList)];

            SpkResult::updateOrCreate(
                ['siswa_id' => $siswa->id, 'category' => 'Dummy'],
                [
                    'weights' => [
                        'minat' => $weights['minat'],
                        'bakat' => $weights['bakat'],
                        'akademik' => $weights['akademik'],
                        'karir' => $weights['karir'],
                    ],
                    'criteria_values' => [
                        'minat' => $minat,
                        'bakat' => $bakat,
                        'akademik' => $akademik,
                        'karir' => $karir,
                        'recommended_prodi' => $recommendedProdi,
                    ],
                    'total_score' => $totalScore,
                ]
            );
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Prodi;
use App\Models\Kriteria;
use App\Models\ProdiAlternative;
use App\Models\SpkResult;
use App\Models\MinatCategory;
use App\Models\BakatCategory;
use App\Models\AkademikCategory;
use App\Models\KarirCategory;

class SiswaSpkController extends Controller
{
    /**
     * Display SPK form for siswa with dropdowns and input fields
     */
    public function showForm()
    {
        // Get kriteria weights from AHP
        $kriteria = Kriteria::orderBy('urutan')->get();

        // Get all prodi with their alternatives
        $prodis = Prodi::with(['alternatives' => function($q) {
            $q->orderBy('urutan');
        }])->orderBy('nama_prodi')->get();

        // Get siswa data
        $siswa = Siswa::where('email', Auth::user()->email)->first();

        // Get options for each kriteria (dynamic)
        $kriteriaOptions = $this->getKriteriaOptions();

        return view('spk.siswa-form', compact('kriteria', 'prodis', 'siswa', 'kriteriaOptions'));
    }

    /**
     * Get dropdown options for kriteria based on kode_kriteria
     */
    private function getKriteriaOptions()
    {
        $options = [];

        // Get Minat categories from database
        $minatCategories = MinatCategory::orderBy('urutan')->get();
        if ($minatCategories->isNotEmpty()) {
            $options['MINAT'] = [];
            foreach ($minatCategories as $cat) {
                $options['MINAT'][$cat->kode] = $cat->nama;
            }
        }

        // Get Bakat categories from database
        $bakatCategories = BakatCategory::orderBy('urutan')->get();
        if ($bakatCategories->isNotEmpty()) {
            $options['BAKAT'] = [];
            foreach ($bakatCategories as $cat) {
                $options['BAKAT'][$cat->kode] = $cat->nama;
            }
        }

        // Get Akademik categories from database
        $akademikCategories = AkademikCategory::orderBy('urutan')->get();
        if ($akademikCategories->isNotEmpty()) {
            $options['AKADEMIK'] = [];
            foreach ($akademikCategories as $cat) {
                $options['AKADEMIK'][$cat->kode] = $cat->nama;
            }
        }

        // Get Karir categories from database
        $karirCategories = KarirCategory::orderBy('urutan')->get();
        if ($karirCategories->isNotEmpty()) {
            $options['KARIR'] = [];
            foreach ($karirCategories as $cat) {
                $options['KARIR'][$cat->kode] = $cat->nama;
            }
        }

        // Fallback to old hardcoded values if database is empty
        if (empty($options['MINAT'])) {
            $options['MINAT'] = [
                'PROC_BIS' => 'Proses Bisnis',
                'PEMROG' => 'Pemrograman',
                'SENI_EST' => 'Seni dan Estetika Visual',
                'PFIS_MAT' => 'Penerapan Fisika dan Matematika'
            ];
        }

        if (empty($options['BAKAT'])) {
            $options['BAKAT'] = [
                'PROB_BIS' => 'Kemampuan memecahkan masalah bisnis (problem-solving)',
                'DEBUG' => 'Ketekunan dan ketelitian tinggi dalam mencari kesalahan teknis (debugging)',
                'ELEC_SIG' => 'Kemampuan menganalisis rangkaian elektronik dan pemrosesan sinyal',
                'VISUAL' => 'Kepekaan visual (rasa estetika) terhadap komposisi, warna, dan tipografi'
            ];
        }

        if (empty($options['KARIR'])) {
            $options['KARIR'] = [
                'SYSAN' => 'System Analyst',
                'SWE' => 'Software Developer/Engineer',
                'NETENG' => 'Network Engineer',
                'GDES' => 'Graphic Designer'
            ];
        }

        return $options;
    }

    /**
     * Calculate SPK recommendation for siswa
     */
    public function calculate(Request $request)
    {
        // Dynamic validation based on existing kriteria
        $kriteria = Kriteria::orderBy('urutan')->get();
        $validationRules = [
            'nilai_mapel' => 'required|array',
            'nilai_mapel.*' => 'required|numeric|min:0|max:100',
        ];

        // Add validation for each kriteria dynamically (exclude Akademik/Nilai)
        foreach ($kriteria as $k) {
            if (in_array($k->kode_kriteria, ['AKADEMIK','NILAI'])) {
                continue;
            }
            $fieldName = 'kriteria_' . strtolower($k->kode_kriteria);
            $validationRules[$fieldName] = 'required|string';
        }

        $data = $request->validate($validationRules);

        // Get siswa
        $siswa = Siswa::where('email', Auth::user()->email)->first();
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
        }

        // Get all prodi with their alternatives
        $prodis = Prodi::with(['alternatives' => function($q) {
            $q->orderBy('urutan');
        }])->get();

        $prodiScores = [];

        foreach ($prodis as $prodi) {
            $score = 0;
            $details = [];

            // Calculate score for each kriteria dynamically
            foreach ($kriteria as $k) {
                $fieldName = 'kriteria_' . strtolower($k->kode_kriteria);
                $kriteriaValue = $data[$fieldName] ?? null;

                // For Akademik/Nilai, use nilai_mapel inputs; otherwise, use selected value
                if ($k->kode_kriteria === 'AKADEMIK' || $k->kode_kriteria === 'NILAI') {
                    $kriteriaScore = $this->getNilaiAkademikScore($data['nilai_mapel'] ?? [], $prodi);
                } elseif ($kriteriaValue) {
                    $kriteriaScore = $this->calculateKriteriaScore($k->kode_kriteria, $kriteriaValue, $prodi, $data['nilai_mapel'] ?? []);

                    $score += $kriteriaScore * $k->bobot;
                    $details[strtolower($k->nama_kriteria)] = [
                        'score' => $kriteriaScore,
                        'weight' => $k->bobot,
                        'weighted' => $kriteriaScore * $k->bobot
                    ];
                }
            }

            $prodiScores[] = [
                'prodi' => $prodi,
                'score' => $score,
                'details' => $details
            ];
        }

        // Sort by score descending
        usort($prodiScores, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Save result for top prodi
        if (count($prodiScores) > 0) {
            $topProdi = $prodiScores[0];

            SpkResult::create([
                'siswa_id' => $siswa->id,
                'total_score' => $topProdi['score'],
                'category' => $this->getCategory($topProdi['score']),
                'rekomendasi_prodi' => $topProdi['prodi']->nama_prodi,
                'criteria_breakdown' => json_encode($topProdi['details'])
            ]);
        }

        return view('spk.siswa-result', compact('prodiScores', 'kriteria', 'data'));
    }

    /**
     * Calculate score for a kriteria dynamically
     */
    private function calculateKriteriaScore($kodeKriteria, $value, $prodi, $nilaiMapel = [])
    {
        switch ($kodeKriteria) {
            case 'MINAT':
                return $this->getMinatScore($value, $prodi);
            case 'BAKAT':
                return $this->getBakatScore($value, $prodi);
            case 'NILAI':
                return $this->getNilaiAkademikScore($nilaiMapel, $prodi);
            case 'PROSPEK':
                return $this->getProspekScore($value, $prodi);
            default:
                // For custom kriteria, return default score
                return 75; // Neutral score
        }
    }

    /**
     * Get minat score for prodi based on selected minat
     */
    private function getMinatScore($minat, $prodi)
    {
        // Mapping minat to prodi
        $minatMapping = [
            'proses_bisnis' => ['Sistem Informasi Bisnis', 'Manajemen Informatika'],
            'pemrograman' => ['Teknik Informatika', 'Sistem Informasi', 'Rekayasa Perangkat Lunak'],
            'seni_estetika' => ['Desain Grafis', 'Desain Komunikasi Visual', 'Multimedia'],
            'fisika_matematika' => ['Teknik Elektro', 'Teknik Komputer', 'Teknik Fisika']
        ];

        // Check if prodi matches minat
        foreach ($minatMapping as $key => $prodiList) {
            if ($minat === $key) {
                foreach ($prodiList as $prodiName) {
                    if (stripos($prodi->nama_prodi, $prodiName) !== false) {
                        return 100; // Perfect match
                    }
                }
            }
        }

        return 50; // Partial match or no match
    }

    /**
     * Get bakat score for prodi based on selected bakat
     */
    private function getBakatScore($bakat, $prodi)
    {
        // Mapping bakat to prodi
        $bakatMapping = [
            'problem_solving' => ['Sistem Informasi', 'Manajemen Informatika'],
            'debugging' => ['Teknik Informatika', 'Rekayasa Perangkat Lunak'],
            'elektronik' => ['Teknik Elektro', 'Teknik Komputer'],
            'kepekaan_visual' => ['Desain Grafis', 'Desain Komunikasi Visual']
        ];

        foreach ($bakatMapping as $key => $prodiList) {
            if ($bakat === $key) {
                foreach ($prodiList as $prodiName) {
                    if (stripos($prodi->nama_prodi, $prodiName) !== false) {
                        return 100;
                    }
                }
            }
        }

        return 50;
    }

    /**
     * Get nilai akademik score based on mata pelajaran inputs
     * Using weighted average based on alternative weights
     */
    private function getNilaiAkademikScore($nilaiMapel, $prodi)
    {
        $alternatives = $prodi->alternatives;

        if ($alternatives->isEmpty()) {
            return 0;
        }

        $totalWeightedScore = 0;
        $totalWeight = 0;

        foreach ($alternatives as $alt) {
            $mapelKey = $this->getMapelKey($alt->nama_alternatif);

            if (isset($nilaiMapel[$mapelKey])) {
                $nilai = floatval($nilaiMapel[$mapelKey]);
                $weight = $alt->bobot ?? 1;

                $totalWeightedScore += $nilai * $weight;
                $totalWeight += $weight;
            }
        }

        return $totalWeight > 0 ? $totalWeightedScore / $totalWeight : 0;
    }

    /**
     * Get prospek karir score
     */
    private function getProspekScore($prospek, $prodi)
    {
        $prospekMapping = [
            'system_analyst' => ['Sistem Informasi', 'Manajemen Informatika'],
            'software_developer' => ['Teknik Informatika', 'Rekayasa Perangkat Lunak'],
            'network_engineer' => ['Teknik Komputer', 'Jaringan Komputer'],
            'graphic_designer' => ['Desain Grafis', 'Desain Komunikasi Visual']
        ];

        foreach ($prospekMapping as $key => $prodiList) {
            if ($prospek === $key) {
                foreach ($prodiList as $prodiName) {
                    if (stripos($prodi->nama_prodi, $prodiName) !== false) {
                        return 100;
                    }
                }
            }
        }

        return 50;
    }

    /**
     * Convert alternative name to mapel key
     */
    private function getMapelKey($alternativeName)
    {
        $mapping = [
            'Bahasa Inggris' => 'bahasa_inggris',
            'Informatika' => 'informatika',
            'Matematika' => 'matematika',
            'Seni Budaya' => 'seni_budaya',
            'Fisika' => 'fisika'
        ];

        foreach ($mapping as $name => $key) {
            if (stripos($alternativeName, $name) !== false) {
                return $key;
            }
        }

        return strtolower(str_replace(' ', '_', $alternativeName));
    }

    /**
     * Get category based on score
     */
    private function getCategory($score)
    {
        if ($score >= 80) return 'Sangat Sesuai';
        if ($score >= 70) return 'Sesuai';
        if ($score >= 60) return 'Cukup Sesuai';
        return 'Kurang Sesuai';
    }
}

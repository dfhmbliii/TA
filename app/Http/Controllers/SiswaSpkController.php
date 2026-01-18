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
     * Calculate SPK recommendation for siswa using AHP weights
     */
    public function calculate(Request $request)
    {
        // Dynamic validation based on existing kriteria
        $kriteria = Kriteria::orderBy('urutan')->get();
        $validationRules = [
            'nilai_mapel' => 'required|array',
            'nilai_mapel.*' => 'required|numeric|min:0|max:100',
        ];

        // Add validation for each kriteria dynamically
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

        // Load AHP categories with weights from database
        $minatCategories = MinatCategory::orderBy('urutan')->get()->keyBy('kode');
        $bakatCategories = BakatCategory::orderBy('urutan')->get()->keyBy('kode');
        $karirCategories = KarirCategory::orderBy('urutan')->get()->keyBy('kode');
        $akademikCategories = AkademikCategory::orderBy('urutan')->get()->keyBy('kode');

        $prodiScores = [];

        foreach ($prodis as $prodi) {
            $score = 0;
            $details = [];

            // Calculate score for each kriteria dynamically
            foreach ($kriteria as $k) {
                $kriteriaValue = null;
                $kriteriaScore = 0;
                
                // Get field name
                $fieldName = 'kriteria_' . strtolower($k->kode_kriteria);
                
                if ($k->kode_kriteria === 'MINAT') {
                    $kriteriaValue = $data[$fieldName] ?? null;
                    $kriteriaScore = $this->getMinatScoreWithWeights($kriteriaValue, $prodi, $minatCategories);
                } elseif ($k->kode_kriteria === 'BAKAT') {
                    $kriteriaValue = $data[$fieldName] ?? null;
                    $kriteriaScore = $this->getBakatScoreWithWeights($kriteriaValue, $prodi, $bakatCategories);
                } elseif ($k->kode_kriteria === 'KARIR') {
                    $kriteriaValue = $data[$fieldName] ?? null;
                    $kriteriaScore = $this->getProspekScoreWithWeights($kriteriaValue, $prodi, $karirCategories);
                } elseif ($k->kode_kriteria === 'AKADEMIK') {
                    $kriteriaScore = $this->getNilaiAkademikScore($data['nilai_mapel'] ?? [], $prodi);
                }

                // Add to total score using kriteria bobot (AHP weight from database)
                if ($kriteriaScore > 0 || $k->kode_kriteria === 'AKADEMIK') {
                    $score += $kriteriaScore * $k->bobot;
                    
                    // Store detailed breakdown
                    $criteriaKey = strtolower($k->kode_kriteria);
                    if ($k->kode_kriteria === 'KARIR') {
                        $criteriaKey = 'prospek_karir';
                    }
                    
                    $details[$criteriaKey] = [
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

            // Prepare criteria_values and weights from details
            $criteriaValues = [];
            $weights = [];
            
            foreach ($topProdi['details'] as $key => $detail) {
                $criteriaValues[$key] = $detail['score'];
                $weights[$key] = round($detail['weight'] * 100, 2); // Convert to percentage
            }

            // Prepare input data for storage
            $inputData = [
                'minat' => $data['kriteria_minat'] ?? null,
                'bakat' => $data['kriteria_bakat'] ?? null,
                'prospek_karir' => $data['kriteria_karir'] ?? null,
                'nilai_mapel' => $data['nilai_mapel'] ?? []
            ];

            // Prepare all prodi recommendations (top 5)
            $rekomendasiProdi = [];
            $topProdiScores = array_slice($prodiScores, 0, 5);
            foreach ($topProdiScores as $index => $prodiScore) {
                $rekomendasiProdi[] = [
                    'nama_prodi' => $prodiScore['prodi']->nama_prodi,
                    'score' => $prodiScore['score'],
                    'percentage' => ($prodiScore['score'] / ($topProdi['score'] ?: 1)) * 100,
                    'rank' => $index + 1
                ];
            }

            SpkResult::create([
                'siswa_id' => $siswa->id,
                'total_score' => $topProdi['score'],
                'category' => $this->getCategory($topProdi['score']),
                'rekomendasi_prodi' => json_encode($rekomendasiProdi),
                'criteria_breakdown' => json_encode($topProdi['details']),
                'criteria_values' => json_encode($criteriaValues),
                'weights' => json_encode($weights),
                'input_data' => json_encode($inputData)
            ]);
        }

        return view('spk.siswa-result', compact('prodiScores', 'kriteria', 'data'));
    }

    /**
     * Get minat score using AHP weights and matching logic
     * Score = Σ(Category Weight × Match Score)
     */
    private function getMinatScoreWithWeights($selectedMinatKode, $prodi, $minatCategories)
    {
        if (!$selectedMinatKode) {
            return 0;
        }

        $score = 0;
        $prodiName = strtolower($prodi->nama_prodi);

        // Mapping minat kode ke nama prodi yang sesuai - lebih spesifik
        $minatProdiMapping = [
            'PROC_BIS' => [
                'high' => ['sistem informasi'],
                'medium' => ['bisnis', 'manajemen'],
                'low' => []
            ],
            'PEMROG' => [
                'high' => ['teknik informatika', 'teknologi informasi'],
                'medium' => ['informatika', 'rekayasa perangkat lunak'],
                'low' => ['sistem informasi']
            ],
            'SENI_EST' => [
                'high' => ['desain komunikasi visual', 'desain grafis'],
                'medium' => ['multimedia', 'seni'],
                'low' => []
            ],
            'PFIS_MAT' => [
                'high' => ['teknik telekomunikasi', 'teknik elektro'],
                'medium' => ['fisika', 'teknik komputer'],
                'low' => []
            ]
        ];

        // Check if selected minat matches this prodi
        $matchScore = 0;
        if (isset($minatProdiMapping[$selectedMinatKode])) {
            $mapping = $minatProdiMapping[$selectedMinatKode];
            
            // Check high priority matches
            foreach ($mapping['high'] as $keyword) {
                if (stripos($prodiName, $keyword) !== false) {
                    $matchScore = 100;
                    break;
                }
            }
            
            // Check medium priority matches
            if ($matchScore === 0) {
                foreach ($mapping['medium'] as $keyword) {
                    if (stripos($prodiName, $keyword) !== false) {
                        $matchScore = 70;
                        break;
                    }
                }
            }
            
            // Check low priority matches (penalti)
            if ($matchScore === 0) {
                foreach ($mapping['low'] as $keyword) {
                    if (stripos($prodiName, $keyword) !== false) {
                        $matchScore = 30;
                        break;
                    }
                }
            }
            
            // No match at all
            if ($matchScore === 0) {
                $matchScore = 20;
            }
        }

        // Use category weight from AHP
        if (isset($minatCategories[$selectedMinatKode])) {
            $categoryWeight = floatval($minatCategories[$selectedMinatKode]->bobot ?? 0);
            $score = $matchScore * $categoryWeight; // matchScore already 0-100, weight is 0-1
        } else {
            $score = $matchScore;
        }

        return $score; // Already in correct range
    }

    /**
     * Get bakat score using AHP weights and matching logic
     */
    private function getBakatScoreWithWeights($selectedBakatKode, $prodi, $bakatCategories)
    {
        if (!$selectedBakatKode) {
            return 0;
        }

        $score = 0;
        $prodiName = strtolower($prodi->nama_prodi);

        // Mapping bakat kode ke nama prodi yang sesuai - lebih spesifik
        $bakatProdiMapping = [
            'PROB_BIS' => [
                'high' => ['sistem informasi'],
                'medium' => ['bisnis', 'manajemen'],
                'low' => []
            ],
            'DEBUG' => [
                'high' => ['teknik informatika', 'teknologi informasi'],
                'medium' => ['informatika', 'rekayasa perangkat lunak'],
                'low' => ['sistem informasi']
            ],
            'ELEC_SIG' => [
                'high' => ['teknik telekomunikasi', 'teknik elektro'],
                'medium' => ['fisika', 'teknik komputer'],
                'low' => []
            ],
            'VISUAL' => [
                'high' => ['desain komunikasi visual', 'desain grafis'],
                'medium' => ['multimedia', 'seni'],
                'low' => []
            ]
        ];

        // Check if selected bakat matches this prodi
        $matchScore = 0;
        if (isset($bakatProdiMapping[$selectedBakatKode])) {
            $mapping = $bakatProdiMapping[$selectedBakatKode];
            
            // Check high priority matches
            foreach ($mapping['high'] as $keyword) {
                if (stripos($prodiName, $keyword) !== false) {
                    $matchScore = 100;
                    break;
                }
            }
            
            // Check medium priority matches
            if ($matchScore === 0) {
                foreach ($mapping['medium'] as $keyword) {
                    if (stripos($prodiName, $keyword) !== false) {
                        $matchScore = 70;
                        break;
                    }
                }
            }
            
            // Check low priority matches (penalti)
            if ($matchScore === 0) {
                foreach ($mapping['low'] as $keyword) {
                    if (stripos($prodiName, $keyword) !== false) {
                        $matchScore = 30;
                        break;
                    }
                }
            }
            
            // No match at all
            if ($matchScore === 0) {
                $matchScore = 20;
            }
        }

        // Use category weight from AHP
        if (isset($bakatCategories[$selectedBakatKode])) {
            $categoryWeight = floatval($bakatCategories[$selectedBakatKode]->bobot ?? 0);
            $score = $matchScore * $categoryWeight; // matchScore already 0-100, weight is 0-1
        } else {
            $score = $matchScore;
        }

        return $score; // Already in correct range
    }

    /**
     * Get prospek karir score using AHP weights
     */
    private function getProspekScoreWithWeights($selectedKarirKode, $prodi, $karirCategories)
    {
        if (!$selectedKarirKode) {
            return 0;
        }

        $score = 0;
        $prodiName = strtolower($prodi->nama_prodi);

        // Mapping karir kode ke nama prodi yang sesuai - lebih spesifik
        $karirProdiMapping = [
            'SYSAN' => [
                'high' => ['sistem informasi'],
                'medium' => ['teknologi informasi'],
                'low' => ['teknik informatika']
            ],
            'SWE' => [
                'high' => ['teknik informatika', 'teknologi informasi'],
                'medium' => ['informatika', 'rekayasa perangkat lunak'],
                'low' => ['sistem informasi']
            ],
            'NETENG' => [
                'high' => ['teknik telekomunikasi', 'teknik komputer'],
                'medium' => ['jaringan', 'teknologi informasi'],
                'low' => []
            ],
            'GDES' => [
                'high' => ['desain komunikasi visual', 'desain grafis'],
                'medium' => ['multimedia', 'seni'],
                'low' => []
            ]
        ];

        // Check if selected karir matches this prodi
        $matchScore = 0;
        if (isset($karirProdiMapping[$selectedKarirKode])) {
            $mapping = $karirProdiMapping[$selectedKarirKode];
            
            // Check high priority matches
            foreach ($mapping['high'] as $keyword) {
                if (stripos($prodiName, $keyword) !== false) {
                    $matchScore = 100;
                    break;
                }
            }
            
            // Check medium priority matches
            if ($matchScore === 0) {
                foreach ($mapping['medium'] as $keyword) {
                    if (stripos($prodiName, $keyword) !== false) {
                        $matchScore = 70;
                        break;
                    }
                }
            }
            
            // Check low priority matches (penalti)
            if ($matchScore === 0) {
                foreach ($mapping['low'] as $keyword) {
                    if (stripos($prodiName, $keyword) !== false) {
                        $matchScore = 30;
                        break;
                    }
                }
            }
            
            // No match at all
            if ($matchScore === 0) {
                $matchScore = 20;
            }
        }

        // Use category weight from AHP
        if (isset($karirCategories[$selectedKarirKode])) {
            $categoryWeight = floatval($karirCategories[$selectedKarirKode]->bobot ?? 0);
            $score = $matchScore * $categoryWeight; // matchScore already 0-100, weight is 0-1
        } else {
            $score = $matchScore;
        }

        return $score; // Already in correct range
    }

    /**
     * Get nilai akademik score based on mata pelajaran inputs
     * Using simple average of all input nilai_mapel (0-100 scale)
     */
    private function getNilaiAkademikScore($nilaiMapel, $prodi)
    {
        if (empty($nilaiMapel)) {
            return 0;
        }

        // Calculate simple average of all input nilai mapel
        $totalNilai = 0;
        $count = 0;

        foreach ($nilaiMapel as $key => $nilai) {
            if ($nilai !== null && $nilai !== '') {
                $totalNilai += floatval($nilai);
                $count++;
            }
        }

        return $count > 0 ? $totalNilai / $count : 0;
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
        // Adjusted thresholds based on weighted score calculation
        // Total score range: 0-100 (after applying kriteria weights)
        if ($score >= 35) return 'Sangat Sesuai';
        if ($score >= 28) return 'Sesuai';
        if ($score >= 20) return 'Cukup Sesuai';
        return 'Kurang Sesuai';
    }
}

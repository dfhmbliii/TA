<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\PairwiseComparison;

class SpkAnalysisController extends Controller
{
    /**
     * Display SPK Analysis page with criteria management
     */
    public function index()
    {
        $kriteria = Kriteria::orderBy('urutan')->get();
        $matrix = $this->buildPairwiseMatrix($kriteria);

        return view('spk.analysis', compact('kriteria', 'matrix'));
    }

    /**
     * Store or update pairwise comparison values
     */
    public function storePairwise(Request $request)
    {
        $data = $request->validate([
            'comparisons' => 'required|array',
            'comparisons.*.kriteria_1_id' => 'required|exists:kriteria,id',
            'comparisons.*.kriteria_2_id' => 'required|exists:kriteria,id',
            'comparisons.*.nilai' => 'required|numeric|min:0.11|max:9',
        ]);

        foreach ($data['comparisons'] as $comparison) {
            PairwiseComparison::updateOrCreate(
                [
                    'kriteria_1_id' => $comparison['kriteria_1_id'],
                    'kriteria_2_id' => $comparison['kriteria_2_id'],
                ],
                ['nilai' => $comparison['nilai']]
            );

            // Store reciprocal value
            PairwiseComparison::updateOrCreate(
                [
                    'kriteria_1_id' => $comparison['kriteria_2_id'],
                    'kriteria_2_id' => $comparison['kriteria_1_id'],
                ],
                ['nilai' => 1 / $comparison['nilai']]
            );
        }

        // Calculate AHP weights
        $this->calculateAHPWeights();

        return redirect()->route('spk.analysis')->with('success', 'Perbandingan berhasil disimpan dan bobot telah dihitung.');
    }

    /**
     * Recalculate and apply current AHP weights based on stored comparisons
     */
    public function applyCurrentWeights()
    {
        $this->calculateAHPWeights();
        return redirect()->route('spk.analysis')->with('success', 'Bobot AHP telah diterapkan dari perbandingan tersimpan.');
    }

    /**
     * Build pairwise comparison matrix
     */
    private function buildPairwiseMatrix($kriteria)
    {
        $matrix = [];

        foreach ($kriteria as $k1) {
            foreach ($kriteria as $k2) {
                if ($k1->id == $k2->id) {
                    $matrix[$k1->id][$k2->id] = 1;
                } else {
                    $comparison = PairwiseComparison::where('kriteria_1_id', $k1->id)
                        ->where('kriteria_2_id', $k2->id)
                        ->first();

                    $matrix[$k1->id][$k2->id] = $comparison ? (float)$comparison->nilai : 1;
                }
            }
        }

        return $matrix;
    }

    /**
     * Calculate AHP weights using eigenvector method
     */
    private function calculateAHPWeights()
    {
        $kriteria = Kriteria::orderBy('urutan')->get();
        $matrix = $this->buildPairwiseMatrix($kriteria);
        $n = count($kriteria);

        if ($n == 0) return;

        // Step 1: Normalize matrix (sum each column and divide each element by column sum)
        $colSums = [];
        foreach ($kriteria as $k) {
            $colSums[$k->id] = 0;
            foreach ($kriteria as $k2) {
                $colSums[$k->id] += $matrix[$k2->id][$k->id];
            }
        }

        $normalizedMatrix = [];
        foreach ($kriteria as $k1) {
            foreach ($kriteria as $k2) {
                $normalizedMatrix[$k1->id][$k2->id] = $matrix[$k1->id][$k2->id] / $colSums[$k2->id];
            }
        }

        // Step 2: Calculate average of each row (priority vector / weights)
        $weights = [];
        foreach ($kriteria as $k) {
            $rowSum = 0;
            foreach ($kriteria as $k2) {
                $rowSum += $normalizedMatrix[$k->id][$k2->id];
            }
            $weights[$k->id] = $rowSum / $n;

            // Update kriteria weight
            $k->update(['bobot' => $weights[$k->id]]);
        }

        // Step 3: Calculate Consistency Ratio (CR)
        $this->calculateConsistencyRatio($matrix, $weights, $kriteria);

        return $weights;
    }

    /**
     * Calculate Consistency Ratio to check if comparisons are consistent
     */
    private function calculateConsistencyRatio($matrix, $weights, $kriteria)
    {
        $n = count($kriteria);

        // Calculate weighted sum vector
        $weightedSum = [];
        foreach ($kriteria as $k1) {
            $sum = 0;
            foreach ($kriteria as $k2) {
                $sum += $matrix[$k1->id][$k2->id] * $weights[$k2->id];
            }
            $weightedSum[$k1->id] = $sum;
        }

        // Calculate λmax (lambda max)
        $lambdaMax = 0;
        foreach ($kriteria as $k) {
            $lambdaMax += $weightedSum[$k->id] / $weights[$k->id];
        }
        $lambdaMax = $lambdaMax / $n;

        // Calculate Consistency Index (CI)
        $ci = ($lambdaMax - $n) / ($n - 1);

        // Random Index (RI) values for n=1 to 10
        $ri = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
        $randomIndex = $ri[$n - 1] ?? 1.49;

        // Calculate Consistency Ratio (CR)
        $cr = $ci / $randomIndex;

        // Store CR in session for display
        session(['ahp_consistency_ratio' => $cr, 'ahp_lambda_max' => $lambdaMax]);

        return $cr;
    }

    /**
     * Initialize default criteria
     */
    public function initializeCriteria()
    {
        $defaultCriteria = [
            ['nama_kriteria' => 'Minat', 'kode_kriteria' => 'MINAT', 'deskripsi' => 'Ketertarikan siswa terhadap bidang studi', 'urutan' => 1],
            ['nama_kriteria' => 'Bakat', 'kode_kriteria' => 'BAKAT', 'deskripsi' => 'Kemampuan bawaan siswa di bidang tertentu', 'urutan' => 2],
            ['nama_kriteria' => 'Nilai Akademik', 'kode_kriteria' => 'AKADEMIK', 'deskripsi' => 'Prestasi akademik siswa', 'urutan' => 3],
            ['nama_kriteria' => 'Prospek Karir', 'kode_kriteria' => 'KARIR', 'deskripsi' => 'Peluang karir di bidang terkait', 'urutan' => 4],
        ];

        foreach ($defaultCriteria as $data) {
            Kriteria::firstOrCreate(
                ['kode_kriteria' => $data['kode_kriteria']],
                $data
            );
        }

        return redirect()->route('spk.analysis')->with('success', 'Kriteria default berhasil diinisialisasi.');
    }

    /**
     * Update kriteria
     */
    public function updateKriteria(Request $request, $id)
    {
        $kriteria = Kriteria::findOrFail($id);

        $data = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'kode_kriteria' => 'required|string|max:50|unique:kriteria,kode_kriteria,' . $id,
            'deskripsi' => 'nullable|string',
        ]);

        $kriteria->update($data);

        return redirect()->route('spk.analysis')->with('success', 'Kriteria berhasil diupdate.');
    }

    /**
     * Delete kriteria
     */
    public function deleteKriteria($id)
    {
        $kriteria = Kriteria::findOrFail($id);

        // Delete related pairwise comparisons
        PairwiseComparison::where('kriteria_1_id', $id)
            ->orWhere('kriteria_2_id', $id)
            ->delete();

        $kriteria->delete();

        return redirect()->route('spk.analysis')->with('success', 'Kriteria berhasil dihapus.');
    }
}

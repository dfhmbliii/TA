<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\ProdiAlternative;
use App\Models\ProdiPairwiseComparison;

class ProdiAnalysisController extends Controller
{
    /**
     * Display prodi analysis page with alternatives matrix
     */
    public function index($prodiId)
    {
        $prodi = Prodi::findOrFail($prodiId);
        $alternatives = ProdiAlternative::where('prodi_id', $prodiId)
            ->orderBy('urutan')
            ->get();
        
        $matrix = $this->buildPairwiseMatrix($alternatives, $prodiId);
        
        return view('spk.prodi-analysis', compact('prodi', 'alternatives', 'matrix'));
    }

    /**
     * List all prodis for analysis selection
     */
    public function list()
    {
        $prodis = Prodi::with(['alternatives' => function($q) {
            $q->orderBy('urutan');
        }])->get();
        return view('spk.prodi-list', compact('prodis'));
    }

    /**
     * Store or update pairwise comparison values for prodi alternatives
     */
    public function storePairwise(Request $request, $prodiId)
    {
        $prodi = Prodi::findOrFail($prodiId);
        
        $data = $request->validate([
            'comparisons' => 'required|array',
            'comparisons.*.alternative_1_id' => 'required|exists:prodi_alternatives,id',
            'comparisons.*.alternative_2_id' => 'required|exists:prodi_alternatives,id',
            'comparisons.*.nilai' => 'required|numeric|min:0.11|max:9',
        ]);

        foreach ($data['comparisons'] as $comparison) {
            ProdiPairwiseComparison::updateOrCreate(
                [
                    'prodi_id' => $prodiId,
                    'alternative_1_id' => $comparison['alternative_1_id'],
                    'alternative_2_id' => $comparison['alternative_2_id'],
                ],
                ['nilai' => $comparison['nilai']]
            );

            // Store reciprocal value
            ProdiPairwiseComparison::updateOrCreate(
                [
                    'prodi_id' => $prodiId,
                    'alternative_1_id' => $comparison['alternative_2_id'],
                    'alternative_2_id' => $comparison['alternative_1_id'],
                ],
                ['nilai' => 1 / $comparison['nilai']]
            );
        }

        // Calculate AHP weights for alternatives
        $this->calculateAHPWeights($prodiId);

        return redirect()->route('prodi.analysis', $prodiId)
            ->with('success', 'Perbandingan alternatif berhasil disimpan dan bobot telah dihitung.');
    }

    /**
     * Build pairwise comparison matrix
     */
    private function buildPairwiseMatrix($alternatives, $prodiId)
    {
        $matrix = [];
        
        foreach ($alternatives as $a1) {
            foreach ($alternatives as $a2) {
                if ($a1->id == $a2->id) {
                    $matrix[$a1->id][$a2->id] = 1;
                } else {
                    $comparison = ProdiPairwiseComparison::where('prodi_id', $prodiId)
                        ->where('alternative_1_id', $a1->id)
                        ->where('alternative_2_id', $a2->id)
                        ->first();
                    
                    $matrix[$a1->id][$a2->id] = $comparison ? (float)$comparison->nilai : 1;
                }
            }
        }
        
        return $matrix;
    }

    /**
     * Calculate AHP weights for alternatives
     */
    private function calculateAHPWeights($prodiId)
    {
        $alternatives = ProdiAlternative::where('prodi_id', $prodiId)
            ->orderBy('urutan')
            ->get();
        $matrix = $this->buildPairwiseMatrix($alternatives, $prodiId);
        $n = count($alternatives);
        
        if ($n == 0) return;

        // Step 1: Normalize matrix
        $colSums = [];
        foreach ($alternatives as $a) {
            $colSums[$a->id] = 0;
            foreach ($alternatives as $a2) {
                $colSums[$a->id] += $matrix[$a2->id][$a->id];
            }
        }

        $normalizedMatrix = [];
        foreach ($alternatives as $a1) {
            foreach ($alternatives as $a2) {
                $normalizedMatrix[$a1->id][$a2->id] = $matrix[$a1->id][$a2->id] / $colSums[$a2->id];
            }
        }

        // Step 2: Calculate weights (priority vector)
        $weights = [];
        foreach ($alternatives as $a) {
            $rowSum = 0;
            foreach ($alternatives as $a2) {
                $rowSum += $normalizedMatrix[$a->id][$a2->id];
            }
            $weights[$a->id] = $rowSum / $n;
            
            // Update alternative weight
            $a->update(['bobot' => $weights[$a->id]]);
        }

        // Step 3: Calculate Consistency Ratio
        $this->calculateConsistencyRatio($matrix, $weights, $alternatives, $prodiId);
        
        return $weights;
    }

    /**
     * Calculate Consistency Ratio
     */
    private function calculateConsistencyRatio($matrix, $weights, $alternatives, $prodiId)
    {
        $n = count($alternatives);
        
        // Calculate weighted sum vector
        $weightedSum = [];
        foreach ($alternatives as $a1) {
            $sum = 0;
            foreach ($alternatives as $a2) {
                $sum += $matrix[$a1->id][$a2->id] * $weights[$a2->id];
            }
            $weightedSum[$a1->id] = $sum;
        }

        // Calculate λmax
        $lambdaMax = 0;
        foreach ($alternatives as $a) {
            $lambdaMax += $weightedSum[$a->id] / $weights[$a->id];
        }
        $lambdaMax = $lambdaMax / $n;

        // Calculate CI
        $ci = ($lambdaMax - $n) / ($n - 1);

        // Random Index
        $ri = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
        $randomIndex = $ri[$n - 1] ?? 1.49;

        // Calculate CR
        $cr = $ci / $randomIndex;

        // Store in session
        session([
            "prodi_{$prodiId}_cr" => $cr,
            "prodi_{$prodiId}_lambda" => $lambdaMax
        ]);

        return $cr;
    }

    /**
     * Initialize default alternatives for a prodi
     */
    public function initializeAlternatives(Request $request, $prodiId)
    {
        $prodi = Prodi::findOrFail($prodiId);
        
        $data = $request->validate([
            'alternatives' => 'required|array|min:2',
            'alternatives.*.nama' => 'required|string|max:255',
            'alternatives.*.kode' => 'required|string|max:50',
        ]);

        $urutan = 1;
        foreach ($data['alternatives'] as $alt) {
            ProdiAlternative::firstOrCreate(
                [
                    'prodi_id' => $prodiId,
                    'kode_alternatif' => $alt['kode'],
                ],
                [
                    'nama_alternatif' => $alt['nama'],
                    'urutan' => $urutan++,
                    'bobot' => 0,
                ]
            );
        }

        return redirect()->route('prodi.analysis', $prodiId)
            ->with('success', 'Alternatif berhasil diinisialisasi.');
    }

    /**
     * Update alternative
     */
    public function updateAlternative(Request $request, $prodiId, $altId)
    {
        $alternative = ProdiAlternative::where('prodi_id', $prodiId)->findOrFail($altId);
        
        $data = $request->validate([
            'nama_alternatif' => 'required|string|max:255',
            'kode_alternatif' => 'required|string|max:50',
        ]);

        $alternative->update($data);

        return redirect()->route('prodi.analysis', $prodiId)
            ->with('success', 'Mata pelajaran berhasil diupdate.');
    }

    /**
     * Delete alternative
     */
    public function deleteAlternative($prodiId, $altId)
    {
        $alternative = ProdiAlternative::where('prodi_id', $prodiId)->findOrFail($altId);
        
        // Delete related pairwise comparisons
        ProdiPairwiseComparison::where('prodi_id', $prodiId)
            ->where(function($q) use ($altId) {
                $q->where('alternative_1_id', $altId)
                  ->orWhere('alternative_2_id', $altId);
            })
            ->delete();
        
        $alternative->delete();

        return redirect()->route('prodi.analysis', $prodiId)
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkademikCategory;
use App\Models\AkademikPairwiseComparison;

class AkademikAnalysisController extends Controller
{
    public function index()
    {
        $categories = AkademikCategory::orderBy('urutan')->get();
        $matrix = $this->buildMatrix($categories);
        return view('spk.akademik-analysis', compact('categories', 'matrix'));
    }

    public function initialize()
    {
        $defaults = [
            ['nama' => 'Bahasa Inggris', 'kode' => 'BING', 'urutan' => 1],
            ['nama' => 'Informatika', 'kode' => 'INFORMATIKA', 'urutan' => 2],
            ['nama' => 'Matematika', 'kode' => 'MTK', 'urutan' => 3],
            ['nama' => 'Seni Budaya', 'kode' => 'SENBUD', 'urutan' => 4],
        ];
        foreach ($defaults as $d) {
            AkademikCategory::firstOrCreate(['kode' => $d['kode']], $d);
        }
        return redirect()->route('spk.akademik.index')->with('success', 'Kategori Nilai Akademik berhasil diinisialisasi.');
    }

    public function storePairwise(Request $request)
    {
        $data = $request->validate([
            'comparisons' => 'required|array',
            'comparisons.*.category_1_id' => 'required|exists:akademik_categories,id',
            'comparisons.*.category_2_id' => 'required|exists:akademik_categories,id',
            'comparisons.*.nilai' => 'required|numeric|min:0.10|max:9',
        ]);

        foreach ($data['comparisons'] as $c) {
            AkademikPairwiseComparison::updateOrCreate(
                ['category_1_id' => $c['category_1_id'], 'category_2_id' => $c['category_2_id']],
                ['nilai' => $c['nilai']]
            );
            AkademikPairwiseComparison::updateOrCreate(
                ['category_1_id' => $c['category_2_id'], 'category_2_id' => $c['category_1_id']],
                ['nilai' => 1 / $c['nilai']]
            );
        }

        $this->calculateWeights();
        return redirect()->route('spk.akademik.index')->with('success', 'Perbandingan Nilai Akademik disimpan.');
    }

    public function applyWeights()
    {
        $this->calculateWeights();
        return redirect()->route('spk.akademik.index')->with('success', 'Bobot Nilai Akademik telah diterapkan dari perbandingan tersimpan.');
    }

    private function buildMatrix($categories)
    {
        $matrix = [];
        foreach ($categories as $c1) {
            foreach ($categories as $c2) {
                if ($c1->id === $c2->id) {
                    $matrix[$c1->id][$c2->id] = 1;
                } else {
                    $cmp = AkademikPairwiseComparison::where('category_1_id', $c1->id)
                        ->where('category_2_id', $c2->id)
                        ->first();
                    $matrix[$c1->id][$c2->id] = $cmp ? (float)$cmp->nilai : 1;
                }
            }
        }
        return $matrix;
    }

    private function calculateWeights()
    {
        $categories = AkademikCategory::orderBy('urutan')->get();
        $matrix = $this->buildMatrix($categories);
        $n = count($categories);
        if ($n === 0) return;

        $colSums = [];
        foreach ($categories as $c) {
            $colSums[$c->id] = 0;
            foreach ($categories as $c2) {
                $colSums[$c->id] += $matrix[$c2->id][$c->id];
            }
        }

        $weights = [];
        foreach ($categories as $c1) {
            $rowSum = 0;
            foreach ($categories as $c2) {
                $rowSum += $matrix[$c1->id][$c2->id] / $colSums[$c2->id];
            }
            $weights[$c1->id] = $rowSum / $n;
            $c1->update(['bobot' => $weights[$c1->id]]);
        }

        // Calculate and store Consistency Ratio (CR)
        $this->calculateConsistencyRatio($matrix, $weights, $categories);

        return $weights;
    }

    private function calculateConsistencyRatio(array $matrix, array $weights, $categories)
    {
        $n = count($categories);
        if ($n < 2) {
            session(['akademik_cr' => 0, 'akademik_lambda_max' => $n]);
            return 0;
        }

        // Weighted sum vector
        $weightedSum = [];
        foreach ($categories as $c1) {
            $sum = 0;
            foreach ($categories as $c2) {
                $sum += $matrix[$c1->id][$c2->id] * $weights[$c2->id];
            }
            $weightedSum[$c1->id] = $sum;
        }

        // Lambda max
        $lambdaMax = 0;
        foreach ($categories as $c) {
            if ($weights[$c->id] == 0) continue;
            $lambdaMax += $weightedSum[$c->id] / $weights[$c->id];
        }
        $lambdaMax = $lambdaMax / $n;

        // Consistency Index (CI)
        $ci = ($lambdaMax - $n) / ($n - 1);

        // Random Index (RI) for n=1..10
        $ri = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
        $randomIndex = $ri[$n - 1] ?? 1.49;

        // Consistency Ratio (CR)
        $cr = $randomIndex == 0 ? 0 : $ci / $randomIndex;

        session(['akademik_cr' => $cr, 'akademik_lambda_max' => $lambdaMax]);
        return $cr;
    }
}

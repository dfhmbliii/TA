<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\SpkResult;
use App\Models\MinatCategory;
use App\Models\BakatCategory;
use App\Models\KarirCategory;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Show SPK Analysis Reports page
     */
    public function spkAnalysis()
    {
        $siswas = Siswa::orderBy('name')->get();
        return view('reports.spk-analysis', compact('siswas'));
    }

    /**
     * Get SPK Analysis data as JSON (for AJAX)
     */
    public function getSpkAnalysisData(Request $request)
    {
        $query = SpkResult::with('siswa');

        if ($request->siswa_id) {
            $query->where('siswa_id', $request->siswa_id);
        }

        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date . '-01');
        }

        if ($request->to_date) {
            // Add one month to to_date for inclusive range
            $toDate = date('Y-m-d', strtotime($request->to_date . '-01 +1 month'));
            $query->whereDate('created_at', '<', $toDate);
        }

        $results = $query->orderByDesc('created_at')->get()->map(function ($item) {
            $prodiRec = $item->rekomendasi_prodi;
            if (is_string($prodiRec)) {
                $prodiRec = json_decode($prodiRec, true);
            }
            // Check if it's an array and has at least one item (the top recommendation)
            $prodiName = '-';
            if (is_array($prodiRec) && count($prodiRec) > 0) {
                $prodiName = $prodiRec[0]['nama_prodi'] ?? '-';
            }
            
            return [
                'id' => $item->id,
                'siswa_name' => $item->siswa->name,
                'nisn' => $item->siswa->nisn,
                'total_score' => number_format($item->total_score, 2),
                'category' => $item->category,
                'rekomendasi_prodi' => $prodiName,
                'created_at' => $item->created_at->format('d F Y, H:i'),
            ];
        });

        return response()->json(['results' => $results]);
    }

    /**
     * Get SPK Analysis detail as JSON
     */
    public function getSpkAnalysisDetail($id)
    {
        $result = SpkResult::with('siswa')->findOrFail($id);

        // Ensure data is properly decoded
        $criteriaValues = $result->criteria_values;
        if (is_string($criteriaValues)) {
            $criteriaValues = json_decode($criteriaValues, true);
        }

        $weights = $result->weights;
        if (is_string($weights)) {
            $weights = json_decode($weights, true);
        }

        $inputData = $result->input_data;
        if (is_string($inputData)) {
            $inputData = json_decode($inputData, true);
        }

        // Resolve category names
        if ($inputData) {
            if (isset($inputData['minat'])) {
                $cat = MinatCategory::where('kode', $inputData['minat'])->first();
                $inputData['minat_text'] = $cat ? $cat->nama : ucwords(str_replace('_', ' ', $inputData['minat']));
            }
            if (isset($inputData['bakat'])) {
                $cat = BakatCategory::where('kode', $inputData['bakat'])->first();
                $inputData['bakat_text'] = $cat ? $cat->nama : ucwords(str_replace('_', ' ', $inputData['bakat']));
            }
            if (isset($inputData['prospek_karir'])) {
                $cat = KarirCategory::where('kode', $inputData['prospek_karir'])->first();
                $inputData['karir_text'] = $cat ? $cat->nama : ucwords(str_replace('_', ' ', $inputData['prospek_karir']));
            }
        }

        // Format criteria values to 2 decimals
        $formattedCriteria = [];
        if (is_array($criteriaValues)) {
            foreach ($criteriaValues as $key => $val) {
                $formattedCriteria[$key] = number_format((float)$val, 2);
            }
        }

        return response()->json([
            'id' => $result->id,
            'siswa_name' => $result->siswa->name,
            'nisn' => $result->siswa->nisn,
            'total_score' => number_format($result->total_score, 2),
            'category' => $result->category,
            'rekomendasi_prodi' => $result->rekomendasi_prodi,
            'criteria_values' => $formattedCriteria,
            'weights' => $weights,
            'input_data' => $inputData,
            'created_at' => $result->created_at->format('d F Y, H:i:s'),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Prodi;
use App\Models\Siswa;

class ReportController extends Controller
{
    /**
     * Show the Prodi population report page.
     */
    public function prodi(Request $request)
    {
        $prodis = Prodi::orderBy('nama_prodi')->get(['id','nama_prodi']);
        $years = $this->availableYears();
        return view('reports.prodi', compact('prodis','years'));
    }

    /**
     * JSON endpoint returning counts per prodi per year.
     * Params: from (year), to (year), prodi_id (optional), includeEmpty (bool)
     */
    public function prodiData(Request $request)
    {
        $from = (int)($request->input('from') ?: date('Y') - 4);
        $to = (int)($request->input('to') ?: date('Y'));
        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $prodiId = $request->input('prodi_id');
        $includeEmpty = filter_var($request->input('includeEmpty', true), FILTER_VALIDATE_BOOLEAN);

        // Data 1: Siswa yang sudah terdaftar di prodi
        $query = Siswa::query()
            ->selectRaw('YEAR(created_at) as year, prodi_id, COUNT(*) as total')
            ->whereNotNull('prodi_id')
            ->whereBetween(DB::raw('YEAR(created_at)'), [$from, $to])
            ->groupBy('year', 'prodi_id');

        if ($prodiId) {
            $query->where('prodi_id', $prodiId);
        }

        $rows = $query->get();

        // Index by year then prodi
        $data = [];
        foreach ($rows as $r) {
            $data[$r->year][$r->prodi_id] = (int)$r->total;
        }

        // Get all prodis
        $prodis = Prodi::orderBy('nama_prodi')->get(['id','nama_prodi']);

        // Data 2: Rekomendasi SPK (ambil top prodi dari rekomendasi_prodi)
        $spkResults = \App\Models\SpkResult::whereBetween(DB::raw('YEAR(created_at)'), [$from, $to])->get();
        
        // Parse rekomendasi_prodi and count by prodi name
        $spkData = [];
        
        foreach ($spkResults as $result) {
            $year = $result->created_at->format('Y');
            
            // Get rekomendasi - bisa berupa array atau string
            $rekomendasi = $result->rekomendasi_prodi;
            if (is_string($rekomendasi)) {
                $rekomendasi = json_decode($rekomendasi, true);
            }
            
            if (is_array($rekomendasi) && !empty($rekomendasi)) {
                // Get top recommendation (rank 1)
                $topProdi = $rekomendasi[0]['nama_prodi'] ?? null;
                if ($topProdi) {
                    // Find prodi id by name
                    $prodi = $prodis->firstWhere('nama_prodi', $topProdi);
                    if ($prodi) {
                        $spkData[$year][$prodi->id] = ($spkData[$year][$prodi->id] ?? 0) + 1;
                    }
                }
            }
        }

        $years = range($from, $to);

        // Series 1: Siswa Terdaftar
        $series = [];
        foreach ($prodis as $p) {
            $points = [];
            foreach ($years as $y) {
                $points[] = $data[$y][$p->id] ?? 0;
            }
            if ($includeEmpty || array_sum($points) > 0) {
                $series[] = [
                    'prodi_id' => $p->id,
                    'label' => $p->nama_prodi . ' (Terdaftar)',
                    'data' => $points,
                    'type' => 'registered'
                ];
            }
        }

        // Series 2: Rekomendasi SPK
        foreach ($prodis as $p) {
            $points = [];
            foreach ($years as $y) {
                $points[] = $spkData[$y][$p->id] ?? 0;
            }
            if ($includeEmpty || array_sum($points) > 0) {
                $series[] = [
                    'prodi_id' => $p->id,
                    'label' => $p->nama_prodi . ' (Rekomendasi SPK)',
                    'data' => $points,
                    'type' => 'recommendation'
                ];
            }
        }

        return response()->json([
            'years' => $years,
            'series' => $series,
        ]);
    }

    /**
     * CSV export of the same data shape.
     */
    public function prodiExport(Request $request)
    {
        $json = $this->prodiData($request)->getData(true);
        $years = $json['years'];
        $series = $json['series'];

        $headers = ['Prodi'];
        foreach ($years as $y) { $headers[] = $y; }

        $lines = [];
        $lines[] = implode(',', $headers);
        foreach ($series as $s) {
            $row = [$this->escapeCsv($s['label'])];
            foreach ($s['data'] as $v) { $row[] = $v; }
            $lines[] = implode(',', $row);
        }

        $csv = implode("\n", $lines);
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="prodi_report.csv"'
        ]);
    }

    private function escapeCsv($value): string
    {
        $needs = str_contains($value, ',') || str_contains($value, '"');
        $v = str_replace('"', '""', $value);
        return $needs ? '"'.$v.'"' : $v;
    }

    private function availableYears(): array
    {
        // Collect min/max from both siswa creation and SPK results to widen the selectable range
        $siswaMin = Siswa::query()->min(DB::raw('YEAR(created_at)'));
        $siswaMax = Siswa::query()->max(DB::raw('YEAR(created_at)'));

        $spkMin = \App\Models\SpkResult::query()->min(DB::raw('YEAR(created_at)'));
        $spkMax = \App\Models\SpkResult::query()->max(DB::raw('YEAR(created_at)'));

        $minCandidates = array_filter([$siswaMin, $spkMin], fn($v) => !is_null($v));
        $maxCandidates = array_filter([$siswaMax, $spkMax], fn($v) => !is_null($v));

        $min = $minCandidates ? min($minCandidates) : (int)date('Y');
        $max = $maxCandidates ? max($maxCandidates) : (int)date('Y');

        // If only a single year exists, widen the selectable range for UI usability
        if ($min === $max) {
            $max = max($max, (int)date('Y'));
            $min = $max - 4; // show last 5 years window
        }

        if ($min > $max) { $min = $max; }
        return range($min, $max);
    }
}

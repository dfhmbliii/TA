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

        $prodis = Prodi::orderBy('nama_prodi')->get(['id','nama_prodi']);
        $years = range($from, $to);

        $series = [];
        foreach ($prodis as $p) {
            $points = [];
            foreach ($years as $y) {
                $points[] = $data[$y][$p->id] ?? 0;
            }
            if ($includeEmpty || array_sum($points) > 0) {
                $series[] = [
                    'prodi_id' => $p->id,
                    'label' => $p->nama_prodi,
                    'data' => $points,
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
    $min = Siswa::query()->min(DB::raw('YEAR(created_at)')) ?? date('Y');
    $max = Siswa::query()->max(DB::raw('YEAR(created_at)')) ?? date('Y');
        if ($min > $max) { $min = $max; }
        return range($min, $max);
    }
}

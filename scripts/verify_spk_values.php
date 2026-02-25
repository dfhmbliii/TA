<?php

use App\Models\Kriteria;
use App\Models\PairwiseComparison;
use App\Models\MinatCategory;
use App\Models\MinatPairwiseComparison;
use App\Models\BakatCategory;
use App\Models\BakatPairwiseComparison;
use App\Models\AkademikCategory;
use App\Models\AkademikPairwiseComparison;
use App\Models\KarirCategory;
use App\Models\KarirPairwiseComparison;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

function getMatrixData($title, $items, $pairwiseModel, $fk1, $fk2, $nameField = 'nama') {
    $data = [
        'title' => $title,
        'items' => [],
        'matrix' => [],
        'column_sums' => []
    ];

    foreach($items as $item) {
        $data['items'][] = $item->$nameField;
    }

    $matrix = [];
    foreach ($items as $r) {
        $row = [];
        foreach ($items as $c) {
            if ($r->id == $c->id) {
                $val = 1.0;
            } else {
                $comp = $pairwiseModel::where($fk1, $r->id)->where($fk2, $c->id)->first();
                $val = $comp ? (float)$comp->nilai : 1.0;
            }
            $row[$c->$nameField] = $val;
            $matrix[$c->id][$r->id] = $val; // Store for col sum
        }
        $data['matrix'][$r->$nameField] = $row;
    }

    foreach ($items as $c) {
        $sum = 0;
        foreach ($items as $r) {
            $sum += $matrix[$c->id][$r->id];
        }
        $data['column_sums'][$c->$nameField] = $sum;
    }
    
    return $data;
}

$results = [];

// 1. Kriteria
$kriteria = Kriteria::orderBy('urutan')->get();
$results['kriteria'] = getMatrixData("Perbandingan Kriteria", $kriteria, PairwiseComparison::class, 'kriteria_1_id', 'kriteria_2_id', 'nama_kriteria');

// 2. Minat
$minat = MinatCategory::orderBy('urutan')->get();
$results['minat'] = getMatrixData("Perbandingan Minat", $minat, MinatPairwiseComparison::class, 'category_1_id', 'category_2_id');

// 3. Bakat
$bakat = BakatCategory::orderBy('urutan')->get();
$results['bakat'] = getMatrixData("Perbandingan Bakat", $bakat, BakatPairwiseComparison::class, 'category_1_id', 'category_2_id');

// 4. Akademik
$akademik = AkademikCategory::orderBy('urutan')->get();
$results['akademik'] = getMatrixData("Perbandingan Akademik", $akademik, AkademikPairwiseComparison::class, 'category_1_id', 'category_2_id');

// 5. Karir
$karir = KarirCategory::orderBy('urutan')->get();
$results['karir'] = getMatrixData("Perbandingan Karir", $karir, KarirPairwiseComparison::class, 'category_1_id', 'category_2_id');

echo json_encode($results, JSON_PRETTY_PRINT);

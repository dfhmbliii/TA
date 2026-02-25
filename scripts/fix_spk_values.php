<?php

use App\Models\Kriteria;
use App\Models\PairwiseComparison;
use App\Models\MinatCategory;
use App\Models\MinatPairwiseComparison;
use App\Models\BakatCategory;
use App\Models\BakatPairwiseComparison;
use App\Models\AkademikCategory;
use App\Models\AkademikPairwiseComparison;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

function updatePairwise($model, $fk1, $fk2, $search1, $search2, $field1, $field2, $value) {
    // Find items
    $item1 = $model['items_model']::where($field1, 'like', "%$search1%")->first();
    $item2 = $model['items_model']::where($field2, 'like', "%$search2%")->first();

    if (!$item1 || !$item2) {
        echo "ERROR: Could not find items for '$search1' or '$search2'\n";
        return;
    }

    // Direct update
    $model['pairwise_model']::updateOrCreate(
        [
            $fk1 => $item1->id,
            $fk2 => $item2->id,
        ],
        ['nilai' => $value]
    );

    // Reciprocal update
    $model['pairwise_model']::updateOrCreate(
        [
            $fk1 => $item2->id,
            $fk2 => $item1->id,
        ],
        ['nilai' => 1 / $value]
    );

    echo "Updated: {$item1->$field1} vs {$item2->$field2} = $value\n";
}

echo "=== Updating SPK Values ===\n\n";

// 1. Kriteria Main
// Table: Minat, Bakat, Nilai Akademik, Prospek Karir
$kriteriaConfig = [
    'items_model' => Kriteria::class,
    'pairwise_model' => PairwiseComparison::class
];
updatePairwise($kriteriaConfig, 'kriteria_1_id', 'kriteria_2_id', 'Minat', 'Bakat', 'nama_kriteria', 'nama_kriteria', 0.748);
updatePairwise($kriteriaConfig, 'kriteria_1_id', 'kriteria_2_id', 'Minat', 'Nilai Akademik', 'nama_kriteria', 'nama_kriteria', 0.416);
updatePairwise($kriteriaConfig, 'kriteria_1_id', 'kriteria_2_id', 'Minat', 'Prospek Karir', 'nama_kriteria', 'nama_kriteria', 0.377); // Original was 0.377 in image
updatePairwise($kriteriaConfig, 'kriteria_1_id', 'kriteria_2_id', 'Bakat', 'Nilai Akademik', 'nama_kriteria', 'nama_kriteria', 0.570);
updatePairwise($kriteriaConfig, 'kriteria_1_id', 'kriteria_2_id', 'Bakat', 'Prospek Karir', 'nama_kriteria', 'nama_kriteria', 0.653);
updatePairwise($kriteriaConfig, 'kriteria_1_id', 'kriteria_2_id', 'Nilai Akademik', 'Prospek Karir', 'nama_kriteria', 'nama_kriteria', 1.674);

echo "\n--- Kriteria Updated ---\n\n";

// 2. Bakat
// Table: Problem Solving, Ketekunan, Analisis Elektronik, Kepekaan Visual
$bakatConfig = [
    'items_model' => BakatCategory::class,
    'pairwise_model' => BakatPairwiseComparison::class
];
// Shorten search terms to ensure match
updatePairwise($bakatConfig, 'category_1_id', 'category_2_id', 'memecahkan masalah', 'ketekunan', 'nama', 'nama', 0.839);
updatePairwise($bakatConfig, 'category_1_id', 'category_2_id', 'memecahkan masalah', 'menganalisis rangkaian', 'nama', 'nama', 1.112);
updatePairwise($bakatConfig, 'category_1_id', 'category_2_id', 'memecahkan masalah', 'Kepekaan visual', 'nama', 'nama', 1.174);
updatePairwise($bakatConfig, 'category_1_id', 'category_2_id', 'ketekunan', 'menganalisis rangkaian', 'nama', 'nama', 0.966);
updatePairwise($bakatConfig, 'category_1_id', 'category_2_id', 'ketekunan', 'Kepekaan visual', 'nama', 'nama', 0.628);
updatePairwise($bakatConfig, 'category_1_id', 'category_2_id', 'menganalisis rangkaian', 'Kepekaan visual', 'nama', 'nama', 0.690);

echo "\n--- Bakat Updated ---\n\n";

// 3. Akademik (Named 'Perbandingan Matematika' in image, but matches Akademik structure)
// Table: Bahasa Inggris, Informatika, Matematika, Seni Budaya
$akademikConfig = [
    'items_model' => AkademikCategory::class,
    'pairwise_model' => AkademikPairwiseComparison::class
];
updatePairwise($akademikConfig, 'category_1_id', 'category_2_id', 'Bahasa Inggris', 'Informatika', 'nama', 'nama', 0.484);
updatePairwise($akademikConfig, 'category_1_id', 'category_2_id', 'Bahasa Inggris', 'Matematika', 'nama', 'nama', 0.325);
updatePairwise($akademikConfig, 'category_1_id', 'category_2_id', 'Bahasa Inggris', 'Seni Budaya', 'nama', 'nama', 0.317);
updatePairwise($akademikConfig, 'category_1_id', 'category_2_id', 'Informatika', 'Matematika', 'nama', 'nama', 0.388);
updatePairwise($akademikConfig, 'category_1_id', 'category_2_id', 'Informatika', 'Seni Budaya', 'nama', 'nama', 0.539);
updatePairwise($akademikConfig, 'category_1_id', 'category_2_id', 'Matematika', 'Seni Budaya', 'nama', 'nama', 0.864);

echo "\n--- Akademik Updated ---\n\n";

// Trigger recalculations (Optional but good practice)
echo "Recalculating weights via Controllers...\n";

$spkController = new \App\Http\Controllers\SpkAnalysisController();
$spkController->applyCurrentWeights();

$bakatController = new \App\Http\Controllers\BakatAnalysisController();
$bakatController->applyWeights();

$akademikController = new \App\Http\Controllers\AkademikAnalysisController();
$akademikController->applyWeights();

echo "Done.\n";

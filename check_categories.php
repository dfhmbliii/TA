<?php
require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kriteria = \App\Models\Kriteria::orderBy('urutan')->get();
echo "=== KRITERIA ===\n";
foreach($kriteria as $k) {
    echo $k->id . " | " . $k->kode_kriteria . " | " . $k->nama_kriteria . " | Bobot: " . ($k->bobot ?? 'null') . "\n";
}

echo "\n=== MINAT CATEGORIES ===\n";
$minat = \App\Models\MinatCategory::orderBy('urutan')->get();
foreach($minat as $m) {
    echo $m->id . " | " . $m->kode . " | " . $m->nama . " | Bobot: " . ($m->bobot ?? 'null') . "\n";
}

echo "\n=== BAKAT CATEGORIES ===\n";
$bakat = \App\Models\BakatCategory::orderBy('urutan')->get();
foreach($bakat as $b) {
    echo $b->id . " | " . $b->kode . " | " . $b->nama . " | Bobot: " . ($b->bobot ?? 'null') . "\n";
}

echo "\n=== KARIR CATEGORIES ===\n";
$karir = \App\Models\KarirCategory::orderBy('urutan')->get();
foreach($karir as $k) {
    echo $k->id . " | " . $k->kode . " | " . $k->nama . " | Bobot: " . ($k->bobot ?? 'null') . "\n";
}

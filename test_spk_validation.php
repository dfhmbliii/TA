#!/usr/bin/env php
<?php

$testData = [
    'kriteria_minat' => 'PROC_BIS',
    'kriteria_bakat' => 'PROB_BIS',
    'kriteria_karir' => 'SYSAN',
    'nilai_mapel' => [
        'bahasa_inggris' => '85',
        'informatika' => '90',
        'matematika' => '88',
        'seni_budaya' => '82',
        'fisika' => '85'
    ]
];

echo "=== TEST SPK CALCULATION ===\n\n";
echo "Input Data:\n";
print_r($testData);

// Sekarang check apakah validation akan pass
echo "\n=== VALIDASI ===\n";
require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kriteria = \App\Models\Kriteria::orderBy('urutan')->get();

$rules = [
    'nilai_mapel' => 'required|array',
    'nilai_mapel.*' => 'required|numeric|min:0|max:100',
];

foreach ($kriteria as $k) {
    if (in_array($k->kode_kriteria, ['AKADEMIK','NILAI'])) {
        continue;
    }
    
    $fieldName = 'kriteria_' . strtolower($k->kode_kriteria);
    $rules[$fieldName] = 'required|string';
    echo "✓ Rule added: $fieldName (kode: " . $k->kode_kriteria . ")\n";
}

echo "\nRules to validate:\n";
foreach($rules as $field => $rule) {
    echo "  $field => $rule\n";
}

$validator = validator()->make($testData, $rules);

if ($validator->passes()) {
    echo "\n✓ Validasi PASSED\n";
} else {
    echo "\n✗ Validasi FAILED\n";
    foreach ($validator->errors()->all() as $error) {
        echo "  - $error\n";
    }
}

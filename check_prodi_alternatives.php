#!/usr/bin/env php
<?php
require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$prodis = \App\Models\Prodi::with(['alternatives' => function($q) {
    $q->orderBy('urutan');
}])->get();

echo "=== PRODI ALTERNATIVES ===\n\n";
foreach($prodis as $prodi) {
    echo "📚 " . $prodi->nama_prodi . "\n";
    
    if ($prodi->alternatives->isEmpty()) {
        echo "   ⚠️  TIDAK ADA ALTERNATIF!\n";
    } else {
        foreach($prodi->alternatives as $alt) {
            echo "   - " . $alt->nama_alternatif . " (bobot: " . $alt->bobot . ")\n";
        }
    }
    echo "\n";
}

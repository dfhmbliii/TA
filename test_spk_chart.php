<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SpkResult;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;

echo "=== Data Rekomendasi SPK ===\n\n";

$spkResults = SpkResult::all();
echo "Total SPK Results: " . $spkResults->count() . "\n\n";

$prodis = Prodi::orderBy('nama_prodi')->get();

foreach ($spkResults as $result) {
    echo "ID: {$result->id}\n";
    echo "Created: {$result->created_at}\n";
    echo "Year: " . $result->created_at->format('Y') . "\n";
    
    $rekomendasi = json_decode($result->rekomendasi_prodi, true);
    if (is_array($rekomendasi) && !empty($rekomendasi)) {
        $topProdi = $rekomendasi[0]['nama_prodi'] ?? 'N/A';
        echo "Top Rekomendasi: {$topProdi}\n";
        
        // Find prodi
        $prodi = $prodis->firstWhere('nama_prodi', $topProdi);
        if ($prodi) {
            echo "Prodi ID: {$prodi->id}\n";
        } else {
            echo "Prodi NOT FOUND!\n";
        }
    } else {
        echo "No rekomendasi data\n";
    }
    echo "\n";
}

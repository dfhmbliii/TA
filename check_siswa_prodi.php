<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Siswa;
use App\Models\Prodi;

echo "Total Siswa: " . Siswa::count() . "\n";
echo "Siswa dengan Prodi: " . Siswa::whereNotNull('prodi_id')->count() . "\n";
echo "Total Prodi: " . Prodi::count() . "\n\n";

// Get siswa per prodi
$siswas = Siswa::with('prodi')->get();
foreach ($siswas as $siswa) {
    echo "Siswa: {$siswa->name} - Prodi: " . ($siswa->prodi ? $siswa->prodi->nama_prodi : 'TIDAK ADA') . "\n";
}

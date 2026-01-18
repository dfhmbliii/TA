<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SpkResult;
use App\Models\Siswa;

// Get latest SPK result
$result = SpkResult::latest()->first();
if ($result) {
    echo "Result ID: {$result->id}\n";
    echo "Total Score: {$result->total_score}\n";
    echo "Input Data Type: " . gettype($result->input_data) . "\n";
    echo "Input Data Content:\n";
    if (is_array($result->input_data)) {
        echo json_encode($result->input_data, JSON_PRETTY_PRINT) . "\n";
    } else {
        var_dump($result->input_data);
    }
} else {
    echo "No SPK result found\n";
}

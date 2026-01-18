<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SpkResult;

// Get latest SPK result
$result = SpkResult::latest()->first();

if ($result) {
    echo "ID: {$result->id}\n";
    echo "Total Score: {$result->total_score}\n";
    echo "Category: {$result->category}\n";
    echo "\nInput Data:\n";
    echo $result->input_data . "\n\n";
    
    // Try to decode input_data
    $inputData = json_decode($result->input_data, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "Input Data decoded:\n";
        print_r($inputData);
    } else {
        echo "JSON decode error: " . json_last_error_msg() . "\n";
    }
    
    echo "\n\nRekomendasi Prodi:\n";
    echo $result->rekomendasi_prodi . "\n";
} else {
    echo "No SPK results found\n";
}

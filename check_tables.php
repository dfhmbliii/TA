<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tables = DB::select("SHOW TABLES");
    $tableNames = array_map(fn($t) => array_values((array)$t)[0], $tables);
    
    if (in_array('sessions', $tableNames)) {
        echo "✓ Sessions table exists\n";
    } else {
        echo "✗ Sessions table NOT found\n";
        echo "Available tables: " . implode(', ', $tableNames) . "\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>

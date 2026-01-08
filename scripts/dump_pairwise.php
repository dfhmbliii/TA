<?php
// Dump pairwise_comparisons table for inspection
$envPath = __DIR__ . '/../.env';
$env = [];
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (!strpos($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $env[trim($k)] = trim($v);
    }
}

$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbPort = $env['DB_PORT'] ?? '3306';
$dbName = $env['DB_DATABASE'] ?? null;
$dbUser = $env['DB_USERNAME'] ?? 'root';
$dbPass = $env['DB_PASSWORD'] ?? '';

if (!$dbName) {
    echo "DB_DATABASE not found in .env\n";
    exit(1);
}

$dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "DB connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

$stmt = $pdo->query('SELECT kriteria_1_id, kriteria_2_id, nilai FROM pairwise_comparisons ORDER BY kriteria_1_id, kriteria_2_id');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$rows) {
    echo "No pairwise comparisons found.\n";
    exit(0);
}

echo "Pairwise comparisons (kriteria_1_id, kriteria_2_id, nilai):\n";
foreach ($rows as $r) {
    printf("%d, %d, %s\n", $r['kriteria_1_id'], $r['kriteria_2_id'], $r['nilai']);
}

exit(0);

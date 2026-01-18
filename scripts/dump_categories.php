<?php
// Dump category weights
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

echo "=== MINAT Categories ===\n";
$stmt = $pdo->query('SELECT kode, nama, bobot FROM minat_categories ORDER BY urutan');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    printf("%s - %s: %s\n", $r['kode'], $r['nama'], $r['bobot']);
}

echo "\n=== BAKAT Categories ===\n";
$stmt = $pdo->query('SELECT kode, nama, bobot FROM bakat_categories ORDER BY urutan');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    printf("%s - %s: %s\n", $r['kode'], $r['nama'], $r['bobot']);
}

echo "\n=== KARIR Categories ===\n";
$stmt = $pdo->query('SELECT kode, nama, bobot FROM karir_categories ORDER BY urutan');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    printf("%s - %s: %s\n", $r['kode'], $r['nama'], $r['bobot']);
}

exit(0);

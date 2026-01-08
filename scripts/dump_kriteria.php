<?php
// Dump kriteria table
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

$stmt = $pdo->query('SELECT id, nama_kriteria, kode_kriteria, urutan, bobot FROM kriteria ORDER BY urutan, id');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Kriteria (id, urutan, kode, nama, bobot):\n";
foreach ($rows as $r) {
    printf("%d, %d, %s, %s, %s\n", $r['id'], $r['urutan'], $r['kode_kriteria'], $r['nama_kriteria'], $r['bobot']);
}

exit(0);

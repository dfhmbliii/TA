<?php
// Apply fixed weights computed from user-provided matrix to kriteria table
// Mapping assumes order: Minat, Bakat, Nilai Akademik, Prospek Karir

$weights = [
    'MINAT' => 0.043241, // Minat
    'BAKAT' => 0.370202, // Bakat
    'AKADEMIK' => 0.447277, // Nilai Akademik
    'KARIR' => 0.139281, // Prospek Karir
];

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

if (!$dbName) { echo "DB_DATABASE not found in .env\n"; exit(1); }

$dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
try { $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); }
catch (Exception $e) { echo "DB connection failed: " . $e->getMessage() . "\n"; exit(1); }

// Backup current weights
$ts = date('Ymd_His');
$backupTable = "kriteria_backup_{$ts}";
$pdo->exec("CREATE TABLE {$backupTable} AS SELECT * FROM kriteria");
echo "Backup created in table: {$backupTable}\n";

$update = $pdo->prepare('UPDATE kriteria SET bobot = :b WHERE kode_kriteria = :kode');
foreach ($weights as $kode => $bobot) {
    $update->execute([':b' => $bobot, ':kode' => $kode]);
    echo "Updated {$kode} -> {$bobot}\n";
}

echo "Done applying weights.\n";
exit(0);

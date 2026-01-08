<?php
// Recompute reciprocal entries using primary comparisons (the ones >= 1)
// Requires pairwise_comparisons table to have adequate precision.

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

// Find all pairs where both directions exist
$stmt = $pdo->query('SELECT kriteria_1_id, kriteria_2_id, nilai FROM pairwise_comparisons');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pairs = [];
foreach ($rows as $r) {
    $a = (int)$r['kriteria_1_id'];
    $b = (int)$r['kriteria_2_id'];
    $pairs[$a][$b] = (float)$r['nilai'];
}

$updated = 0;
foreach ($pairs as $a => $map) {
    foreach ($map as $b => $val) {
        if (isset($pairs[$b][$a])) {
            $valAB = $pairs[$a][$b];
            $valBA = $pairs[$b][$a];
            // Prefer keeping the value >= 1 as the primary
            if ($valAB >= 1) {
                $newBA = 1.0 / $valAB;
                if (abs($valBA - $newBA) > 1e-6) {
                    $upd = $pdo->prepare('UPDATE pairwise_comparisons SET nilai = :val WHERE kriteria_1_id = :k1 AND kriteria_2_id = :k2');
                    $upd->execute([':val' => round($newBA, 6), ':k1' => $b, ':k2' => $a]);
                    $updated++;
                }
            } elseif ($valBA >= 1) {
                $newAB = 1.0 / $valBA;
                if (abs($valAB - $newAB) > 1e-6) {
                    $upd = $pdo->prepare('UPDATE pairwise_comparisons SET nilai = :val WHERE kriteria_1_id = :k1 AND kriteria_2_id = :k2');
                    $upd->execute([':val' => round($newAB, 6), ':k1' => $a, ':k2' => $b]);
                    $updated++;
                }
            }
        }
    }
}

echo "Recomputed reciprocals updated: {$updated}\n";
exit(0);

<?php
// One-off script to calculate AHP weights from pairwise comparisons and apply to `kriteria` table.

// Load .env
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

// Fetch criteria
$stmt = $pdo->query('SELECT id, nama_kriteria, kode_kriteria FROM kriteria ORDER BY urutan');
$kriteria = $stmt->fetchAll(PDO::FETCH_ASSOC);
$n = count($kriteria);
if ($n == 0) {
    echo "No criteria found.\n";
    exit(1);
}

$ids = array_column($kriteria, 'id');

// Initialize matrix with 1s on diagonal and 1 default elsewhere
$matrix = [];
foreach ($ids as $i) {
    foreach ($ids as $j) {
        $matrix[$i][$j] = ($i == $j) ? 1.0 : 1.0;
    }
}

// Load pairwise comparisons
$stmt = $pdo->query('SELECT kriteria_1_id, kriteria_2_id, nilai FROM pairwise_comparisons');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    $a = (int)$r['kriteria_1_id'];
    $b = (int)$r['kriteria_2_id'];
    $val = (float)$r['nilai'];
    if (in_array($a, $ids) && in_array($b, $ids)) {
        $matrix[$a][$b] = $val;
    }
}

// Column sums
$colSums = [];
foreach ($ids as $j) {
    $sum = 0.0;
    foreach ($ids as $i) {
        $sum += $matrix[$i][$j];
    }
    $colSums[$j] = $sum > 0 ? $sum : 1.0;
}

// Normalized and row averages
$weights = [];
foreach ($ids as $i) {
    $rowSum = 0.0;
    foreach ($ids as $j) {
        $rowSum += $matrix[$i][$j] / $colSums[$j];
    }
    $weights[$i] = $rowSum / $n;
}

// Weighted sum vector
$weighted = [];
foreach ($ids as $i) {
    $sum = 0.0;
    foreach ($ids as $j) {
        $sum += $matrix[$i][$j] * $weights[$j];
    }
    $weighted[$i] = $sum;
}

$lambdaMax = 0.0;
foreach ($ids as $i) {
    if ($weights[$i] != 0) $lambdaMax += $weighted[$i] / $weights[$i];
}
$lambdaMax = $lambdaMax / $n;

$ci = ($lambdaMax - $n) / ($n - 1);
$ri = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
$randomIndex = $ri[$n - 1] ?? end($ri);
$cr = $randomIndex ? ($ci / $randomIndex) : 0;

echo "AHP calculation results:\n";
foreach ($kriteria as $k) {
    $id = $k['id'];
    $name = $k['nama_kriteria'];
    printf(" - %s (%d): %0.6f\n", $name, $id, $weights[$id]);
}
printf("λmax = %0.6f\n", $lambdaMax);
printf("CI = %0.6f\n", $ci);
printf("RI (n=%d) = %0.6f\n", $n, $randomIndex);
printf("CR = %0.6f\n", $cr);

// Check for --force flag
$force = in_array('--force', $argv ?? []);
$apply = ($cr < 0.1) ? true : false;
if ($apply || $force) {
    echo "CR < 0.1 — applying weights to `kriteria` table...\n";
    $update = $pdo->prepare('UPDATE kriteria SET bobot = :b WHERE id = :id');
    foreach ($weights as $id => $w) {
        $update->execute([':b' => $w, ':id' => $id]);
    }
    echo "Done.\n";
} else {
    echo "CR >= 0.1 — weights not applied. If you want to force apply, re-run script with --force (not implemented).\n";
}

exit(0);

<?php
// Compute AHP using transposed orientation (matrix^T) to compare results
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

$stmt = $pdo->query('SELECT id, nama_kriteria FROM kriteria ORDER BY urutan');
$kriteria = $stmt->fetchAll(PDO::FETCH_ASSOC);
$ids = array_column($kriteria, 'id');
$n = count($ids);

// build original matrix A[row][col]
$matrix = [];
foreach ($ids as $i) {
    foreach ($ids as $j) {
        if ($i == $j) $matrix[$i][$j] = 1.0;
        else {
            $stmt2 = $pdo->prepare('SELECT nilai FROM pairwise_comparisons WHERE kriteria_1_id = ? AND kriteria_2_id = ?');
            $stmt2->execute([$i, $j]);
            $v = $stmt2->fetchColumn();
            $matrix[$i][$j] = $v !== false ? (float)$v : 1.0;
        }
    }
}

// transpose
$T = [];
foreach ($ids as $i) {
    foreach ($ids as $j) {
        $T[$i][$j] = $matrix[$j][$i];
    }
}

// compute AHP on T
$colSums = [];
foreach ($ids as $j) {
    $colSums[$j] = 0.0;
    foreach ($ids as $i) $colSums[$j] += $T[$i][$j];
}

$normalized = [];
$weights = [];
foreach ($ids as $i) {
    $rowSum = 0.0;
    foreach ($ids as $j) {
        $normalized[$i][$j] = $T[$i][$j] / $colSums[$j];
        $rowSum += $normalized[$i][$j];
    }
    $weights[$i] = $rowSum / $n;
}

$weighted = [];
foreach ($ids as $i) {
    $s = 0.0;
    foreach ($ids as $j) $s += $T[$i][$j] * $weights[$j];
    $weighted[$i] = $s;
}

$lambdaMax = 0.0;
foreach ($ids as $i) if ($weights[$i] != 0) $lambdaMax += $weighted[$i] / $weights[$i];
$lambdaMax /= $n;
$ci = ($lambdaMax - $n) / ($n - 1);
$ri = [0,0,0.58,0.90,1.12,1.24,1.32,1.41,1.45,1.49];
$randomIndex = $ri[$n-1] ?? end($ri);
$cr = $randomIndex ? ($ci / $randomIndex) : 0;

echo "Transposed AHP results:\n";
foreach ($kriteria as $k) {
    printf(" - %s: %0.6f\n", $k['nama_kriteria'], $weights[$k['id']]);
}
printf("λmax=%0.6f CI=%0.6f RI=%0.6f CR=%0.6f\n", $lambdaMax, $ci, $randomIndex, $cr);

exit(0);

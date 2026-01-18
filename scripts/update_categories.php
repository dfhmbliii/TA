<?php
// Update categories in spk_results based on new threshold
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

function getCategory($score) {
    if ($score >= 35) return 'Sangat Sesuai';
    if ($score >= 28) return 'Sesuai';
    if ($score >= 20) return 'Cukup Sesuai';
    return 'Kurang Sesuai';
}

// Get all spk_results
$stmt = $pdo->query('SELECT id, total_score, category FROM spk_results ORDER BY id DESC LIMIT 10');
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Updating categories based on new threshold:\n\n";
$updated = 0;

foreach ($results as $result) {
    $oldCategory = $result['category'];
    $newCategory = getCategory($result['total_score']);
    
    if ($oldCategory !== $newCategory) {
        $stmt = $pdo->prepare('UPDATE spk_results SET category = ? WHERE id = ?');
        $stmt->execute([$newCategory, $result['id']]);
        echo "ID {$result['id']} - Score {$result['total_score']}: '{$oldCategory}' → '{$newCategory}'\n";
        $updated++;
    } else {
        echo "ID {$result['id']} - Score {$result['total_score']}: '{$oldCategory}' (no change)\n";
    }
}

echo "\nTotal updated: $updated records\n";
exit(0);

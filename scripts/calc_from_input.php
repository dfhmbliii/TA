<?php
// Calculate AHP weights and consistency from an explicit matrix (from user input)

$matrix = [
    // Minat, Bakat, Nilai Akademik, Prospek Karir
    // Use exact reciprocals for 1/7, 1/9, 1/3 to match typical Excel precision
    [1.0, 1.0/7.0, 1.0/9.0, 0.2],
    [7.0, 1.0,     1.0,     3.0],
    [9.0, 1.0,     1.0,     5.0],
    [5.0, 1.0/3.0, 0.2,     1.0],
];

$n = count($matrix);

// Column sums
$colSums = array_fill(0, $n, 0.0);
for ($j=0; $j<$n; $j++) {
    for ($i=0; $i<$n; $i++) {
        $colSums[$j] += $matrix[$i][$j];
    }
}

// Normalized matrix and row sums
$normalized = array_fill(0, $n, array_fill(0, $n, 0.0));
$rowSums = array_fill(0, $n, 0.0);
for ($i=0; $i<$n; $i++) {
    for ($j=0; $j<$n; $j++) {
        $normalized[$i][$j] = $matrix[$i][$j] / $colSums[$j];
        $rowSums[$i] += $normalized[$i][$j];
    }
}

$weights = array_map(function($s) use ($n) { return $s / $n; }, $rowSums);

// Weighted sum vector A * w
$weighted = array_fill(0, $n, 0.0);
for ($i=0; $i<$n; $i++) {
    $s = 0.0;
    for ($j=0; $j<$n; $j++) {
        $s += $matrix[$i][$j] * $weights[$j];
    }
    $weighted[$i] = $s;
}

$lambdaTerms = [];
for ($i=0; $i<$n; $i++) {
    $lambdaTerms[$i] = $weighted[$i] / $weights[$i];
}
$lambdaMax = array_sum($lambdaTerms) / $n;
$ci = ($lambdaMax - $n) / ($n - 1);
$ri = [0,0,0.58,0.90,1.12,1.24,1.32,1.41,1.45,1.49];
$randomIndex = $ri[$n-1] ?? end($ri);
$cr = $randomIndex ? ($ci / $randomIndex) : 0.0;

// Print detailed results
echo "Column sums:\n";
for ($j=0;$j<$n;$j++) printf(" col %d = %0.6f\n", $j+1, $colSums[$j]);
echo "\nNormalized matrix:\n";
for ($i=0;$i<$n;$i++) {
    for ($j=0;$j<$n;$j++) printf("%0.6f\t", $normalized[$i][$j]);
    printf(" | %0.6f\n", $rowSums[$i]);
}
echo "\nWeights (priorities):\n";
for ($i=0;$i<$n;$i++) printf(" w%d = %0.6f\n", $i+1, $weights[$i]);

echo "\nWeighted sum (A*w) and lambda terms:\n";
for ($i=0;$i<$n;$i++) printf(" %0.6f \t %0.6f\n", $weighted[$i], $lambdaTerms[$i]);

printf("\nλmax = %0.6f\n", $lambdaMax);
printf("CI = %0.6f\n", $ci);
printf("RI = %0.6f\n", $randomIndex);
printf("CR = %0.6f\n", $cr);

exit(0);

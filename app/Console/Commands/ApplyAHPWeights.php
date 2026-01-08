<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kriteria;
use App\Models\PairwiseComparison;

class ApplyAHPWeights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ahp:apply {--force : Apply weights even if CR >= 0.1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate AHP weights from stored pairwise comparisons and apply to Kriteria (if consistent)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $kriteria = Kriteria::orderBy('urutan')->get();
        $n = count($kriteria);

        if ($n == 0) {
            $this->error('No criteria found.');
            return 1;
        }

        // Build matrix
        $matrix = [];
        foreach ($kriteria as $k1) {
            foreach ($kriteria as $k2) {
                if ($k1->id == $k2->id) {
                    $matrix[$k1->id][$k2->id] = 1.0;
                } else {
                    $comparison = PairwiseComparison::where('kriteria_1_id', $k1->id)
                        ->where('kriteria_2_id', $k2->id)
                        ->first();
                    $matrix[$k1->id][$k2->id] = $comparison ? (float)$comparison->nilai : 1.0;
                }
            }
        }

        // Column sums
        $colSums = [];
        foreach ($kriteria as $k) {
            $colSums[$k->id] = 0.0;
            foreach ($kriteria as $k2) {
                $colSums[$k->id] += $matrix[$k2->id][$k->id];
            }
            if ($colSums[$k->id] == 0) $colSums[$k->id] = 1.0; // guard
        }

        // Normalized matrix and row averages (weights)
        $normalized = [];
        $weights = [];
        foreach ($kriteria as $k1) {
            $sumRow = 0.0;
            foreach ($kriteria as $k2) {
                $normalized[$k1->id][$k2->id] = $matrix[$k1->id][$k2->id] / $colSums[$k2->id];
                $sumRow += $normalized[$k1->id][$k2->id];
            }
            $weights[$k1->id] = $sumRow / $n;
        }

        // Weighted sum vector for lambda max
        $weightedSum = [];
        foreach ($kriteria as $k1) {
            $sum = 0.0;
            foreach ($kriteria as $k2) {
                $sum += $matrix[$k1->id][$k2->id] * $weights[$k2->id];
            }
            $weightedSum[$k1->id] = $sum;
        }

        $lambdaMax = 0.0;
        foreach ($kriteria as $k) {
            if (isset($weights[$k->id]) && $weights[$k->id] != 0) {
                $lambdaMax += $weightedSum[$k->id] / $weights[$k->id];
            }
        }
        $lambdaMax = $lambdaMax / $n;

        $ci = ($lambdaMax - $n) / ($n - 1);
        $ri = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49, 1.51, 1.48, 1.56, 1.57, 1.59];
        $randomIndex = $ri[$n - 1] ?? $ri[count($ri) - 1];
        $cr = $randomIndex ? ($ci / $randomIndex) : 0.0;

        $this->info('Calculated AHP weights:');
        foreach ($kriteria as $k) {
            $this->line(sprintf(' - %s (%s): %0.6f', $k->nama_kriteria, $k->kode_kriteria, $weights[$k->id] ?? 0));
        }

        $this->info(sprintf('λmax = %0.6f', $lambdaMax));
        $this->info(sprintf('CI = %0.6f', $ci));
        $this->info(sprintf('RI (n=%d) = %0.6f', $n, $randomIndex));
        $this->info(sprintf('CR = %0.6f', $cr));

        if ($cr < 0.1 || $this->option('force')) {
            // Apply weights
            foreach ($kriteria as $k) {
                $k->bobot = $weights[$k->id] ?? 0;
                $k->save();
            }
            $this->info('Weights applied to Kriteria table.');
            return 0;
        }

        $this->warn('Consistency Ratio (CR) >= 0.1. Weights not applied. Use --force to apply anyway.');
        return 2;
    }
}

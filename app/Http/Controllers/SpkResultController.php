<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\SpkResult;
use App\Models\User;
use App\Models\Notification as DbNotification;
use App\Notifications\SpkResultReady;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SpkResultController extends Controller
{
    /**
     * Calculate SPK for a single siswa and store the result.
     *
     * POST /siswa/{id}/spk
     */
    public function calculate(Request $request, $id)
    {
    $siswa = Siswa::findOrFail($id);

        // Determine criteria values from siswa model. Adjust field names as needed.
        $criteria = [
            'ipk' => $siswa->ipk ?? 0,
            'prestasi' => $siswa->prestasi_score ?? 0,
            'kepemimpinan' => $siswa->kepemimpinan ?? 0,
            'sosial' => $siswa->sosial ?? 0,
            'komunikasi' => $siswa->komunikasi ?? 0,
            'kreativitas' => $siswa->kreativitas ?? 0,
        ];

        // Load weights from config or use defaults
        $defaultWeights = [
            'ipk' => 0.30,
            'prestasi' => 0.25,
            'kepemimpinan' => 0.20,
            'sosial' => 0.10,
            'komunikasi' => 0.10,
            'kreativitas' => 0.05,
        ];

        // If there's a settings table or config, you can pull real weights here.
        $weights = config('spk.weights', $defaultWeights);

        // Normalize and compute weighted sum
        $total = 0;
        foreach ($criteria as $key => $value) {
            $w = $weights[$key] ?? 0;
            // Assume criteria values are on a 0-100 scale, normalize to 0-1
            $valNorm = is_numeric($value) ? ($value / 100) : 0;
            $total += $valNorm * $w;
        }

        // Convert total to 0-100 scale
        $totalScore = round($total * 100, 4);

        // Simple category thresholds (example)
        if ($totalScore >= 75) {
            $category = 'Direkomendasikan';
        } elseif ($totalScore >= 50) {
            $category = 'Cadangan';
        } else {
            $category = 'Tidak Direkomendasikan';
        }

        $result = SpkResult::create([
            'siswa_id' => $siswa->id,
            'weights' => $weights,
            'criteria_values' => $criteria,
            'total_score' => $totalScore,
            'category' => $category,
        ]);

        // Optionally save last score on siswa model
        if (method_exists($siswa, 'fill')) {
            $siswa->spk_last_score = $totalScore;
            $siswa->spk_last_category = $category;
            $siswa->save();
        }

        // Send notification to the associated user (by email and in-app)
        $user = User::where('email', $siswa->email)->first();
        if ($user) {
            // In-app notification (custom table)
            DbNotification::create([
                'user_id' => $user->id,
                'type' => 'spk_result',
                'title' => 'Hasil Analisis SPK Siap',
                'message' => 'Skor: ' . $totalScore . ' • ' . $category,
                'data' => ['result_id' => $result->id],
            ]);

            // Laravel notification channels (database/mail)
            if (($user->spk_updates ?? true)) {
                $user->notify(new SpkResultReady($result));
            }
        }

        return redirect()->back()->with('success', "SPK calculated for {$siswa->name}. Score: {$totalScore}");
    }
}

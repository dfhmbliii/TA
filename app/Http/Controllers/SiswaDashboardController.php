<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\SpkResult;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['siswa', 'mahasiswa'])) {
            return redirect()->route('dashboard');
        }

        // Get siswa data by email
        $siswa = Siswa::where('email', Auth::user()->email)->first();
        
        // Get SPK results history
        $spkResults = [];
        if ($siswa) {
            $spkResults = SpkResult::where('siswa_id', $siswa->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('siswa.dashboard', compact('siswa', 'spkResults'));
    }
    
    public function history()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['siswa', 'mahasiswa'])) {
            return redirect()->route('dashboard');
        }

        // Get siswa data by email
        $siswa = Siswa::where('email', Auth::user()->email)->first();
        
        // Get all SPK results history
        $spkResults = collect([]);
        if ($siswa) {
            $spkResults = SpkResult::where('siswa_id', $siswa->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('siswa.history', compact('siswa', 'spkResults'));
    }
    
    public function exportPdf($id)
    {
        $result = SpkResult::with('siswa')->findOrFail($id);
        
        // Check if user has access to this result
        if (in_array(Auth::user()->role, ['siswa', 'mahasiswa'])) {
            $siswa = Siswa::where('email', Auth::user()->email)->first();
            if (!$siswa || $result->siswa_id !== $siswa->id) {
                abort(403, 'Unauthorized access');
            }
        }
        
        $pdf = Pdf::loadView('pdf.spk-result', compact('result'));
        
        $filename = 'Hasil_Analisis_SPK_' . $result->siswa->name . '_' . $result->created_at->format('YmdHis') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    public function viewPdf($id)
    {
        $result = SpkResult::with('siswa')->findOrFail($id);
        
        // Check if user has access to this result
        if (in_array(Auth::user()->role, ['siswa', 'mahasiswa'])) {
            $siswa = Siswa::where('email', Auth::user()->email)->first();
            if (!$siswa || $result->siswa_id !== $siswa->id) {
                abort(403, 'Unauthorized access');
            }
        }
        
        $pdf = Pdf::loadView('pdf.spk-result', compact('result'));
        
        return $pdf->stream();
    }
    
    public function getRekomendasiProdi($id)
    {
        $result = SpkResult::findOrFail($id);
        
        // Check if user has access to this result
        if (in_array(Auth::user()->role, ['siswa', 'mahasiswa'])) {
            $siswa = Siswa::where('email', Auth::user()->email)->first();
            if (!$siswa || $result->siswa_id !== $siswa->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
        }
        
        // Get rekomendasi prodi from result
        $rekomendasi = [];
        $rekomendasiData = $result->rekomendasi_prodi;
        
        if ($rekomendasiData) {
            // Ensure it's decoded if it's a string
            if (is_string($rekomendasiData)) {
                $rekomendasiData = json_decode($rekomendasiData, true);
            }
            
            if (is_array($rekomendasiData) && json_last_error() === JSON_ERROR_NONE) {
                $rekomendasi = $rekomendasiData;
            }
        }
        
        return response()->json([
            'success' => true,
            'rekomendasi' => $rekomendasi
        ]);
    }
    
    public function getDetailAnalysis($id)
    {
        $result = SpkResult::findOrFail($id);
        
        // Check if user has access to this result
        if (in_array(Auth::user()->role, ['siswa', 'mahasiswa'])) {
            $siswa = Siswa::where('email', Auth::user()->email)->first();
            if (!$siswa || $result->siswa_id !== $siswa->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
        }
        
        // Ensure input_data is properly decoded
        $inputData = $result->input_data;
        if (is_string($inputData)) {
            $inputData = json_decode($inputData, true);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $result->id,
                'total_score' => $result->total_score,
                'category' => $result->category,
                'created_at' => $result->created_at->format('d F Y, H:i:s'),
                'input_data' => $inputData
            ]
        ]);
    }
}

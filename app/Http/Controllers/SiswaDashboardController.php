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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Prodi;
use App\Models\MinatCategory;
use App\Models\BakatCategory;
use App\Models\AkademikCategory;
use App\Models\KarirCategory;

class SpkDashboardController extends Controller
{
    /**
     * Display SPK Analysis dashboard with options
     */
    public function index()
    {
        $kriteriaCount = Kriteria::count();
        $prodiCount = Prodi::count();
        $prodiWithAlternatives = Prodi::has('alternatives')->count();
        $minatCount = MinatCategory::count();
        $bakatCount = BakatCategory::count();
        $akademikCount = AkademikCategory::count();
        $karirCount = KarirCategory::count();

        return view('spk.dashboard', compact(
            'kriteriaCount',
            'prodiCount',
            'prodiWithAlternatives',
            'minatCount',
            'bakatCount',
            'akademikCount',
            'karirCount'
        ));
    }
}

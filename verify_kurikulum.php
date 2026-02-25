<?php
// Script untuk verifikasi data kurikulum
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Prodi;

echo "=== VERIFIKASI DATA KURIKULUM ===\n\n";

// Verifikasi Sistem Informasi
$prodiSI = Prodi::where('nama_prodi', 'LIKE', '%Sistem Informasi%')->first();
if($prodiSI) {
    echo "📚 SISTEM INFORMASI - KAMPUS JAKARTA\n";
    echo "   Total SKS: {$prodiSI->total_sks}\n";
    echo "   Jumlah Semester: {$prodiSI->jumlah_semester}\n";
    echo "   Jumlah Mata Kuliah: " . count($prodiSI->kurikulum_data) . "\n\n";
}

// Verifikasi Teknologi Informasi
$prodiTI = Prodi::where('nama_prodi', 'LIKE', '%Teknologi Informasi%')->first();
if($prodiTI) {
    echo "💻 TEKNOLOGI INFORMASI - KAMPUS JAKARTA\n";
    echo "   Total SKS: {$prodiTI->total_sks}\n";
    echo "   Jumlah Semester: {$prodiTI->jumlah_semester}\n";
    echo "   Jumlah Mata Kuliah: " . count($prodiTI->kurikulum_data) . "\n";
    
    // Breakdown per semester
    echo "\n   Breakdown per Semester:\n";
    for($i = 1; $i <= 8; $i++) {
        $mkSemester = array_filter($prodiTI->kurikulum_data, fn($mk) => $mk['semester'] == $i);
        $sksSemester = array_sum(array_column($mkSemester, 'sks'));
        echo "   - Semester $i: " . count($mkSemester) . " MK, $sksSemester SKS\n";
    }
}

// Verifikasi Teknik Telekomunikasi
$prodiTTEL = Prodi::where('nama_prodi', 'LIKE', '%Teknik Telekomunikasi%')
                  ->orWhere('nama_prodi', 'LIKE', '%Telekomunikasi%')
                  ->first();
if($prodiTTEL) {
    echo "\n📡 TEKNIK TELEKOMUNIKASI - KAMPUS JAKARTA\n";
    echo "   Total SKS: {$prodiTTEL->total_sks}\n";
    echo "   Jumlah Semester: {$prodiTTEL->jumlah_semester}\n";
    echo "   Jumlah Mata Kuliah: " . count($prodiTTEL->kurikulum_data) . "\n";
    
    // Breakdown per semester
    echo "\n   Breakdown per Semester:\n";
    for($i = 1; $i <= 8; $i++) {
        $mkSemester = array_filter($prodiTTEL->kurikulum_data, fn($mk) => $mk['semester'] == $i);
        $sksSemester = array_sum(array_column($mkSemester, 'sks'));
        echo "   - Semester $i: " . count($mkSemester) . " MK, $sksSemester SKS\n";
    }
}

// Verifikasi Desain Komunikasi Visual
$prodiDKV = Prodi::where('nama_prodi', 'LIKE', '%Desain Komunikasi Visual%')
                 ->orWhere('nama_prodi', 'LIKE', '%DKV%')
                 ->first();
if($prodiDKV) {
    echo "\n🎨 DESAIN KOMUNIKASI VISUAL - KAMPUS JAKARTA\n";
    echo "   Total SKS: {$prodiDKV->total_sks}\n";
    echo "   Jumlah Semester: {$prodiDKV->jumlah_semester}\n";
    echo "   Jumlah Mata Kuliah: " . count($prodiDKV->kurikulum_data) . "\n";
    
    // Breakdown per semester
    echo "\n   Breakdown per Semester:\n";
    for($i = 1; $i <= 8; $i++) {
        $mkSemester = array_filter($prodiDKV->kurikulum_data, fn($mk) => $mk['semester'] == $i);
        $sksSemester = array_sum(array_column($mkSemester, 'sks'));
        echo "   - Semester $i: " . count($mkSemester) . " MK, $sksSemester SKS\n";
    }
}

echo "\n✅ Verifikasi selesai!\n";

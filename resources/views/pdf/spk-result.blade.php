<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Analisis SPK - {{ $result->siswa->nama ?? 'Siswa' }}</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            background-color: #ffffff;
            margin: 0;
            padding: 40px 40px 60px 40px;
            font-size: 12px;
        }

        /* Footer Fixed on Every Page */
        @page {
            margin: 0px;
        }
        
        .footer {
            position: fixed;
            bottom: 30px;
            left: 40px;
            right: 40px;
            height: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            background-color: white;
        }

        /* Utilities */
        .w-100 { width: 100%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-muted { color: #6c757d; }
        
        /* Header */
        .header-container {
            margin-bottom: 30px;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 20px;
            margin-top: 40px;
        }
        
        .header-table {
            width: 100%;
        }
        
        .logo-img {
            height: 45px;
            width: auto;
        }

        .app-name {
            font-size: 24px;
            font-weight: 900;
            color: #0d6efd;
            margin: 0;
            line-height: 1;
        }

        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 15px;
        }

        .header-subtitle {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }

        /* CARD STYLE */
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            background-color: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* 1. BEST RECOMMENDATION CARD */
        .rec-card {
            background: linear-gradient(to right bottom, #ffffff, #f8fafc);
            border-left: 5px solid #0d6efd;
        }

        .rec-label {
            font-size: 14px;
            font-weight: bold;
            color: #d97706;
            margin-bottom: 15px;
            display: block;
        }

        .icon-small {
            width: 16px;
            height: 16px;
            vertical-align: middle;
            margin-right: 5px;
        }

        .prodi-highlight {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .fakultas-highlight {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .score-box {
            display: inline-block;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 10px 20px;
            border-radius: 10px;
        }
        
        .score-val {
            font-size: 32px;
            font-weight: 800;
            color: #15803d;
            line-height: 1;
        }
        
        .score-cat {
            font-size: 14px;
            color: #166534;
            font-weight: 600;
            margin-left: 10px;
        }


        /* 2. DATA INPUT TABLE */
        .input-card {
            border-left: 5px solid #6366f1; /* Indigo */
        }

        .input-header {
            font-size: 14px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .input-table {
            width: 100%;
            border-collapse: collapse;
        }

        .input-table th {
            text-align: left;
            font-size: 11px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            padding-bottom: 5px;
            width: 25%;
        }

        .input-table td {
            vertical-align: top;
            padding-right: 15px;
        }

        .input-box {
            background: #f3f4f6;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #1f2937;
            border-left: 3px solid #9ca3af;
            min-height: 20px;
        }

        /* Specific Input Colors */
        .input-box.minat { background: #fef2f2; border-color: #ef4444; color: #991b1b; }
        .input-box.bakat { background: #fffbeb; border-color: #f59e0b; color: #92400e; }
        .input-box.karir { background: #f0fdf4; border-color: #22c55e; color: #166534; }
        .input-box.nilai { background: #eff6ff; border-color: #3b82f6; color: #1e40af; }


        /* 3. RANKING TABLE */
        .rank-header {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 15px;
            margin-top: 10px;
        }

        .table-rank {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .table-rank th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-rank td {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .table-rank tr:last-child td {
            border-bottom: none;
        }

        /* Rank Badges */
        .rank-badge {
            width: 24px;
            height: 24px;
            line-height: 24px; /* Ensure vertical centering */
            text-align: center;
            border-radius: 50%;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
            vertical-align: middle; /* Vertical align middle */
        }
        .rank-1 { background-color: #FFD700; color: #783f04; }
        .rank-2 { background-color: #C0C0C0; color: #404040; }
        .rank-3 { background-color: #CD7F32; color: #fff; }
        .rank-other { background-color: #f1f5f9; color: #64748b; }

        /* Faculty Colors */
        .fak-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
        }
        .fak-ri { background: #dcfce7; color: #15803d; } /* Green */
        .fak-te { background: #dbeafe; color: #1d4ed8; } /* Blue */
        .fak-if { background: #fef9c3; color: #854d0e; } /* Yellow */
        .fak-ik { background: #fee2e2; color: #b91c1c; } /* Red */
        .fak-def { background: #f3f4f6; color: #4b5563; } /* Grey */

    </style>
</head>
<body>

    @php
        use App\Models\Prodi;
        use App\Models\MinatCategory;
        use App\Models\BakatCategory;
        use App\Models\KarirCategory;
        
        // --- DATA PREPARATION --- //
        $inputData = is_array($result->input_data) ? $result->input_data : [];
        if (empty($inputData) && is_string($result->input_data)) {
            $inputData = json_decode($result->input_data, true) ?? [];
        }

        $allRecommendations = is_array($result->rekomendasi_prodi) ? $result->rekomendasi_prodi : [];
        if (empty($allRecommendations) && is_string($result->rekomendasi_prodi)) {
             $allRecommendations = json_decode($result->rekomendasi_prodi, true) ?? [];
        }
        if (isset($allRecommendations['nama_prodi'])) {
            $allRecommendations = [$allRecommendations];
        }
        
        // Take top 5
        $topRecommendations = array_slice($allRecommendations, 0, 5);
        $topResult = $topRecommendations[0] ?? null;

        // --- LOOKUP HELPERS --- //
        
        // Get Full Text for Minat
        function getMinatText($code) {
            if (!$code) return '-';
            $item = MinatCategory::where('kode', $code)->first();
            // If not found by code, maybe the input IS the code/name, so return formatted
            return $item ? $item->nama : ucwords(str_replace('_', ' ', strtolower($code)));
        }

        // Get Full Text for Bakat
        function getBakatText($code) {
            if (!$code) return '-';
            $item = BakatCategory::where('kode', $code)->first();
            return $item ? $item->nama : ucwords(str_replace('_', ' ', strtolower($code)));
        }

        // Get Full Text for Karir
        function getKarirText($code) {
            if (!$code) return '-';
            $item = KarirCategory::where('kode', $code)->first();
            return $item ? $item->nama : ucwords(str_replace('_', ' ', strtolower($code)));
        }

        // Helper: Get Faculty Object & Color Logic
        function getFakultasDetails($prodiName) {
            $prodi = Prodi::where('nama_prodi', $prodiName)->first();
            $nama = $prodi ? $prodi->nama_fakultas : '-';
            
            $class = 'fak-def';
            if (stripos($nama, 'Rekayasa Industri') !== false) $class = 'fak-ri';
            elseif (stripos($nama, 'Teknik Elektro') !== false) $class = 'fak-te';
            elseif (stripos($nama, 'Informatika') !== false) $class = 'fak-if';
            elseif (stripos($nama, 'Industri Kreatif') !== false) $class = 'fak-ik';

            return ['nama' => $nama, 'class' => $class];
        }

        $topFakultas = $topResult ? getFakultasDetails($topResult['nama_prodi']) : ['nama' => '-', 'class' => 'fak-def'];

        // --- IMAGE TO BASE64 HELPER --- //
        function imgBase64($path) {
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            return '';
        }
        
        // Check multiple possible locations for logo
        $logoPath = '';
        // Prioritize user specified filename
        if (file_exists(public_path('img/pilihanku3.png'))) {
            $logoPath = public_path('img/pilihanku3.png');
        } elseif (file_exists(public_path('images/pilihanku3.png'))) {
            $logoPath = public_path('images/pilihanku3.png');
        } elseif (file_exists(base_path('public/img/pilihanku3.png'))) {
             $logoPath = base_path('public/img/pilihanku3.png');
        }
        // Fallbacks
        elseif (file_exists(public_path('img/logo.png'))) {
            $logoPath = public_path('img/logo.png');
        }

        $logoSrc = $logoPath ? imgBase64($logoPath) : '';
        $trophySrc = imgBase64(public_path('img/icons/trophy.png'));
        $clipboardSrc = imgBase64(public_path('img/icons/clipboard.png'));
        $chartSrc = imgBase64(public_path('img/icons/chart.png'));
    @endphp

    <!-- HEADER -->
    <div class="header-container" style="border-bottom: 3px solid #0d6efd; margin-bottom: 25px; padding-bottom: 15px;">
        <table class="header-table">
            <tr>
                <td width="60%" style="vertical-align: middle;">
                    <!-- Logo Only (Larger) -->
                    @if($logoSrc)
                        <img src="{{ $logoSrc }}" style="height: 75px; width: auto;" alt="Logo">
                    @else
                        <h2 style="margin:0; font-size: 32px; color: #0d6efd;">Pilihanku</h2>
                    @endif
                </td>
                <td width="40%" align="right" style="vertical-align: bottom;">
                    <div style="font-size: 11px; color: #6b7280;">
                        <b>{{ $result->siswa->name ?? 'Siswa' }}</b><br>
                        NISN: {{ $result->siswa->nisn ?? '-' }}<br>
                        {{ $result->created_at->format('d F Y, H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>


    <!-- 1. BEST RESULT CARD -->
    @if($topResult)
    <div class="card rec-card">
        <div class="rec-label">
            @if($trophySrc)<img src="{{ $trophySrc }}" class="icon-small" alt="trophy">@endif 
            REKOMENDASI UTAMA
        </div>
        
        <div class="prodi-highlight">
            {{ $topResult['nama_prodi'] }}
        </div>
        
        <div class="fakultas-highlight" style="display: flex; align-items: center;">
             <span class="fak-badge {{ $topFakultas['class'] }}" style="font-size: 12px; margin-right: 10px;">
                {{ $topFakultas['nama'] }}
             </span>
        </div>

        <div style="margin-top: 15px;">
            <div class="score-box">
                <span class="score-val">{{ number_format($result->total_score, 2) }}</span>
                <span class="score-cat">{{ $result->category }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- 2. DATA INPUT (TABLE FORMAT) -->
    <div class="card input-card">
        <div class="input-header">
            @if($clipboardSrc)<img src="{{ $clipboardSrc }}" class="icon-small" alt="clipboard">@endif
            DATA INPUT SISWA
        </div>
        
        <table class="input-table">
            <thead>
                <tr>
                    <th width="30%">Minat</th>
                    <th width="30%">Bakat</th>
                    <th width="20%">Prospek Karir</th>
                    <th width="20%">Nilai Rata-rata</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="input-box minat">
                            {{ getMinatText($inputData['minat'] ?? null) }}
                        </div>
                    </td>
                    <td>
                        <div class="input-box bakat">
                            {{ getBakatText($inputData['bakat'] ?? null) }}
                        </div>
                    </td>
                    <td>
                        <div class="input-box karir">
                             {{ getKarirText($inputData['prospek_karir'] ?? null) }}
                        </div>
                    </td>
                    <td>
                        @php
                            $nilaiArr = $inputData['nilai_mapel'] ?? [];
                            $avg = count($nilaiArr) > 0 ? array_sum($nilaiArr) / count($nilaiArr) : 0;
                        @endphp
                        <div class="input-box nilai">
                            {{ number_format($avg, 2) }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 3. RANKING TABLE -->
    <div class="rank-header">
        @if($chartSrc)<img src="{{ $chartSrc }}" class="icon-small" alt="chart">@endif
        PERINGKAT PROGRAM STUDI (TOP 5)
    </div>

    <table class="table-rank">
        <thead>
            <tr>
                <th width="10%" class="text-center">RANK</th>
                <th width="40%">PROGRAM STUDI</th>
                <th width="25%">FAKULTAS</th>
                <th width="25%" class="text-right">SKOR KESESUAIAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topRecommendations as $index => $rec)
            @php
                $fakDetails = getFakultasDetails($rec['nama_prodi']);
                $score = isset($rec['score']) ? floatval($rec['score']) : 0;
                
                $rankClass = 'rank-other';
                if ($index === 0) $rankClass = 'rank-1';
                if ($index === 1) $rankClass = 'rank-2';
                if ($index === 2) $rankClass = 'rank-3';
            @endphp
            <tr>
                <td class="text-center">
                    <span class="rank-badge {{ $rankClass }}">{{ $index + 1 }}</span>
                </td>
                <td>
                    <div style="font-weight: bold; color: #1f2937;">{{ $rec['nama_prodi'] }}</div>
                </td>
                <td>
                    <span class="fak-badge {{ $fakDetails['class'] }}">{{ $fakDetails['nama'] }}</span>
                </td>
                <td class="text-right">
                    <div style="font-weight: 800; color: #374151; font-size: 13px;">{{ number_format($score, 2) }}</div>
                    <div style="font-size: 10px; color: #6b7280;">{{ $rec['percentage'] ?? '' }}</div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER (Fixed) -->
    <div class="footer">
        dicetak otomatis oleh sistem <b>Pilihanku</b> pada {{ date('d/m/Y H:i') }} | &copy; {{ date('Y') }}
    </div>

</body>
</html>

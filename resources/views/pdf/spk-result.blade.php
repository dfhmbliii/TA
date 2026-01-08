<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Analisis SPK</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 12px;
        }
        .info-box {
            background-color: #F3F4F6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-box td {
            padding: 5px;
            font-size: 12px;
        }
        .info-box td:first-child {
            font-weight: bold;
            width: 150px;
        }
        .score-box {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }
        .score-box h2 {
            margin: 0;
            font-size: 48px;
            font-weight: bold;
        }
        .score-box p {
            margin: 10px 0 0 0;
            font-size: 18px;
        }
        .section-title {
            color: #4F46E5;
            font-size: 16px;
            font-weight: bold;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #E5E7EB;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th,
        table.data-table td {
            border: 1px solid #E5E7EB;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        table.data-table th {
            background-color: #F3F4F6;
            font-weight: bold;
            color: #4F46E5;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #10B981;
            color: white;
        }
        .badge-primary {
            background-color: #4F46E5;
            color: white;
        }
        .badge-info {
            background-color: #06B6D4;
            color: white;
        }
        .badge-warning {
            background-color: #F59E0B;
            color: white;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #E5E7EB;
            padding-top: 15px;
        }
        .criteria-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .criteria-row {
            display: table-row;
        }
        .criteria-cell {
            display: table-cell;
            width: 50%;
            padding: 5px;
            vertical-align: top;
        }
        .criteria-item {
            background-color: #F9FAFB;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            border-left: 3px solid #4F46E5;
        }
        .criteria-item .label {
            font-size: 10px;
            color: #666;
            margin-bottom: 3px;
        }
        .criteria-item .value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>HASIL ANALISIS SPK</h1>
        <p>Sistem Pendukung Keputusan Pemilihan Program Studi</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td>Nama siswa</td>
                <td>: {{ $result->siswa->name }}</td>
            </tr>
            <tr>
                <td>NISN</td>
                <td>: {{ $result->siswa->nisn }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{ $result->siswa->email }}</td>
            </tr>
            <tr>
                <td>Jurusan SMA/SMK</td>
                <td>: {{ $result->siswa->jurusan_sma }}</td>
            </tr>
            <tr>
                <td>Asal Sekolah</td>
                <td>: {{ $result->siswa->asal_sekolah }}</td>
            </tr>
            <tr>
                <td>Tahun Lulus</td>
                <td>: {{ $result->siswa->tahun_lulus }}</td>
            </tr>
            <tr>
                <td>Tanggal Analisis</td>
                <td>: {{ $result->created_at->format('d F Y, H:i:s') }}</td>
            </tr>
        </table>
    </div>

    <div class="score-box">
        <h2>{{ number_format($result->total_score, 4) }}</h2>
        <p>
            <span class="badge badge-{{ $result->category == 'Sangat Baik' ? 'success' : ($result->category == 'Baik' ? 'primary' : ($result->category == 'Cukup' ? 'info' : 'warning')) }}">
                {{ $result->category }}
            </span>
        </p>
    </div>

    <div class="section-title">Nilai Kriteria</div>
    <div class="criteria-grid">
        @if($result->criteria_values)
        <div class="criteria-row">
            <div class="criteria-cell">
                <div class="criteria-item">
                    <div class="label">⭐ IPK (Indeks Prestasi Kumulatif)</div>
                    <div class="value">{{ $result->criteria_values['ipk'] ?? '-' }}</div>
                </div>
            </div>
            <div class="criteria-cell">
                <div class="criteria-item">
                    <div class="label">🏆 Prestasi</div>
                    <div class="value">{{ $result->criteria_values['prestasi_score'] ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div class="criteria-row">
            <div class="criteria-cell">
                <div class="criteria-item">
                    <div class="label">👥 Kepemimpinan</div>
                    <div class="value">{{ $result->criteria_values['kepemimpinan'] ?? '-' }}</div>
                </div>
            </div>
            <div class="criteria-cell">
                <div class="criteria-item">
                    <div class="label">🤝 Sosial</div>
                    <div class="value">{{ $result->criteria_values['sosial'] ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div class="criteria-row">
            <div class="criteria-cell">
                <div class="criteria-item">
                    <div class="label">💬 Komunikasi</div>
                    <div class="value">{{ $result->criteria_values['komunikasi'] ?? '-' }}</div>
                </div>
            </div>
            <div class="criteria-cell">
                <div class="criteria-item">
                    <div class="label">💡 Kreativitas</div>
                    <div class="value">{{ $result->criteria_values['kreativitas'] ?? '-' }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="section-title">Bobot Kriteria</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Kriteria</th>
                <th style="text-align: center; width: 100px;">Bobot (%)</th>
            </tr>
        </thead>
        <tbody>
            @if($result->weights)
            <tr>
                <td>IPK (Indeks Prestasi Kumulatif)</td>
                <td style="text-align: center;">{{ $result->weights['ipk'] ?? '-' }}%</td>
            </tr>
            <tr>
                <td>Prestasi</td>
                <td style="text-align: center;">{{ $result->weights['prestasi_score'] ?? '-' }}%</td>
            </tr>
            <tr>
                <td>Kepemimpinan</td>
                <td style="text-align: center;">{{ $result->weights['kepemimpinan'] ?? '-' }}%</td>
            </tr>
            <tr>
                <td>Sosial</td>
                <td style="text-align: center;">{{ $result->weights['sosial'] ?? '-' }}%</td>
            </tr>
            <tr>
                <td>Komunikasi</td>
                <td style="text-align: center;">{{ $result->weights['komunikasi'] ?? '-' }}%</td>
            </tr>
            <tr>
                <td>Kreativitas</td>
                <td style="text-align: center;">{{ $result->weights['kreativitas'] ?? '-' }}%</td>
            </tr>
            <tr style="font-weight: bold; background-color: #F3F4F6;">
                <td>Total</td>
                <td style="text-align: center;">100%</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh Pilihanku</p>
        <p>© {{ date('Y') }} - Sistem Pendukung Keputusan Pemilihan Program Studi</p>
    </div>
</body>
</html>

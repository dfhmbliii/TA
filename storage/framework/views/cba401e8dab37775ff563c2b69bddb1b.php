<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Analisis SPK - <?php echo e($result->siswa->nama ?? 'Siswa'); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1f2937;
            line-height: 1.6;
            background: #f9fafb;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4f46e5;
        }
        
        .header h1 {
            color: #4f46e5;
            font-size: 28px;
            margin-bottom: 8px;
        }
        
        .header p {
            color: #6b7280;
            font-size: 14px;
        }
        
        /* Info Box */
        .info-box {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 13px;
        }
        
        .info-value {
            font-weight: 500;
            color: #111827;
            font-size: 13px;
        }
        
        /* Score Card */
        .score-card {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .score-value {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 10px;
        }
        
        .score-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 15px;
        }
        
        .score-badge {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        
        /* Section */
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        th {
            background: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover {
            background: #f9fafb;
        }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        
        /* Grid */
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .grid-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .grid-item-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .grid-item-value {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }
        
        /* Recommendation Box */
        .recommendation {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
        }
        
        .recommendation-title {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .recommendation-prodi {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .recommendation-score {
            font-size: 32px;
            font-weight: 800;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 11px;
        }
        
        /* Page Break */
        .page-break {
            page-break-after: always;
        }
        
        @page {
            margin: 20mm;
            size: A4;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>PILIHANKU</h1>
            <p>Sistem Pendukung Keputusan Pemilihan Program Studi</p>
        </div>

        <!-- Student Info -->
        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Nama Siswa</span>
                <span class="info-value"><?php echo e($result->siswa->nama ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">NISN</span>
                <span class="info-value"><?php echo e($result->siswa->nisn ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value"><?php echo e($result->siswa->email ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Asal Sekolah</span>
                <span class="info-value"><?php echo e($result->siswa->asal_sekolah ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Jurusan</span>
                <span class="info-value"><?php echo e($result->siswa->jurusan ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Analisis</span>
                <span class="info-value"><?php echo e($result->created_at->format('d F Y, H:i')); ?></span>
            </div>
        </div>

        <!-- Score Card -->
        <div class="score-card">
            <div class="score-label">TOTAL SKOR ANALISIS</div>
            <div class="score-value"><?php echo e(number_format($result->total_score, 2)); ?></div>
            <div class="score-badge"><?php echo e($result->category); ?></div>
        </div>

        <!-- Recommendation - Moved here before other details -->
        <?php
            $prodiRec = $result->rekomendasi_prodi;
            if (is_string($prodiRec)) {
                $prodiRec = json_decode($prodiRec, true);
            }
            $prodiScore = isset($prodiRec['score']) ? floatval($prodiRec['score']) : $result->total_score;
            $scoreCategory = $prodiScore >= 80 ? 'Sangat Sesuai' : ($prodiScore >= 70 ? 'Sesuai' : ($prodiScore >= 60 ? 'Cukup Sesuai' : 'Kurang Sesuai'));
        ?>

        <div class="recommendation">
            <div class="recommendation-title">✨ Rekomendasi Program Studi Terbaik</div>
            <div class="recommendation-prodi"><?php echo e($prodiRec['nama_prodi'] ?? 'Program Studi Belum Tersedia'); ?></div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 12px; opacity: 0.9;">Skor Kesesuaian</div>
                    <div class="recommendation-score"><?php echo e(number_format($prodiScore, 2)); ?>/100</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 12px; opacity: 0.9;">Tingkat Kesesuaian</div>
                    <div style="font-size: 20px; font-weight: 700; margin-top: 5px;"><?php echo e($scoreCategory); ?></div>
                </div>
            </div>
        </div>

        <!-- Data Input -->
        <div class="section">
            <h2 class="section-title">Data Input</h2>
            <?php
                $inputData = is_array($result->input_data) ? $result->input_data : json_decode($result->input_data, true);
                $nilaiMapel = isset($inputData['nilai_mapel']) ? $inputData['nilai_mapel'] : [];
                $avgNilai = count($nilaiMapel) > 0 ? number_format(array_sum(array_map('floatval', $nilaiMapel)) / count($nilaiMapel), 2) : '0.00';
            ?>
            
            <table>
                <tr>
                    <th>Minat</th>
                    <th>Bakat</th>
                    <th>Prospek Karir</th>
                    <th>Rata-rata Nilai</th>
                </tr>
                <tr>
                    <td><span class="badge badge-danger"><?php echo e(strtoupper($inputData['minat'] ?? '-')); ?></span></td>
                    <td><span class="badge badge-warning"><?php echo e(strtoupper($inputData['bakat'] ?? '-')); ?></span></td>
                    <td><span class="badge badge-success"><?php echo e(strtoupper($inputData['prospek_karir'] ?? '-')); ?></span></td>
                    <td><span class="badge badge-info"><?php echo e($avgNilai); ?></span></td>
                </tr>
            </table>
        </div>

        <!-- Nilai Kriteria -->
        <div class="section">
            <h2 class="section-title">Nilai Kriteria</h2>
            <?php
                $criteriaValues = is_array($result->criteria_values) ? $result->criteria_values : json_decode($result->criteria_values ?? '[]', true);
            ?>
            
            <div class="grid-4">
                <div class="grid-item">
                    <div class="grid-item-label">Minat</div>
                    <div class="grid-item-value"><?php echo e(number_format($criteriaValues['minat'] ?? 0, 2)); ?></div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-label">Bakat</div>
                    <div class="grid-item-value"><?php echo e(number_format($criteriaValues['bakat'] ?? 0, 2)); ?></div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-label">Prospek Karir</div>
                    <div class="grid-item-value"><?php echo e(number_format($criteriaValues['prospek_karir'] ?? 0, 2)); ?></div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-label">Akademik</div>
                    <div class="grid-item-value"><?php echo e(number_format($criteriaValues['akademik'] ?? 0, 2)); ?></div>
                </div>
            </div>
        </div>

        <!-- Bobot Kriteria (Weight) -->
        <div class="section">
            <h2 class="section-title">Bobot Kriteria (Weight)</h2>
            <?php
                $weights = is_array($result->weights) ? $result->weights : json_decode($result->weights ?? '[]', true);
            ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        <th>Visualisasi</th>
                        <th style="text-align: right;">Bobot</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Minat</td>
                        <td>
                            <div style="background: #e5e7eb; height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="width: <?php echo e($weights['minat'] ?? 0); ?>%; background: #4f46e5; height: 100%;"></div>
                            </div>
                        </td>
                        <td style="text-align: right; font-weight: 600;"><?php echo e(number_format($weights['minat'] ?? 0, 2)); ?>%</td>
                    </tr>
                    <tr>
                        <td>Bakat</td>
                        <td>
                            <div style="background: #e5e7eb; height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="width: <?php echo e($weights['bakat'] ?? 0); ?>%; background: #7c3aed; height: 100%;"></div>
                            </div>
                        </td>
                        <td style="text-align: right; font-weight: 600;"><?php echo e(number_format($weights['bakat'] ?? 0, 2)); ?>%</td>
                    </tr>
                    <tr>
                        <td>Prospek Karir</td>
                        <td>
                            <div style="background: #e5e7eb; height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="width: <?php echo e($weights['prospek_karir'] ?? 0); ?>%; background: #10b981; height: 100%;"></div>
                            </div>
                        </td>
                        <td style="text-align: right; font-weight: 600;"><?php echo e(number_format($weights['prospek_karir'] ?? 0, 2)); ?>%</td>
                    </tr>
                    <tr>
                        <td>Akademik</td>
                        <td>
                            <div style="background: #e5e7eb; height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="width: <?php echo e($weights['akademik'] ?? 0); ?>%; background: #f59e0b; height: 100%;"></div>
                            </div>
                        </td>
                        <td style="text-align: right; font-weight: 600;"><?php echo e(number_format($weights['akademik'] ?? 0, 2)); ?>%</td>
                    </tr>
                    <tr style="background: #f9fafb;">
                        <td colspan="2" style="font-weight: 700;">Total</td>
                        <td style="text-align: right; font-weight: 700;">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Detail Skor Rekomendasi -->
        <?php if(isset($prodiRec['details']) && is_array($prodiRec['details']) && count($prodiRec['details']) > 0): ?>
        <div class="section">
            <h2 class="section-title">Detail Skor Rekomendasi</h2>
            <table>
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        <th style="text-align: right;">Nilai</th>
                        <th style="text-align: right;">Weight</th>
                        <th style="text-align: right;">Weighted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $prodiRec['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(ucwords(str_replace('_', ' ', $key))); ?></td>
                        <td style="text-align: right;"><?php echo e(number_format($detail['score'] ?? 0, 2)); ?></td>
                        <td style="text-align: right;"><?php echo e(number_format(($detail['weight'] ?? 0) * 100, 2)); ?>%</td>
                        <td style="text-align: right; font-weight: 600;"><?php echo e(number_format($detail['weighted'] ?? 0, 2)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Dokumen ini digenerate otomatis oleh sistem PILIHANKU</strong></p>
            <p>© <?php echo e(date('Y')); ?> - Sistem Pendukung Keputusan Pemilihan Program Studi</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\tugas_akhir\resources\views/pdf/spk-result.blade.php ENDPATH**/ ?>
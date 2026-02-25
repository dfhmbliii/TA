@extends('layouts.app')

@section('title', 'Hasil Rekomendasi Program Studi')

@section('content')
<div class="content-header">
    <h1><i class="fas fa-chart-bar me-2"></i>Hasil Analisis SPK</h1>
    <p>Rekomendasi Program Studi berdasarkan minat, bakat, dan kemampuan Anda</p>
</div>

<!-- Summary Card -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-3"><i class="fas fa-trophy text-warning me-2"></i>Rekomendasi Terbaik</h5>
                        <h3 class="text-primary mb-2">{{ $prodiScores[0]['prodi']->nama_prodi ?? 'N/A' }}</h3>
                        <p class="text-muted mb-3">{{ $prodiScores[0]['prodi']->nama_fakultas ?? '' }}</p>
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <h2 class="mb-0 text-success">{{ number_format($prodiScores[0]['score'], 2) }}</h2>
                                <small class="text-muted">Skor Total</small>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <h5 class="mb-0">
                                    <span class="badge bg-{{ 
                                        $prodiScores[0]['score'] >= 35 ? 'success' : 
                                        ($prodiScores[0]['score'] >= 28 ? 'primary' : 
                                        ($prodiScores[0]['score'] >= 20 ? 'info' : 'warning')) 
                                    }} px-3 py-2">
                                        {{ 
                                            $prodiScores[0]['score'] >= 35 ? 'Sangat Sesuai' : 
                                            ($prodiScores[0]['score'] >= 28 ? 'Sesuai' : 
                                            ($prodiScores[0]['score'] >= 20 ? 'Cukup Sesuai' : 'Kurang Sesuai')) 
                                        }}
                                    </span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-graduation-cap text-primary" style="font-size: 6rem; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Input Summary -->
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card shadow border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Data Input Anda</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <small class="text-muted d-block">Minat</small>
                            @php
                                $minatCode = $data['kriteria_minat'] ?? null;
                                $minatText = $minatCode ? ($minatCategories[$minatCode]->nama ?? $minatCode) : '-';
                            @endphp
                            <span class="badge bg-danger">{{ $minatText }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <small class="text-muted d-block">Bakat</small>
                            @php
                                $bakatCode = $data['kriteria_bakat'] ?? null;
                                $bakatText = $bakatCode ? ($bakatCategories[$bakatCode]->nama ?? $bakatCode) : '-';
                            @endphp
                            <span class="badge bg-warning text-dark">{{ $bakatText }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <small class="text-muted d-block">Prospek Karir</small>
                            @php
                                $karirCode = $data['kriteria_karir'] ?? null;
                                $karirText = $karirCode ? ($karirCategories[$karirCode]->nama ?? $karirCode) : '-';
                            @endphp
                            <span class="badge bg-success">{{ $karirText }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <small class="text-muted d-block">Nilai Rata-rata</small>
                            <span class="badge bg-info">{{ number_format(array_sum($data['nilai_mapel'] ?? [0]) / max(count($data['nilai_mapel'] ?? [1]), 1), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ranking Table -->
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-list-ol me-2"></i>
                        Ranking Program Studi (Top {{ count($prodiScores) }})
                    </h6>
                    <a href="{{ route('siswa-spk.form') }}" class="btn btn-sm btn-outline-primary d-print-none">
                        <i class="fas fa-redo me-1"></i>Analisis Ulang
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="5%">Rank</th>
                                <th width="30%">Program Studi</th>
                                <th width="20%">Fakultas</th>
                                <th class="text-center" width="10%">Skor Total</th>
                                <th class="text-center" width="15%">Kategori</th>
                                <th class="text-center d-print-none" width="10%">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prodiScores as $index => $item)
                            <tr>
                                <td class="text-center">
                                    @if($index === 0)
                                        <i class="fas fa-trophy text-warning fa-lg"></i>
                                    @elseif($index === 1)
                                        <i class="fas fa-medal text-secondary fa-lg"></i>
                                    @elseif($index === 2)
                                        <i class="fas fa-award text-bronze fa-lg" style="color: #CD7F32;"></i>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <!-- Hide Initials in Print -->
                                        <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center me-2 d-print-none">
                                            {{ substr($item['prodi']->nama_prodi, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $item['prodi']->nama_prodi }}</div>
                                            <small class="text-muted">{{ $item['prodi']->kode_prodi ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $fakultas = $item['prodi']->nama_fakultas;
                                        $colorClass = 'secondary';
                                        if (stripos($fakultas, 'Rekayasa Industri') !== false) $colorClass = 'success';
                                        elseif (stripos($fakultas, 'Teknik Elektro') !== false) $colorClass = 'info';
                                        elseif (stripos($fakultas, 'Informatika') !== false) $colorClass = 'warning';
                                        elseif (stripos($fakultas, 'Industri Kreatif') !== false) $colorClass = 'danger';
                                    @endphp
                                    <span class="badge bg-{{ $colorClass }}">{{ $fakultas }}</span>
                                </td>
                                <td class="text-center">
                                    <h5 class="mb-0 text-{{ $item['score'] >= 80 ? 'success' : ($item['score'] >= 70 ? 'primary' : 'secondary') }}">
                                        {{ number_format($item['score'], 2) }}
                                    </h5>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ 
                                        $item['score'] >= 35 ? 'success' : 
                                        ($item['score'] >= 28 ? 'primary' : 
                                        ($item['score'] >= 20 ? 'info' : 'warning')) 
                                    }}">
                                        {{ 
                                            $item['score'] >= 35 ? 'Sangat Sesuai' : 
                                            ($item['score'] >= 28 ? 'Sesuai' : 
                                            ($item['score'] >= 20 ? 'Cukup Sesuai' : 'Kurang Sesuai')) 
                                        }}
                                    </span>
                                </td>
                                <td class="text-center d-print-none">
                                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#detail{{ $index }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse d-print-none" id="detail{{ $index }}">
                                <td colspan="6" class="bg-light">
                                    <div class="p-3">
                                        <h6 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Detail Skor per Kriteria</h6>
                                        <div class="row">
                                            @foreach($item['details'] as $key => $detail)
                                            <div class="col-md-3 mb-3">
                                                <div class="card border-0 bg-white shadow-sm">
                                                    <div class="card-body p-3">
                                                        <small class="text-muted d-block mb-1">{{ ucwords(str_replace('_', ' ', $key)) }}</small>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h5 class="mb-0">{{ number_format($detail['weighted'], 2) }}</h5>
                                                            <span class="badge bg-primary">{{ number_format($detail['weight'] * 100, 0) }}%</span>
                                                        </div>
                                                        <div class="progress mt-2" style="height: 4px;">
                                                            <div class="progress-bar" role="progressbar" style="width: {{ $detail['score'] }}%"></div>
                                                        </div>
                                                        <small class="text-muted">Nilai: {{ number_format($detail['score'], 2) }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row g-4 mt-2 d-print-none">
    <div class="col-12">
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-home me-2"></i>Kembali ke Dashboard
            </a>
            <a href="{{ route('prodi.show', $prodiScores[0]['prodi']->id) }}" class="btn btn-primary">
                <i class="fas fa-info-circle me-2"></i>Lihat Detail Program Studi
            </a>
            <button class="btn btn-success" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Cetak Hasil
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        font-size: 0.875rem;
        font-weight: bold;
    }
    
    .vr {
        width: 2px;
        background-color: #dee2e6;
        opacity: 1;
        align-self: stretch;
    }
    
    @media print {
        /* Remove default browser headers/footers */
        @page {
            margin: 1cm;
            size: auto;
        }

        /* Force graphics/colors */
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            background: white !important;
        }

        /* Hide unwanted UI elements */
        .sidebar, .navbar, .sidebar-toggle, .btn, .d-print-none, .content-header p, .content-header h1 {
            display: none !important;
        }

        /* Specifically hide the main "Hasil Analisis SPK" text */
        .content-header {
            display: none !important;
        }

        /* Adjust layout to full width */
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            background: white !important;
        }

        /* Card refinements for print */
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            break-inside: avoid;
            margin-bottom: 20px !important;
        }

        .card-header {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #ddd !important;
        }

        /* Ensure badges have colors */
        .badge {
            border: 1px solid transparent;
            color: black !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Table adjustments */
        table {
            width: 100% !important;
        }
    }
</style>
@endpush

@extends('layouts.app')

@section('title', 'SPK Analysis')

@section('content')
<div class="content-header">
    <h1>SPK Analysis</h1>
    <p>Sistem Pendukung Keputusan menggunakan metode AHP (Analytical Hierarchy Process)</p>
</div>

<div class="row">
    <!-- Analisis Kriteria Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm hover-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start mb-3">
                    <div class="icon-box me-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-list-ul fa-2x text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-2">Analisis Kriteria</h4>
                        <p class="text-muted mb-0">Kelola bobot kriteria utama SPK</p>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status Kriteria:</span>
                        <strong class="text-primary">{{ $kriteriaCount }} Kriteria</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ $kriteriaCount > 0 ? '100' : '0' }}%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);">
                        </div>
                    </div>
                </div>

                <div class="criteria-preview mb-4">
                    <small class="text-muted d-block mb-2"><strong>Kriteria yang akan dianalisis:</strong></small>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary">Minat</span>
                        <span class="badge bg-primary">Bakat</span>
                        <span class="badge bg-primary">Nilai Akademik</span>
                        <span class="badge bg-primary">Prospek Karir</span>
                    </div>
                </div>

                <div class="feature-list mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Matriks Perbandingan Berpasangan</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Perhitungan Bobot Otomatis</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Validasi Consistency Ratio (CR)</small>
                    </div>
                </div>

                <a href="{{ route('spk.analysis') }}" class="btn btn-primary w-100"
                   style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                    <i class="fas fa-arrow-right me-2"></i>
                    Kelola Kriteria
                </a>
            </div>
        </div>
    </div>

    <!-- Analisis Minat Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm hover-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start mb-3">
                    <div class="icon-box me-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="fas fa-heart fa-2x text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-2">Analisis Minat</h4>
                        <p class="text-muted mb-0">Kelola bobot kategori minat siswa</p>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kategori Minat:</span>
                        <strong class="text-info">{{ $minatCount }} Kategori</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ $minatCount > 0 ? '100' : '0' }}%; background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);">
                        </div>
                    </div>
                </div>

                <div class="criteria-preview mb-4">
                    <small class="text-muted d-block mb-2"><strong>Kategori yang akan dianalisis:</strong></small>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge" style="background-color: #4facfe;">Proses Bisnis</span>
                        <span class="badge" style="background-color: #00c9ff;">Pemrograman</span>
                        <span class="badge" style="background-color: #92fe9d;">Fisika & Matematika</span>
                        <span class="badge" style="background-color: #ff9a9e;">Seni & Estetika</span>
                    </div>
                </div>

                <div class="feature-list mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Matriks Perbandingan Berpasangan</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Perhitungan Bobot Otomatis</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Validasi Consistency Ratio (CR)</small>
                    </div>
                </div>

                <a href="{{ route('spk.minat.index') }}" class="btn w-100"
                   style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; color: white;">
                    <i class="fas fa-arrow-right me-2"></i>
                    Kelola Minat
                </a>
            </div>
        </div>
    </div>

    <!-- Analisis Bakat Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm hover-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start mb-3">
                    <div class="icon-box me-3" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <i class="fas fa-star fa-2x text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-2">Analisis Bakat</h4>
                        <p class="text-muted mb-0">Kelola bobot kategori bakat siswa</p>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kategori Bakat:</span>
                        <strong class="text-success">{{ $bakatCount }} Kategori</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ $bakatCount > 0 ? '100' : '0' }}%; background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);">
                        </div>
                    </div>
                </div>

                <div class="criteria-preview mb-4">
                    <small class="text-muted d-block mb-2"><strong>Kategori yang akan dianalisis:</strong></small>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge" style="background-color: #43e97b;">Problem-solving</span>
                        <span class="badge" style="background-color: #38f9d7;">Debugging</span>
                        <span class="badge" style="background-color: #f6d365;">Elektronik</span>
                        <span class="badge" style="background-color: #fda085;">Visual</span>
                    </div>
                </div>

                <div class="feature-list mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Matriks Perbandingan Berpasangan</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Perhitungan Bobot Otomatis</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Validasi Consistency Ratio (CR)</small>
                    </div>
                </div>

                <a href="{{ route('spk.bakat.index') }}" class="btn w-100"
                   style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border: none; color: white;">
                    <i class="fas fa-arrow-right me-2"></i>
                    Kelola Bakat
                </a>
            </div>
        </div>
    </div>

    <!-- Analisis Nilai Akademik Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm hover-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start mb-3">
                    <div class="icon-box me-3" style="background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);">
                        <i class="fas fa-graduation-cap fa-2x text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-2">Analisis Nilai Akademik</h4>
                        <p class="text-muted mb-0">Kelola bobot kategori nilai akademik</p>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kategori Akademik:</span>
                        <strong class="text-danger">{{ $akademikCount }} Kategori</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ $akademikCount > 0 ? '100' : '0' }}%; background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%);">
                        </div>
                    </div>
                </div>

                <div class="criteria-preview mb-4">
                    <small class="text-muted d-block mb-2"><strong>Kategori yang dianalisis:</strong></small>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge" style="background-color: #ff9a9e;">Bahasa Inggris</span>
                        <span class="badge" style="background-color: #fad0c4; color: #333;">Informatika</span>
                        <span class="badge" style="background-color: #fbc2eb; color: #333;">Matematika</span>
                        <span class="badge" style="background-color: #a1c4fd;">Seni Budaya</span>
                    </div>
                </div>

                <a href="{{ route('spk.akademik.index') }}" class="btn w-100"
                   style="background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%); border: none; color: white;">
                    <i class="fas fa-arrow-right me-2"></i>
                    Kelola Nilai Akademik
                </a>
            </div>
        </div>
    </div>

    <!-- Analisis Prospek Karir Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm hover-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start mb-3">
                    <div class="icon-box me-3" style="background: linear-gradient(135deg, #8EC5FC 0%, #E0C3FC 100%);">
                        <i class="fas fa-briefcase fa-2x text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-2">Analisis Prospek Karir</h4>
                        <p class="text-muted mb-0">Kelola bobot kategori prospek karir</p>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kategori Karir:</span>
                        <strong class="text-primary">{{ $karirCount }} Kategori</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ $karirCount > 0 ? '100' : '0' }}%; background: linear-gradient(90deg, #8EC5FC 0%, #E0C3FC 100%);">
                        </div>
                    </div>
                </div>

                <div class="criteria-preview mb-4">
                    <small class="text-muted d-block mb-2"><strong>Kategori yang dianalisis:</strong></small>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge" style="background-color: #8EC5FC;">System Analyst</span>
                        <span class="badge" style="background-color: #E0C3FC; color: #333;">Software Engineer</span>
                        <span class="badge" style="background-color: #B8E1FF; color: #333;">Network Engineer</span>
                        <span class="badge" style="background-color: #DDBDF1;">Graphic Designer</span>
                    </div>
                </div>

                <a href="{{ route('spk.karir.index') }}" class="btn w-100"
                   style="background: linear-gradient(135deg, #8EC5FC 0%, #E0C3FC 100%); border: none; color: white;">
                    <i class="fas fa-arrow-right me-2"></i>
                    Kelola Prospek Karir
                </a>
            </div>
        </div>
    </div>

    <!-- Analisis Per Prodi Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm hover-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start mb-3">
                    <div class="icon-box me-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-layer-group fa-2x text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-2">Analisis Per Program Studi</h4>
                        <p class="text-muted mb-0">Kelola bobot mata pelajaran per prodi</p>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Program Studi:</span>
                        <strong class="text-danger">{{ $prodiCount }} Prodi</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Dengan Alternatif:</span>
                        <strong class="text-success">{{ $prodiWithAlternatives }} Prodi</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ $prodiCount > 0 ? ($prodiWithAlternatives / $prodiCount * 100) : 0 }}%; background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);">
                        </div>
                    </div>
                </div>

                <div class="prodi-preview mb-4">
                    <small class="text-muted d-block mb-2"><strong>Program Studi:</strong></small>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge" style="background-color: #208E3B;">Sistem Informasi</span>
                        <span class="badge" style="background-color: #007ACC;">Teknik Telekomunikasi</span>
                        <span class="badge" style="background-color: #F57D20;">DKV</span>
                        <span class="badge" style="background-color: #A48B31;">Teknologi Informasi</span>
                    </div>
                </div>

                <div class="feature-list mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Matriks Per Program Studi</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Kelola Mata Pelajaran</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>Consistency Ratio Per Prodi</small>
                    </div>
                </div>

                <a href="{{ route('prodi.list') }}" class="btn w-100"
                   style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none; color: white;">
                    <i class="fas fa-arrow-right me-2"></i>
                    Kelola Per Prodi
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Info Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="mb-3">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    Tentang Metode AHP
                </h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-item">
                            <h6 class="text-primary">
                                <i class="fas fa-chart-line me-2"></i>
                                Analytical Hierarchy Process
                            </h6>
                            <p class="text-muted small mb-0">
                                Metode pengambilan keputusan dengan membandingkan kriteria secara berpasangan untuk mendapatkan bobot prioritas.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-item">
                            <h6 class="text-success">
                                <i class="fas fa-balance-scale me-2"></i>
                                Consistency Ratio (CR)
                            </h6>
                            <p class="text-muted small mb-0">
                                Nilai CR < 0.1 menunjukkan perbandingan Anda konsisten dan dapat diterima untuk analisis.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-item">
                            <h6 class="text-warning">
                                <i class="fas fa-calculator me-2"></i>
                                Perhitungan Otomatis
                            </h6>
                            <p class="text-muted small mb-0">
                                Sistem akan menghitung bobot, eigenvalue, dan consistency ratio secara otomatis setelah input matriks.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.feature-list {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

.info-item {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    height: 100%;
}

.badge {
    padding: 0.5rem 0.75rem;
    font-weight: 500;
}
</style>

@endsection

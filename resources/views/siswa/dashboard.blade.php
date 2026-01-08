@extends('layouts.app')

@section('title', 'Dashboard siswa')

@section('content')
<div class="content-header">
    <h1>Dashboard Pilihanku</h1>
    <p>Selamat datang di sistem SPK pemilihan program studi</p>
</div>

<div class="row g-4">
    <!-- SPK Analysis Status -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Status Analisis SPK
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    @if($siswa && $spkResults->count() > 0)
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success fa-3x"></i>
                    </div>
                    <h6>Analisis SPK Selesai</h6>
                    <p class="text-muted">Hasil analisis sudah tersedia</p>
                    <div class="mb-3">
                        <h4 class="text-primary mb-1">{{ number_format($spkResults->first()->total_score, 2) }}</h4>
                        <span class="badge bg-{{ $spkResults->first()->category == 'Sangat Baik' ? 'success' : ($spkResults->first()->category == 'Baik' ? 'primary' : 'warning') }}">
                            {{ $spkResults->first()->category }}
                        </span>
                    </div>
                    <a href="{{ route('siswa-spk.form') }}" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>
                        Lihat Hasil
                    </a>
                    @else
                    <div class="mb-3">
                        <i class="fas fa-clock text-warning fa-3x"></i>
                    </div>
                    <h6>Analisis SPK Belum Dilakukan</h6>
                    <p class="text-muted">Anda belum melakukan analisis SPK</p>
                    <a href="{{ route('siswa-spk.form') }}" class="btn btn-primary">
                        <i class="fas fa-play me-2"></i>
                        Mulai Analisis
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Info -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Profil Siswa
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                        {{ substr(auth()->user()->name ?? '', 0, 1) }}
                    </div>
                    <div>
                        <h5 class="mb-1">{{ auth()->user()->name ?? 'siswa' }}</h5>
                        <p class="text-muted mb-0">{{ auth()->user()->email ?? 'email@example.com' }}</p>
                    </div>
                </div>
                <hr>
                <div class="row g-3">
                    <div class="col-6">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge bg-success">Aktif</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Role</small>
                        <span class="badge bg-info">Siswa</span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Bergabung Sejak</small>
                        <span>{{ auth()->user()->created_at ? auth()->user()->created_at->format('d M Y') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Analisis SPK -->
    @if($siswa && $spkResults->count() > 0)
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Analisis SPK
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Total Skor</th>
                                <th>Kategori</th>
                                <th>Kriteria</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($spkResults as $result)
                            <tr>
                                <td>
                                    <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                    {{ $result->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <strong class="text-primary">{{ number_format($result->total_score, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $result->category == 'Sangat Baik' ? 'success' : ($result->category == 'Baik' ? 'primary' : ($result->category == 'Cukup' ? 'info' : 'warning')) }}">
                                        {{ $result->category }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        @if($result->criteria_values)
                                            IPK: {{ $result->criteria_values['ipk'] ?? '-' }}, 
                                            Prestasi: {{ $result->criteria_values['prestasi_score'] ?? '-' }}, 
                                            Kepemimpinan: {{ $result->criteria_values['kepemimpinan'] ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </small>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $result->id }}">
                                        <i class="fas fa-eye me-1"></i>
                                        Detail
                                    </button>
                                    <a href="{{ route('spk.export-pdf', $result->id) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        PDF
                                    </a>
                                </td>
                            </tr>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="detailModal{{ $result->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Analisis SPK</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Tanggal Analisis</label>
                                                <p>{{ $result->created_at->format('d F Y, H:i:s') }}</p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Total Skor</label>
                                                <h4 class="text-primary">{{ number_format($result->total_score, 4) }}</h4>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Kategori</label>
                                                <p>
                                                    <span class="badge bg-{{ $result->category == 'Sangat Baik' ? 'success' : ($result->category == 'Baik' ? 'primary' : ($result->category == 'Cukup' ? 'info' : 'warning')) }} fs-6">
                                                        {{ $result->category }}
                                                    </span>
                                                </p>
                                            </div>
                                            <hr>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Nilai Kriteria</label>
                                                @if($result->criteria_values)
                                                <ul class="list-unstyled">
                                                    <li><i class="fas fa-star text-warning me-2"></i> IPK: <strong>{{ $result->criteria_values['ipk'] ?? '-' }}</strong></li>
                                                    <li><i class="fas fa-trophy text-success me-2"></i> Prestasi: <strong>{{ $result->criteria_values['prestasi_score'] ?? '-' }}</strong></li>
                                                    <li><i class="fas fa-users text-primary me-2"></i> Kepemimpinan: <strong>{{ $result->criteria_values['kepemimpinan'] ?? '-' }}</strong></li>
                                                    <li><i class="fas fa-handshake text-info me-2"></i> Sosial: <strong>{{ $result->criteria_values['sosial'] ?? '-' }}</strong></li>
                                                    <li><i class="fas fa-comments text-secondary me-2"></i> Komunikasi: <strong>{{ $result->criteria_values['komunikasi'] ?? '-' }}</strong></li>
                                                    <li><i class="fas fa-lightbulb text-warning me-2"></i> Kreativitas: <strong>{{ $result->criteria_values['kreativitas'] ?? '-' }}</strong></li>
                                                </ul>
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Bobot Kriteria</label>
                                                @if($result->weights)
                                                <ul class="list-unstyled">
                                                    <li>IPK: <strong>{{ $result->weights['ipk'] ?? '-' }}%</strong></li>
                                                    <li>Prestasi: <strong>{{ $result->weights['prestasi_score'] ?? '-' }}%</strong></li>
                                                    <li>Kepemimpinan: <strong>{{ $result->weights['kepemimpinan'] ?? '-' }}%</strong></li>
                                                    <li>Sosial: <strong>{{ $result->weights['sosial'] ?? '-' }}%</strong></li>
                                                    <li>Komunikasi: <strong>{{ $result->weights['komunikasi'] ?? '-' }}%</strong></li>
                                                    <li>Kreativitas: <strong>{{ $result->weights['kreativitas'] ?? '-' }}%</strong></li>
                                                </ul>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($spkResults->count() >= 5)
                <div class="text-center mt-3">
                    <a href="{{ route('spk.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-history me-2"></i>
                        Lihat Semua Riwayat
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Links -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-link me-2"></i>
                    Menu Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <a href="{{ route('siswa-spk.form') }}" class="card text-decoration-none text-dark">
                            <div class="card-body text-center">
                                <i class="fas fa-calculator fa-2x text-primary mb-3"></i>
                                <h6>SPK Analysis</h6>
                                <p class="text-muted small mb-0">Mulai analisis pemilihan program studi</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('prodi.index') }}" class="card text-decoration-none text-dark">
                            <div class="card-body text-center">
                                <i class="fas fa-graduation-cap fa-2x text-success mb-3"></i>
                                <h6>Program Studi</h6>
                                <p class="text-muted small mb-0">Lihat informasi program studi</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('spk.history') }}" class="card text-decoration-none text-dark {{ $siswa && $spkResults->count() > 0 ? '' : 'opacity-50' }}">
                            <div class="card-body text-center">
                                <i class="fas fa-history fa-2x text-warning mb-3"></i>
                                <h6>Riwayat Analisis</h6>
                                <p class="text-muted small mb-0">
                                    @if($siswa && $spkResults->count() > 0)
                                        {{ $spkResults->count() }} analisis terbaru
                                    @else
                                        Belum ada riwayat analisis
                                    @endif
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
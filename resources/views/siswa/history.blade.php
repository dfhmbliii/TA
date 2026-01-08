@extends('layouts.app')

@section('title', 'Riwayat Analisis SPK')

@section('content')
<div class="content-header">
    <h1>Riwayat Analisis SPK</h1>
    <p>Lihat semua hasil analisis SPK yang pernah dilakukan</p>
</div>

@if($spkResults->count() > 0)
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-history me-2"></i>
            Daftar Riwayat Analisis
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Total Skor</th>
                        <th>Kategori</th>
                        <th>Kriteria</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($spkResults as $index => $result)
                    <tr>
                        <td>{{ ($spkResults->currentPage() - 1) * $spkResults->perPage() + $index + 1 }}</td>
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
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Analisis SPK</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Tanggal Analisis</label>
                                                <p>{{ $result->created_at->format('d F Y, H:i:s') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Total Skor</label>
                                                <h4 class="text-primary">{{ number_format($result->total_score, 4) }}</h4>
                                            </div>
                                        </div>
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Nilai Kriteria</label>
                                                @if($result->criteria_values)
                                                <ul class="list-unstyled">
                                                    <li class="mb-2"><i class="fas fa-star text-warning me-2"></i> IPK: <strong>{{ $result->criteria_values['ipk'] ?? '-' }}</strong></li>
                                                    <li class="mb-2"><i class="fas fa-trophy text-success me-2"></i> Prestasi: <strong>{{ $result->criteria_values['prestasi_score'] ?? '-' }}</strong></li>
                                                    <li class="mb-2"><i class="fas fa-users text-primary me-2"></i> Kepemimpinan: <strong>{{ $result->criteria_values['kepemimpinan'] ?? '-' }}</strong></li>
                                                    <li class="mb-2"><i class="fas fa-handshake text-info me-2"></i> Sosial: <strong>{{ $result->criteria_values['sosial'] ?? '-' }}</strong></li>
                                                    <li class="mb-2"><i class="fas fa-comments text-secondary me-2"></i> Komunikasi: <strong>{{ $result->criteria_values['komunikasi'] ?? '-' }}</strong></li>
                                                    <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> Kreativitas: <strong>{{ $result->criteria_values['kreativitas'] ?? '-' }}</strong></li>
                                                </ul>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Bobot Kriteria</label>
                                                @if($result->weights)
                                                <ul class="list-unstyled">
                                                    <li class="mb-2">IPK: <strong>{{ $result->weights['ipk'] ?? '-' }}%</strong></li>
                                                    <li class="mb-2">Prestasi: <strong>{{ $result->weights['prestasi_score'] ?? '-' }}%</strong></li>
                                                    <li class="mb-2">Kepemimpinan: <strong>{{ $result->weights['kepemimpinan'] ?? '-' }}%</strong></li>
                                                    <li class="mb-2">Sosial: <strong>{{ $result->weights['sosial'] ?? '-' }}%</strong></li>
                                                    <li class="mb-2">Komunikasi: <strong>{{ $result->weights['komunikasi'] ?? '-' }}%</strong></li>
                                                    <li class="mb-2">Kreativitas: <strong>{{ $result->weights['kreativitas'] ?? '-' }}%</strong></li>
                                                </ul>
                                                @endif
                                            </div>
                                        </div>
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
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $spkResults->links() }}
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-history fa-4x text-muted mb-3"></i>
        <h5>Belum Ada Riwayat Analisis</h5>
        <p class="text-muted mb-4">Anda belum pernah melakukan analisis SPK</p>
        <a href="{{ route('spk.index') }}" class="btn btn-primary">
            <i class="fas fa-play me-2"></i>
            Mulai Analisis Sekarang
        </a>
    </div>
</div>
@endif
@endsection

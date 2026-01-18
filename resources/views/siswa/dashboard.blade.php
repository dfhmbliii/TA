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
                        <span class="badge bg-{{ 
                            $spkResults->first()->total_score >= 35 ? 'success' : 
                            ($spkResults->first()->total_score >= 28 ? 'primary' : 
                            ($spkResults->first()->total_score >= 20 ? 'info' : 'warning')) 
                        }}">
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
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Total Skor</th>
                                <th>Kategori</th>
                                <th>Kriteria</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($spkResults->take(5) as $index => $result)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                    {{ $result->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <strong class="text-primary">{{ number_format($result->total_score, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $result->category == 'Sangat Sesuai' ? 'success' : ($result->category == 'Sesuai' ? 'primary' : ($result->category == 'Cukup Sesuai' ? 'info' : 'warning')) }}">
                                        {{ $result->category }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        @php
                                            $input = is_array($result->input_data) ? $result->input_data : json_decode($result->input_data, true);
                                        @endphp
                                        @if($input && is_array($input))
                                            Minat: {{ ucwords(str_replace('_', ' ', $input['minat'] ?? '-')) }}, 
                                            Bakat: {{ ucwords(str_replace('_', ' ', $input['bakat'] ?? '-')) }}
                                        @else
                                            -
                                        @endif
                                    </small>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" data-detail-btn 
                                        data-result-id="{{ $result->id }}" 
                                        data-total-score="{{ $result->total_score }}" 
                                        data-category="{{ $result->category }}" 
                                        data-created="{{ $result->created_at->format('d F Y, H:i:s') }}">
                                        <i class="fas fa-eye me-1"></i>
                                        Detail
                                    </button>
                                    <a href="{{ route('spk.view-pdf', $result->id) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        View
                                    </a>
                                    <a href="{{ route('spk.export-pdf', $result->id) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download me-1"></i>
                                        Download
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($spkResults->count() > 5)
                <div class="text-center mt-3">
                    <a href="{{ route('spk.history') }}" class="btn btn-outline-primary">
                        <i class="fas fa-history me-2"></i>
                        Lihat Semua Riwayat
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Single Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-chart-bar me-2"></i>Detail Analisis SPK</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    
                    <!-- Summary Card -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="text-muted mb-3"><i class="fas fa-calendar me-2"></i>Tanggal Analisis</h6>
                                    <p id="modalCreatedDate" class="mb-4 fs-6">-</p>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Total Skor</h6>
                                            <h3 class="text-success mb-0" id="modalTotalScore">-</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Kategori</h6>
                                            <div id="modalCategory">-</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <i class="fas fa-chart-pie text-primary" style="font-size: 4rem; opacity: 0.15;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Input Section -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Data Input Anda</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div>
                                        <small class="text-muted d-block mb-2">Minat</small>
                                        <span class="badge bg-danger fs-6" id="modalInputMinat">-</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div>
                                        <small class="text-muted d-block mb-2">Bakat</small>
                                        <span class="badge bg-warning text-dark fs-6" id="modalInputBakat">-</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div>
                                        <small class="text-muted d-block mb-2">Prospek Karir</small>
                                        <span class="badge bg-success fs-6" id="modalInputKarir">-</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div>
                                        <small class="text-muted d-block mb-2">Nilai Rata-rata</small>
                                        <span class="badge bg-info fs-6" id="modalInputNilai">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rekomendasi Prodi Section -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Rekomendasi Program Studi</h6>
                        </div>
                        <div class="card-body" id="modalRekomendasiProdi">
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-spinner fa-spin me-2"></i>Memuat rekomendasi...
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
    
    document.querySelectorAll('[data-detail-btn]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            try {
                const resultId = this.getAttribute('data-result-id');
                const totalScore = this.getAttribute('data-total-score');
                const category = this.getAttribute('data-category');
                const created = this.getAttribute('data-created');
                
                // Update modal content
                document.getElementById('modalCreatedDate').textContent = created;
                document.getElementById('modalTotalScore').textContent = parseFloat(totalScore).toFixed(2);
                
                // Category badge
                const categoryBadge = `<span class="badge bg-${
                    category === 'Sangat Sesuai' ? 'success' : 
                    (category === 'Sesuai' ? 'primary' : 
                    (category === 'Cukup Sesuai' ? 'info' : 'warning'))
                } fs-6 px-3 py-2">${category}</span>`;
                document.getElementById('modalCategory').innerHTML = categoryBadge;
                
                // Show modal first
                detailModal.show();
                
                // Fetch detail data (input + rekomendasi) from server
                const rekomendasiContainer = document.getElementById('modalRekomendasiProdi');
                rekomendasiContainer.innerHTML = `
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-spinner fa-spin me-2"></i>Memuat data...
                    </div>
                `;
                
                // Fetch detail analysis
                fetch(`/spk/result/${resultId}/detail`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data && data.data.input_data) {
                            const inputData = data.data.input_data;
                            
                            // Populate input data
                            if (inputData && typeof inputData === 'object' && Object.keys(inputData).length > 0) {
                                const minatText = inputData['minat'] ? 
                                    inputData['minat'].charAt(0).toUpperCase() + inputData['minat'].slice(1).replace(/_/g, ' ') : '-';
                                document.getElementById('modalInputMinat').textContent = minatText;
                                
                                const bakatText = inputData['bakat'] ? 
                                    inputData['bakat'].charAt(0).toUpperCase() + inputData['bakat'].slice(1).replace(/_/g, ' ') : '-';
                                document.getElementById('modalInputBakat').textContent = bakatText;
                                
                                const karirText = inputData['prospek_karir'] ? 
                                    inputData['prospek_karir'].charAt(0).toUpperCase() + inputData['prospek_karir'].slice(1).replace(/_/g, ' ') : '-';
                                document.getElementById('modalInputKarir').textContent = karirText;
                                
                                // Calculate average nilai
                                if (inputData['nilai_mapel']) {
                                    const nilaiMapel = inputData['nilai_mapel'];
                                    const values = Object.values(nilaiMapel).map(v => parseFloat(v) || 0);
                                    const avg = values.length > 0 ? (values.reduce((a, b) => a + b, 0) / values.length).toFixed(2) : '-';
                                    document.getElementById('modalInputNilai').textContent = avg;
                                } else {
                                    document.getElementById('modalInputNilai').textContent = '-';
                                }
                            } else {
                                document.getElementById('modalInputMinat').textContent = '-';
                                document.getElementById('modalInputBakat').textContent = '-';
                                document.getElementById('modalInputKarir').textContent = '-';
                                document.getElementById('modalInputNilai').textContent = '-';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching detail:', error);
                    });
                
                // Fetch rekomendasi prodi
                if (resultId) {
                    fetch(`/spk/result/${resultId}/rekomendasi`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.rekomendasi && data.rekomendasi.length > 0) {
                                let html = '<div class="list-group list-group-flush">';
                                data.rekomendasi.forEach((prodi, index) => {
                                    html += `
                                        <div class="list-group-item px-0 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="badge bg-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                                        ${index + 1}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">${prodi.nama_prodi || prodi.name || 'N/A'}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-chart-line me-1"></i>
                                                        Skor: <strong>${prodi.score ? parseFloat(prodi.score).toFixed(2) : 'N/A'}</strong>
                                                        ${prodi.percentage ? `(${parseFloat(prodi.percentage).toFixed(1)}%)` : ''}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                                html += '</div>';
                                rekomendasiContainer.innerHTML = html;
                            } else {
                                rekomendasiContainer.innerHTML = `
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                                        <p class="mb-0">Tidak ada rekomendasi program studi tersedia</p>
                                    </div>
                                `;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching rekomendasi:', error);
                            rekomendasiContainer.innerHTML = `
                                <div class="text-center text-danger py-4">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <p class="mb-0">Gagal memuat rekomendasi program studi</p>
                                </div>
                            `;
                        });
                }
            } catch (error) {
                console.error('Error parsing data:', error);
                alert('Terjadi kesalahan saat memuat detail.');
            }
        });
    });
});
</script>
@endpush
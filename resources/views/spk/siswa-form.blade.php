@extends('layouts.app')

@section('title', 'Analisis SPK - Rekomendasi Program Studi')

@section('content')
<div class="content-header">
    <h1><i class="fas fa-graduation-cap me-2"></i>Rekomendasi Program Studi</h1>
    <p>Sistem Pendukung Keputusan untuk menemukan program studi yang sesuai dengan minat dan kemampuan Anda</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Formulir Analisis SPK
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa-spk.calculate') }}" method="POST" id="spkForm">
                    @csrf
                    
                    <!-- Alert Info -->
                    <div class="alert alert-info border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk:</strong> Pilih kriteria yang sesuai dengan minat dan kemampuan Anda. Untuk mata pelajaran, masukkan nilai rata-rata rapor Anda.
                    </div>

                    <!-- Dynamic Kriteria Forms -->
                    @foreach($kriteria as $k)
                        @if(!in_array($k->kode_kriteria, ['NILAI','AKADEMIK']))
                        <div class="mb-4">
                            <label for="kriteria_{{ strtolower($k->kode_kriteria) }}" class="form-label fw-bold">
                                <i class="fas fa-{{ $k->kode_kriteria === 'MINAT' ? 'heart' : ($k->kode_kriteria === 'BAKAT' ? 'star' : 'briefcase') }} text-{{ $k->kode_kriteria === 'MINAT' ? 'danger' : ($k->kode_kriteria === 'BAKAT' ? 'warning' : 'success') }} me-2"></i>
                                {{ $k->nama_kriteria }}
                            </label>
                            <select name="kriteria_{{ strtolower($k->kode_kriteria) }}" 
                                    id="kriteria_{{ strtolower($k->kode_kriteria) }}" 
                                    class="form-select form-select-lg @error('kriteria_' . strtolower($k->kode_kriteria)) is-invalid @enderror" 
                                    required>
                                <option value="">-- Pilih {{ $k->nama_kriteria }} --</option>
                                @if(isset($kriteriaOptions[$k->kode_kriteria]))
                                    @foreach($kriteriaOptions[$k->kode_kriteria] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('kriteria_' . strtolower($k->kode_kriteria))
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($k->deskripsi)
                            <div class="form-text">{{ $k->deskripsi }}</div>
                            @endif
                        </div>
                        @endif
                    @endforeach

                    <hr class="my-4">

                    <!-- Mata Pelajaran Relevan -->
                    <h6 class="mb-3 fw-bold">
                        <i class="fas fa-book text-primary me-2"></i>
                        Nilai Mata Pelajaran Relevan
                    </h6>
                    <p class="text-muted small mb-3">Masukkan nilai rata-rata rapor Anda untuk mata pelajaran berikut (skala 0-100):</p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="nilai_bahasa_inggris" class="form-label">
                                <i class="fas fa-language me-1"></i>Bahasa Inggris
                            </label>
                            <input type="number" name="nilai_mapel[bahasa_inggris]" id="nilai_bahasa_inggris" 
                                   class="form-control @error('nilai_mapel.bahasa_inggris') is-invalid @enderror" 
                                   min="0" max="100" step="0.1" required placeholder="Contoh: 85.5">
                            @error('nilai_mapel.bahasa_inggris')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nilai_informatika" class="form-label">
                                <i class="fas fa-laptop-code me-1"></i>Informatika / TIK
                            </label>
                            <input type="number" name="nilai_mapel[informatika]" id="nilai_informatika" 
                                   class="form-control @error('nilai_mapel.informatika') is-invalid @enderror" 
                                   min="0" max="100" step="0.1" required placeholder="Contoh: 90">
                            @error('nilai_mapel.informatika')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nilai_matematika" class="form-label">
                                <i class="fas fa-calculator me-1"></i>Matematika
                            </label>
                            <input type="number" name="nilai_mapel[matematika]" id="nilai_matematika" 
                                   class="form-control @error('nilai_mapel.matematika') is-invalid @enderror" 
                                   min="0" max="100" step="0.1" required placeholder="Contoh: 88">
                            @error('nilai_mapel.matematika')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nilai_seni_budaya" class="form-label">
                                <i class="fas fa-palette me-1"></i>Seni Budaya
                            </label>
                            <input type="number" name="nilai_mapel[seni_budaya]" id="nilai_seni_budaya" 
                                   class="form-control @error('nilai_mapel.seni_budaya') is-invalid @enderror" 
                                   min="0" max="100" step="0.1" required placeholder="Contoh: 82">
                            @error('nilai_mapel.seni_budaya')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-calculator me-2"></i>
                            Hitung Rekomendasi Program Studi
                        </button>
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="col-lg-4">
        <!-- Kriteria AHP -->
        <div class="card shadow mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Kriteria Penilaian
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">Sistem menggunakan metode AHP (Analytical Hierarchy Process) dengan kriteria berikut:</p>
                
                @if($kriteria->count() > 0)
                    @foreach($kriteria as $k)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold">{{ $k->nama_kriteria }}</span>
                            <span class="badge bg-primary">{{ number_format($k->bobot * 100, 0) }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $k->bobot * 100 }}%"></div>
                        </div>
                        @if($k->deskripsi)
                        <p class="small text-muted mb-0 mt-1">{{ $k->deskripsi }}</p>
                        @endif
                    </div>
                    @endforeach
                @else
                    <p class="text-muted small">Kriteria belum dikonfigurasi oleh admin.</p>
                @endif
            </div>
        </div>

        <!-- Program Studi Available -->
        <div class="card shadow">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-university me-2"></i>
                    Program Studi Tersedia
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">Sistem akan merekomendasikan dari {{ $prodis->count() }} program studi:</p>
                
                <div class="list-group list-group-flush">
                    @foreach($prodis->take(5) as $prodi)
                    <div class="list-group-item px-0 py-2 border-0">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small>{{ $prodi->nama_prodi }}</small>
                    </div>
                    @endforeach
                    
                    @if($prodis->count() > 5)
                    <div class="list-group-item px-0 py-2 border-0">
                        <small class="text-muted">Dan {{ $prodis->count() - 5 }} lainnya...</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .form-select-lg, .form-control {
        border-radius: 8px;
    }
    
    .card {
        border-radius: 12px;
        border: none;
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }
    
    .progress {
        border-radius: 10px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Form validation
    document.getElementById('spkForm').addEventListener('submit', function(e) {
        const nilaiInputs = document.querySelectorAll('input[type="number"]');
        let valid = true;
        
        nilaiInputs.forEach(input => {
            const value = parseFloat(input.value);
            if (isNaN(value) || value < 0 || value > 100) {
                valid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Pastikan semua nilai mata pelajaran diisi dengan benar (0-100)');
        }
    });
    
    // Real-time validation for number inputs
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (value < 0) this.value = 0;
            if (value > 100) this.value = 100;
        });
    });
</script>
@endpush

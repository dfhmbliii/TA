@extends('layouts.app')

@section('title', 'Analisis ' . $prodi->nama_prodi)

@php
function getFakultasColor($fakultas) {
    $fakultasLower = strtolower($fakultas);
    if (str_contains($fakultasLower, 'rekayasa industri')) return '#208E3B';
    elseif (str_contains($fakultasLower, 'teknik elektro')) return '#007ACC';
    elseif (str_contains($fakultasLower, 'informatika')) return '#A48B31';
    elseif (str_contains($fakultasLower, 'industri kreatif')) return '#F57D20';
    return '#6c757d';
}
@endphp

@section('content')
<div class="content-header" style="border-left-color: {{ getFakultasColor($prodi->nama_fakultas) }};">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Analisis: {{ $prodi->nama_prodi }}</h1>
            <p>Matriks perbandingan berpasangan mata pelajaran</p>
        </div>
        <a href="{{ route('prodi.list') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session("prodi_{$prodi->id}_cr"))
    @php
        $cr = session("prodi_{$prodi->id}_cr");
        $isConsistent = $cr < 0.1;
    @endphp
    <div class="alert {{ $isConsistent ? 'alert-success' : 'alert-warning' }} alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">
            <i class="fas {{ $isConsistent ? 'fa-check-circle' : 'fa-exclamation-triangle' }} me-2"></i>
            Consistency Ratio (CR): {{ number_format($cr, 4) }}
        </h5>
        <p class="mb-0">
            @if($isConsistent)
                <strong>Konsisten!</strong> Perbandingan konsisten (CR < 0.1).
            @else
                <strong>Tidak Konsisten!</strong> Mohon periksa kembali nilai perbandingan (CR ≥ 0.1).
            @endif
        </p>
        <small class="d-block mt-2">λmax: {{ number_format(session("prodi_{$prodi->id}_lambda"), 4) }}</small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Alternatif List -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header" style="background-color: {{ getFakultasColor($prodi->nama_fakultas) }}; color: white;">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Mata Pelajaran
                </h5>
            </div>
            <div class="card-body">
                @if($alternatives->isEmpty())
                    <p class="text-muted text-center py-3">Belum ada mata pelajaran. Klik tombol di bawah untuk menambahkan.</p>
                    <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#addAlternativeModal">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Mata Pelajaran
                    </button>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($alternatives as $alt)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $alt->nama_alternatif }}</h6>
                                    <small class="text-muted">{{ $alt->kode_alternatif }}</small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge" style="background-color: {{ getFakultasColor($prodi->nama_fakultas) }};">
                                        {{ number_format($alt->bobot, 4) }}
                                    </span>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editAlternativeModal{{ $alt->id }}"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('prodi.delete-alternative', [$prodi->id, $alt->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                onclick="return confirm('Hapus {{ $alt->nama_alternatif }}? Data perbandingan terkait akan ikut terhapus.')"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted d-block mb-2"><strong>Total Bobot:</strong></small>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar" role="progressbar" 
                                style="width: {{ $alternatives->sum('bobot') * 100 }}%; background-color: {{ getFakultasColor($prodi->nama_fakultas) }};">
                                {{ number_format($alternatives->sum('bobot'), 4) }}
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-3" data-bs-toggle="modal" data-bs-target="#addAlternativeModal">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Mata Pelajaran
                    </button>
                @endif
            </div>
        </div>

        <!-- AHP Scale Reference -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Skala AHP</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>1</td><td>Sama penting</td></tr>
                        <tr><td>3</td><td>Sedikit lebih penting</td></tr>
                        <tr><td>5</td><td>Lebih penting</td></tr>
                        <tr><td>7</td><td>Sangat lebih penting</td></tr>
                        <tr><td>9</td><td>Mutlak lebih penting</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pairwise Comparison Matrix -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header" style="background-color: {{ getFakultasColor($prodi->nama_fakultas) }}; color: white;">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Matriks Perbandingan Berpasangan
                </h5>
            </div>
            <div class="card-body">
                @if($alternatives->isEmpty())
                    <p class="text-muted text-center py-5">Tambahkan mata pelajaran terlebih dahulu untuk membuat matriks perbandingan.</p>
                @else
                    <form action="{{ route('prodi.store-pairwise', $prodi->id) }}" method="POST" id="pairwiseForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        @foreach($alternatives as $alt)
                                            <th>{{ $alt->nama_alternatif }}</th>
                                        @endforeach
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alternatives as $a1)
                                        <tr>
                                            <th class="table-light">{{ $a1->nama_alternatif }}</th>
                                            @foreach($alternatives as $a2)
                                                <td>
                                                    @if($a1->id == $a2->id)
                                                        <span class="badge bg-secondary">1</span>
                                                    @elseif($a1->id < $a2->id)
                                                        <input type="number" 
                                                            class="form-control form-control-sm text-center matrix-input" 
                                                            name="comparisons[{{ $a1->id }}_{{ $a2->id }}][nilai]"
                                                            value="{{ $matrix[$a1->id][$a2->id] ?? 1 }}"
                                                            step="0.01"
                                                            min="0.11"
                                                            max="9"
                                                            data-row="{{ $a1->id }}"
                                                            data-col="{{ $a2->id }}"
                                                            style="width: 80px; display: inline-block;">
                                                        <input type="hidden" name="comparisons[{{ $a1->id }}_{{ $a2->id }}][alternative_1_id]" value="{{ $a1->id }}">
                                                        <input type="hidden" name="comparisons[{{ $a1->id }}_{{ $a2->id }}][alternative_2_id]" value="{{ $a2->id }}">
                                                    @else
                                                        <span class="badge bg-info reciprocal" data-row="{{ $a1->id }}" data-col="{{ $a2->id }}">
                                                            {{ $matrix[$a1->id][$a2->id] ? number_format($matrix[$a1->id][$a2->id], 2) : '1.00' }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="table-light">
                                                <strong class="row-total" data-row="{{ $a1->id }}">
                                                    {{ number_format(array_sum($matrix[$a1->id] ?? []), 2) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetMatrix()">
                                <i class="fas fa-undo me-2"></i>
                                Reset ke 1
                            </button>
                            <button type="submit" class="btn btn-primary" style="background-color: {{ getFakultasColor($prodi->nama_fakultas) }}; border-color: {{ getFakultasColor($prodi->nama_fakultas) }};">
                                <i class="fas fa-calculator me-2"></i>
                                Hitung Bobot AHP
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Alternative Modal -->
<div class="modal fade" id="addAlternativeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Mata Pelajaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('prodi.initialize-alternatives', $prodi->id) }}" method="POST" id="addAlternativeForm">
                @csrf
                <div class="modal-body">
                    <div id="alternativesList">
                        <div class="mb-3 alternative-item">
                            <label class="form-label">Mata Pelajaran 1</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="alternatives[0][nama]" placeholder="Nama mata pelajaran" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="alternatives[0][kode]" placeholder="Kode" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAlternativeField()">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Lagi
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let alternativeCount = 1;

function addAlternativeField() {
    const html = `
        <div class="mb-3 alternative-item">
            <label class="form-label">Mata Pelajaran ${alternativeCount + 1}</label>
            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="alternatives[${alternativeCount}][nama]" placeholder="Nama mata pelajaran" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="alternatives[${alternativeCount}][kode]" placeholder="Kode" required>
                </div>
            </div>
        </div>
    `;
    document.getElementById('alternativesList').insertAdjacentHTML('beforeend', html);
    alternativeCount++;
}

// Auto-update reciprocal values
document.querySelectorAll('.matrix-input').forEach(input => {
    input.addEventListener('input', function() {
        const row = this.dataset.row;
        const col = this.dataset.col;
        const value = parseFloat(this.value) || 1;
        
        // Update reciprocal cell
        const reciprocal = document.querySelector(`.reciprocal[data-row="${col}"][data-col="${row}"]`);
        if (reciprocal) {
            reciprocal.textContent = (1 / value).toFixed(2);
        }
        
        // Update row totals
        updateRowTotal(row);
        updateRowTotal(col);
    });
});

function updateRowTotal(rowId) {
    let total = 0;
    
    document.querySelectorAll(`[data-row="${rowId}"]`).forEach(cell => {
        if (cell.classList.contains('matrix-input')) {
            total += parseFloat(cell.value) || 1;
        } else if (cell.classList.contains('reciprocal')) {
            total += parseFloat(cell.textContent) || 1;
        }
    });
    
    total += 1; // diagonal
    
    const totalCell = document.querySelector(`.row-total[data-row="${rowId}"]`);
    if (totalCell) {
        totalCell.textContent = total.toFixed(2);
    }
}

function resetMatrix() {
    if (confirm('Reset semua nilai perbandingan ke 1?')) {
        document.querySelectorAll('.matrix-input').forEach(input => {
            input.value = 1;
            input.dispatchEvent(new Event('input'));
        });
    }
}
</script>
@endpush

<!-- Edit Alternative Modals -->
@foreach($alternatives as $alt)
<div class="modal fade" id="editAlternativeModal{{ $alt->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Mata Pelajaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('prodi.update-alternative', [$prodi->id, $alt->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_alternatif{{ $alt->id }}" class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" class="form-control" id="nama_alternatif{{ $alt->id }}" 
                            name="nama_alternatif" value="{{ $alt->nama_alternatif }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="kode_alternatif{{ $alt->id }}" class="form-label">Kode</label>
                        <input type="text" class="form-control" id="kode_alternatif{{ $alt->id }}" 
                            name="kode_alternatif" value="{{ $alt->kode_alternatif }}" required>
                    </div>
                    <div class="alert alert-info">
                        <small><i class="fas fa-info-circle me-1"></i> 
                        Bobot akan dihitung ulang setelah perbandingan di matriks.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" style="background-color: {{ getFakultasColor($prodi->nama_fakultas) }}; border-color: {{ getFakultasColor($prodi->nama_fakultas) }};">
                        <i class="fas fa-save me-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

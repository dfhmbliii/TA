@extends('layouts.app')

@section('title', 'SPK Analysis')

@section('content')
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="d-flex align-items-center gap-2">
                <span>SPK Analysis - Analisis Kriteria</span>
                @php
                    $cr = session('ahp_consistency_ratio');
                @endphp
                @if(!is_null($cr))
                    @if($cr < 0.1)
                        <span class="badge bg-success">Konsisten (CR {{ number_format($cr, 3) }})</span>
                    @else
                        <span class="badge bg-warning text-dark">Tidak Konsisten (CR {{ number_format($cr, 3) }})</span>
                    @endif
                @endif
            </h1>
            <p>Tentukan bobot kriteria menggunakan metode AHP (Analytical Hierarchy Process)</p>
        </div>
        <a href="{{ route('spk.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali ke Dashboard
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

@if(session('ahp_consistency_ratio'))
    @php
        $cr = session('ahp_consistency_ratio');
        $isConsistent = $cr < 0.1;
    @endphp
    <div class="alert {{ $isConsistent ? 'alert-success' : 'alert-warning' }} alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">
            <i class="fas {{ $isConsistent ? 'fa-check-circle' : 'fa-exclamation-triangle' }} me-2"></i>
            Consistency Ratio (CR): {{ number_format($cr, 4) }}
        </h5>
        <p class="mb-0">
            @if($isConsistent)
                <strong>Konsisten!</strong> Perbandingan Anda konsisten (CR < 0.1). Bobot kriteria dapat digunakan.
            @else
                <strong>Tidak Konsisten!</strong> Perbandingan Anda tidak konsisten (CR ≥ 0.1). Mohon periksa kembali nilai perbandingan.
            @endif
        </p>
        <small class="d-block mt-2">λmax: {{ number_format(session('ahp_lambda_max'), 4) }}</small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Kriteria Card -->
<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Daftar Kriteria
                    </h5>
                    @if($kriteria->isEmpty())
                        <form action="{{ route('spk.initialize-criteria') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Initialize
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($kriteria->isEmpty())
                    <p class="text-muted text-center py-3">Belum ada kriteria. Klik "Initialize" untuk menambahkan kriteria default.</p>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($kriteria as $k)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $k->nama_kriteria }}</h6>
                                    <small class="text-muted">{{ $k->kode_kriteria }}</small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary">
                                        Bobot: {{ number_format($k->bobot, 4) }}
                                    </span>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editKriteriaModal{{ $k->id }}"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('spk.delete-kriteria', $k->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger"
                                                onclick="return confirm('Hapus kriteria {{ $k->nama_kriteria }}? Semua data perbandingan terkait akan ikut terhapus.')"
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
                            <div class="progress-bar" role="progressbar" style="width: {{ $kriteria->sum('bobot') * 100 }}%">
                                {{ number_format($kriteria->sum('bobot'), 4) }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- AHP Scale Reference -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Skala Perbandingan AHP</h6>
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
                        <tr><td>2,4,6,8</td><td>Nilai antara</td></tr>
                        <tr><td>1/3, 1/5, ...</td><td>Kebalikan</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pairwise Comparison Matrix -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Matriks Perbandingan Berpasangan
                </h5>
            </div>
            <div class="card-body">
                @if($kriteria->isEmpty())
                    <p class="text-muted text-center py-5">Inisialisasi kriteria terlebih dahulu untuk membuat matriks perbandingan.</p>
                @else
                    <form action="{{ route('spk.store-pairwise') }}" method="POST" id="pairwiseForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        @foreach($kriteria as $k)
                                            <th>{{ $k->nama_kriteria }}</th>
                                        @endforeach>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kriteria as $k1)
                                        <tr>
                                            <th class="table-light">{{ $k1->nama_kriteria }}</th>
                                            @foreach($kriteria as $k2)
                                                <td>
                                                    @if($k1->id == $k2->id)
                                                        <span class="badge bg-secondary">1</span>
                                                    @elseif($k1->id < $k2->id)
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center matrix-input"
                                                            name="comparisons[{{ $k1->id }}_{{ $k2->id }}][nilai]"
                                                            value="{{ $matrix[$k1->id][$k2->id] ?? 1 }}"
                                                            inputmode="decimal"
                                                            data-row="{{ $k1->id }}"
                                                            data-col="{{ $k2->id }}"
                                                            style="width: 80px; display: inline-block;">
                                                        <input type="hidden" name="comparisons[{{ $k1->id }}_{{ $k2->id }}][kriteria_1_id]" value="{{ $k1->id }}">
                                                        <input type="hidden" name="comparisons[{{ $k1->id }}_{{ $k2->id }}][kriteria_2_id]" value="{{ $k2->id }}">
                                                    @else
                                                        <span class="badge bg-info reciprocal" data-row="{{ $k1->id }}" data-col="{{ $k2->id }}">
                                                            {{ $matrix[$k1->id][$k2->id] ? number_format($matrix[$k1->id][$k2->id], 3, '.', '') : '1.000' }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endforeach>
                                            <td class="table-light">
                                                <strong class="row-total" data-row="{{ $k1->id }}">
                                                    {{ number_format(array_sum($matrix[$k1->id] ?? []), 2) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    @endforeach>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetMatrix()">
                                <i class="fas fa-undo me-2"></i>
                                Reset ke 1
                            </button>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-calculator me-2"></i>
                                    Hitung Bobot AHP
                                </button>
                                <form action="{{ route('spk.apply-weights') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i>
                                        Terapkan Bobot
                                    </button>
                                </form>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Completely hide number input spinner buttons */
.matrix-input,
input.matrix-input,
input[type="number"].matrix-input {
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: textfield !important;
    -ms-appearance: none !important;
    padding-right: 4px !important;
    overflow: hidden !important;
}

/* Target all pseudo-elements that create spinners */
.matrix-input::-webkit-outer-spin-button,
.matrix-input::-webkit-inner-spin-button,
input.matrix-input::-webkit-outer-spin-button,
input.matrix-input::-webkit-inner-spin-button,
input[type="number"].matrix-input::-webkit-outer-spin-button,
input[type="number"].matrix-input::-webkit-inner-spin-button {
    -webkit-appearance: none !important;
    appearance: none !important;
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
    margin: 0 !important;
    padding: 0 !important;
    height: 0 !important;
    width: 0 !important;
    border: none !important;
    background: none !important;
}

/* Firefox */
.matrix-input[type="number"]::-moz-number-spin-box {
    display: none !important;
}

.matrix-input[type="number"] {
    -moz-appearance: textfield !important;
}
</style>
@endpush

@push('scripts')
<script>
// Auto-update reciprocal values with AHP scale snapping for readability
function snapToAHPScale(x) {
    const scale = [1,2,3,4,5,6,7,8,9];
    let best = x, bestDiff = Infinity;
    for (const s of scale) {
        const d = Math.abs(x - s);
        if (d < bestDiff) { bestDiff = d; best = s; }
    }
    // Snap only if close enough (within 0.05)
    return bestDiff <= 0.05 ? best : x;
}

// Snap small decimals to nearest reciprocal 1/n (n=2..9)
function snapToReciprocalScale(x) {
    const candidates = [1/2,1/3,1/4,1/5,1/6,1/7,1/8,1/9];
    let best = x, bestDiff = Infinity;
    for (const c of candidates) {
        const d = Math.abs(x - c);
        if (d < bestDiff) { bestDiff = d; best = c; }
    }
    return bestDiff <= 0.01 ? best : x; // tighter threshold for decimals
}

document.querySelectorAll('.matrix-input').forEach(input => {
    // Allow free editing: don't force a value while typing
    input.addEventListener('input', function() {
        const row = this.dataset.row;
        const col = this.dataset.col;
        // Support locale input with comma decimals
        const raw = (this.value || '').toString();
        if (raw.trim() === '') {
            // If empty, let user continue editing; defer normalization to blur
            return;
        }
        let value = parseFloat(raw.replace(',', '.'));
        if (isNaN(value)) {
            // Invalid partial input; don't overwrite while typing
            return;
        }

        // If user types small decimal, snap to nearest 1/n for clarity
        if (value > 0 && value < 1) {
            value = snapToReciprocalScale(value);
            this.value = Number(value).toFixed(3); // show 3 decimals for small values
        }

        // Update reciprocal cell
        const reciprocal = document.querySelector(`.reciprocal[data-row="${col}"][data-col="${row}"]`);
        if (reciprocal) {
            const recip = 1 / value;
            const snapped = snapToAHPScale(recip);
            reciprocal.textContent = Number(snapped).toFixed(3);
        }

        // Update row totals
        updateRowTotal(row);
        updateRowTotal(col);
    });
    });

    // On blur, normalize empty/invalid to 1 and refresh computed cells
    input.addEventListener('blur', function() {
        const row = this.dataset.row;
        const col = this.dataset.col;
        const raw = (this.value || '').toString();
        let value = parseFloat(raw.replace(',', '.'));
        if (isNaN(value) || raw.trim() === '') {
            value = 1;
            this.value = '1';
        }

        if (value > 0 && value < 1) {
            value = snapToReciprocalScale(value);
            this.value = Number(value).toFixed(3);
        }

        const reciprocal = document.querySelector(`.reciprocal[data-row="${col}"][data-col="${row}"]`);
        if (reciprocal) {
            const recip = 1 / value;
            const snapped = snapToAHPScale(recip);
            reciprocal.textContent = Number(snapped).toFixed(3);
        }

        updateRowTotal(row);
        updateRowTotal(col);
    });
});

function updateRowTotal(rowId) {
    let total = 0;

    // Sum all values in the row
    document.querySelectorAll(`[data-row="${rowId}"]`).forEach(cell => {
        if (cell.classList.contains('matrix-input')) {
            total += parseFloat(cell.value) || 1;
        } else if (cell.classList.contains('reciprocal')) {
            total += parseFloat(cell.textContent) || 1;
        }
    });

    // Add diagonal value (always 1)
    total += 1;

    const totalCell = document.querySelector(`.row-total[data-row="${rowId}"]`);
    if (totalCell) {
        totalCell.textContent = total.toFixed(3);
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

<!-- Edit Kriteria Modals -->
@foreach($kriteria as $k)
<div class="modal fade" id="editKriteriaModal{{ $k->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Kriteria
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('spk.update-kriteria', $k->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kriteria{{ $k->id }}" class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control" id="nama_kriteria{{ $k->id }}"
                            name="nama_kriteria" value="{{ $k->nama_kriteria }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="kode_kriteria{{ $k->id }}" class="form-label">Kode Kriteria</label>
                        <input type="text" class="form-control" id="kode_kriteria{{ $k->id }}"
                            name="kode_kriteria" value="{{ $k->kode_kriteria }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi{{ $k->id }}" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi{{ $k->id }}"
                            name="deskripsi" rows="3">{{ $k->deskripsi }}</textarea>
                    </div>
                    <div class="alert alert-info">
                        <small><i class="fas fa-info-circle me-1"></i>
                        Bobot akan dihitung ulang setelah Anda melakukan perbandingan di matriks.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
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

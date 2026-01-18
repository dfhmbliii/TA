@extends('layouts.app')

@section('title', 'SPK - Perbandingan Prospek Karir')

@section('content')
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0 d-flex align-items-center gap-2">
            <span>Perbandingan Prospek Karir</span>
            @php($krCr = session('karir_cr'))
            @if(!is_null($krCr))
                @if($krCr < 0.1)
                    <span class="badge bg-success">Konsisten (CR {{ number_format($krCr, 3) }})</span>
                @else
                    <span class="badge bg-warning text-dark">Tidak Konsisten (CR {{ number_format($krCr, 3) }})</span>
                @endif
            @endif
        </h1>
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

@if(session('karir_cr'))
    @php($krCr = session('karir_cr'))
    @php($krLambda = session('karir_lambda_max'))
    <div class="alert {{ $krCr < 0.1 ? 'alert-success' : 'alert-warning' }} alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">
            <i class="fas {{ $krCr < 0.1 ? 'fa-check-circle' : 'fa-exclamation-triangle' }} me-2"></i>
            Consistency Ratio (CR): {{ number_format($krCr, 4) }}
        </h5>
        <p class="mb-0">
            @if($krCr < 0.1)
                <strong>Konsisten!</strong> Perbandingan Anda konsisten (CR < 0.1). Bobot dapat digunakan.
            @else
                <strong>Tidak Konsisten!</strong> Perbandingan belum konsisten (CR ≥ 0.1). Silakan tinjau kembali input.
            @endif
        </p>
        <small class="d-block mt-2">λmax: {{ number_format($krLambda, 4) }}</small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Daftar Kategori Prospek Karir
                    </h5>
                    @if($categories->isEmpty())
                        <form action="{{ route('spk.karir.initialize') }}" method="POST">
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
                @if($categories->isEmpty())
                    <p class="text-muted text-center py-3">Belum ada kategori. Klik "Initialize" untuk menambahkan kategori default.</p>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($categories as $c)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $c->nama }}</h6>
                                    <small class="text-muted">{{ $c->kode }}</small>
                                </div>
                                <span class="badge bg-primary">
                                    Bobot: {{ number_format($c->bobot, 4) }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted d-block mb-2"><strong>Total Bobot:</strong></small>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $categories->sum('bobot') * 100 }}%">
                                {{ number_format($categories->sum('bobot'), 4) }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

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

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Matriks Perbandingan Berpasangan
                </h5>
            </div>
            <div class="card-body">
                @if($categories->isEmpty())
                    <p class="text-muted text-center py-5">Inisialisasi kategori terlebih dahulu untuk membuat matriks perbandingan.</p>
                @else
                    <form action="{{ route('spk.karir.store-pairwise') }}" method="POST" id="pairwiseForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        @foreach($categories as $c)
                                            <th>{{ $c->nama }}</th>
                                        @endforeach
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $c1)
                                        <tr>
                                            <th class="table-light">{{ $c1->nama }}</th>
                                            @foreach($categories as $c2)
                                                <td>
                                                    @if($c1->id == $c2->id)
                                                        <span class="badge bg-secondary">1</span>
                                                    @elseif($c1->id < $c2->id)
                                                        <input type="text" class="form-control form-control-sm text-center matrix-input"
                                                               name="comparisons[{{ $c1->id }}_{{ $c2->id }}][nilai]"
                                                               value="{{ $matrix[$c1->id][$c2->id] ?? 1 }}"
                                                               inputmode="decimal"
                                                               data-row="{{ $c1->id }}" data-col="{{ $c2->id }}"
                                                               style="width: 80px; display: inline-block;">
                                                        <input type="hidden" name="comparisons[{{ $c1->id }}_{{ $c2->id }}][category_1_id]" value="{{ $c1->id }}">
                                                        <input type="hidden" name="comparisons[{{ $c1->id }}_{{ $c2->id }}][category_2_id]" value="{{ $c2->id }}">
                                                    @else
                                                        <span class="badge bg-info reciprocal" data-row="{{ $c1->id }}" data-col="{{ $c2->id }}">
                                                            {{ $matrix[$c1->id][$c2->id] ? number_format($matrix[$c1->id][$c2->id], 3, '.', '') : '1.000' }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="table-light"><strong class="row-total" data-row="{{ $c1->id }}">{{ number_format(array_sum($matrix[$c1->id] ?? []), 2) }}</strong></td>
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
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-calculator me-2"></i>
                                    Hitung Bobot AHP
                                </button>
                                <form action="{{ route('spk.karir.apply-weights') }}" method="POST">
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

@push('scripts')
<script>
function snapToAHPScale(x){const s=[1,2,3,4,5,6,7,8,9];let b=x,d=Infinity;for(const v of s){const t=Math.abs(x-v);if(t<d){d=t;b=v}}return d<=0.05?b:x}
function snapToReciprocalScale(x){const c=[1/2,1/3,1/4,1/5,1/6,1/7,1/8,1/9];let b=x,d=Infinity;for(const v of c){const t=Math.abs(x-v);if(t<d){d=t;b=v}}return d<=0.01?b:x}

document.querySelectorAll('.matrix-input').forEach(input=>{
  input.addEventListener('input',function(){
    const row=this.dataset.row,col=this.dataset.col;
    const raw=(this.value||'').toString();
    if(raw.trim()===''){return}
    let value=parseFloat(raw.replace(',','.'));
    if(isNaN(value)){return}
    const reciprocal=document.querySelector(`.reciprocal[data-row="${col}"][data-col="${row}"]`);
    if(reciprocal){const recip=1/value;const snapped=snapToAHPScale(recip);reciprocal.textContent=Number(snapped).toFixed(2)}
    updateRowTotal(row);updateRowTotal(col);
  });
  input.addEventListener('blur',function(){
    const row=this.dataset.row,col=this.dataset.col;
    const raw=(this.value||'').toString();
    let value=parseFloat(raw.replace(',','.'));
    if(isNaN(value)||raw.trim()===''){value=1;this.value='1'}
    if(value>0&&value<1){value=snapToReciprocalScale(value);this.value=Number(value).toFixed(3)}
    const reciprocal=document.querySelector(`.reciprocal[data-row="${col}"][data-col="${row}"]`);
    if(reciprocal){const recip=1/value;const snapped=snapToAHPScale(recip);reciprocal.textContent=Number(snapped).toFixed(2)}
    updateRowTotal(row);updateRowTotal(col);
  });
});

function updateRowTotal(rowId){let total=0;document.querySelectorAll(`[data-row="${rowId}"]`).forEach(cell=>{if(cell.classList.contains('matrix-input')){total+=parseFloat(cell.value)||1}else if(cell.classList.contains('reciprocal')){total+=parseFloat(cell.textContent)||1}});total+=1;const el=document.querySelector(`.row-total[data-row="${rowId}"]`);if(el){el.textContent=total.toFixed(2)}}
function resetMatrix(){if(confirm('Reset semua nilai perbandingan ke 1?')){document.querySelectorAll('.matrix-input').forEach(input=>{input.value=1;input.dispatchEvent(new Event('input'))})}}
</script>
@endpush
@endsection
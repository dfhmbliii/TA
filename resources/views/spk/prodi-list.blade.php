@extends('layouts.app')

@section('title', 'Analisis Per Program Studi')

@section('content')
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Analisis Per Program Studi</h1>
            <p>Kelola matriks perbandingan mata pelajaran untuk setiap program studi</p>
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

<div class="row">
    @foreach($prodis as $prodi)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header" style="background: linear-gradient(135deg, {{ getFakultasColor($prodi->nama_fakultas) }}, {{ adjustColor(getFakultasColor($prodi->nama_fakultas)) }});">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-graduation-cap me-2"></i>
                    {{ $prodi->nama_prodi }}
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    <i class="fas fa-university me-2"></i>
                    {{ $prodi->nama_fakultas }}
                </p>
                <p class="text-muted mb-3">
                    <i class="fas fa-code me-2"></i>
                    <span class="badge bg-primary">{{ $prodi->kode_prodi }}</span>
                </p>

                @if($prodi->alternatives->isEmpty())
                    <div class="alert alert-warning mb-3">
                        <small><i class="fas fa-exclamation-triangle me-1"></i> Belum ada alternatif</small>
                    </div>
                @else
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2"><strong>Mata Pelajaran:</strong></small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($prodi->alternatives as $alt)
                                <span class="badge bg-secondary">{{ $alt->nama_alternatif }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar" role="progressbar" 
                            style="width: {{ $prodi->alternatives->sum('bobot') * 100 }}%">
                            {{ number_format($prodi->alternatives->sum('bobot'), 2) }}
                        </div>
                    </div>
                @endif

                <a href="{{ route('prodi.analysis', $prodi->id) }}" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-chart-line me-2"></i>
                    Kelola Matriks
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@php
function getFakultasColor($fakultas) {
    $fakultasLower = strtolower($fakultas);
    if (str_contains($fakultasLower, 'rekayasa industri')) {
        return '#208E3B';
    } elseif (str_contains($fakultasLower, 'teknik elektro')) {
        return '#007ACC';
    } elseif (str_contains($fakultasLower, 'informatika')) {
        return '#A48B31';
    } elseif (str_contains($fakultasLower, 'industri kreatif')) {
        return '#F57D20';
    }
    return '#6c757d';
}

function adjustColor($hex) {
    // Darken color slightly for gradient
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    $r = max(0, $r - 30);
    $g = max(0, $g - 30);
    $b = max(0, $b - 30);
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}
@endphp

@endsection

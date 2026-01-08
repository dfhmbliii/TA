@extends('layouts.app')
@section('title','Pengumuman Sistem')
@section('content')
<div class="content-header">
    <h1>Buat Pengumuman Sistem</h1>
    <p>Kirim pengumuman ke seluruh pengguna. Dapat disertai email jika diaktifkan.</p>
</div>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.announcement.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Judul Pengumuman</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Pengumuman</label>
                <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="send_email" id="send_email" value="1">
                <label class="form-check-label" for="send_email">
                    Kirim juga melalui email (menghormati preferensi Email Notifications tiap user)
                </label>
            </div>
            <button class="btn btn-primary"><i class="fas fa-bullhorn me-2"></i> Kirim Pengumuman</button>
        </form>
    </div>
</div>
@endsection
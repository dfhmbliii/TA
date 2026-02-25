@extends('layouts.auth')
@section('title','Reset Password')
@section('content')
<div class="mb-4 text-center">
    <h1 class="h4 fw-bold">Reset Password</h1>
    <p class="text-muted">Masukkan password baru Anda.</p>
</div>
<form method="POST" action="{{ route('password.update') }}" class="vstack gap-3">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Password Baru</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
    <button class="btn btn-primary w-100">
        <i class="fas fa-key me-2"></i>Reset Password
    </button>
</form>
<div class="mt-4 text-center">
    <a href="{{ route('login') }}" class="text-decoration-none">Kembali ke Login</a>
</div>
@endsection

@section('head')
    @parent
    <link rel="icon" type="image/png" href="{{ asset('images/Pilihanku3.png') }}">
@endsection

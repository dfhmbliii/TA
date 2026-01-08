@extends('layouts.auth')
@section('title','Lupa Password')
@section('content')
<div class="mb-4 text-center">
    <h1 class="h4 fw-bold">Lupa Password</h1>
    <p class="text-muted">Masukkan email Anda, kami akan kirimkan link reset password.</p>
</div>
@if (session('status'))
    <div class="alert alert-success">Link reset password telah dikirim ke email Anda.</div>
@endif
<form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
    @csrf
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <button class="btn btn-primary w-100">
        <i class="fas fa-envelope me-2"></i>Kirim Link Reset
    </button>
</form>
<div class="mt-4 text-center">
    <a href="{{ route('login') }}" class="text-decoration-none">Kembali ke Login</a>
</div>
@endsection

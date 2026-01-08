<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auth') - Pilihanku</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Pilihanku3.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh; display:flex; align-items:center; background: linear-gradient(135deg,#4f46e5,#3730a3); }
        .auth-wrapper { background:#fff; border-radius:1rem; box-shadow:0 10px 30px rgba(0,0,0,0.15); overflow:hidden; max-width:960px; margin:2rem auto; width:100%; display:grid; grid-template-columns: 1fr 1fr; }
    @media (max-width: 768px){ .auth-wrapper { grid-template-columns:1fr; } .auth-side{display:none;} }
    .auth-side { padding:3rem 2.5rem 3.75rem; color:#fff; background:linear-gradient(160deg,#4f46e5 0%, #3730a3 100%); display:flex; flex-direction:column; gap:1.5rem; }
        .auth-side h1 { font-weight:700; font-size:1.9rem; }
    .auth-side .feature { background:rgba(255,255,255,0.12); padding:1rem 1.1rem; border-radius:0.75rem; display:flex; gap:0.75rem; align-items:flex-start; margin-bottom:1rem; }
        .auth-side .feature i { font-size:1.25rem; }
        .auth-form { padding:2.5rem; }
        .btn-primary { background:#4f46e5; border:none; }
        .btn-primary:hover { background:#3730a3; }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-side">
            <div>
                <div class="mb-4 text-center">
                    <div style="width:64px;height:64px;border-radius:50%;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <img src="{{ asset('images/Pilihanku3.png') }}" alt="Logo Pilihanku" style="width: 100%; height: 100%; object-fit: contain; border-radius: 50%;">
                    </div>
                    <h1>Pilihanku</h1>
                    <p class="text-white-50 mb-0">Sistem Pendukung Keputusan untuk Seleksi siswa</p>
                </div>
                <div class="feature">
                    <i class="fas fa-chart-line"></i>
                    <div>
                        <strong>Analisis SPK</strong><br><small>Multi-kriteria decision making</small>
                    </div>
                </div>
                <div class="feature">
                    <i class="fas fa-user-cog"></i>
                    <div>
                        <strong>Manajemen Data</strong><br><small>Kelola siswa & program studi</small>
                    </div>
                </div>
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Keamanan</strong><br><small>Sistem login yang aman</small>
                    </div>
                </div>
            </div>
            <div class="mt-auto small text-white-50">&copy; {{ date('Y') }} Pilihanku v1.0</div>
        </div>
        <div class="auth-form">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

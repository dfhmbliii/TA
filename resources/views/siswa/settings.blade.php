@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="content-header">
    <h1>Pengaturan Akun</h1>
    <p>Kelola pengaturan dan keamanan akun Anda</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Security Settings -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-lock me-2"></i>
                    Keamanan Akun
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            <i class="fas fa-key me-1"></i>
                            Password Saat Ini
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                            <button type="button" class="btn btn-outline-secondary" id="toggleCurrentPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">
                            <i class="fas fa-lock me-1"></i>
                            Password Baru
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password" 
                                   required>
                            <button type="button" class="btn btn-outline-secondary" id="toggleNewPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Minimal 8 karakter</div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">
                            <i class="fas fa-lock me-1"></i>
                            Konfirmasi Password Baru
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   required>
                            <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bell me-2"></i>
                    Pengaturan Notifikasi
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.notifications.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" {{ (Auth::user()->email_notifications ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="email_notifications">
                                <i class="fas fa-envelope me-1"></i> Email Notifications
                            </label>
                        </div>
                        <div class="ms-4 text-muted">Terima notifikasi melalui email</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="spk_updates" name="spk_updates" {{ (Auth::user()->spk_updates ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="spk_updates">
                                <i class="fas fa-chart-line me-1"></i> Update Data SPK
                            </label>
                        </div>
                        <div class="ms-4 text-muted">Notifikasi saat ada perubahan data SPK</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="system_announcements" name="system_announcements" {{ (Auth::user()->system_announcements ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="system_announcements">
                                <i class="fas fa-bullhorn me-1"></i> Pengumuman Sistem
                            </label>
                        </div>
                        <div class="ms-4 text-muted">Terima pengumuman penting dari sistem</div>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(90deg,#4f46e5,#3730a3);">
                            <i class="fas fa-save me-2"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Akun
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>User ID:</strong><br>
                    <code>{{ Auth::user()->id }}</code>
                </p>
                <p class="mb-2">
                    <strong>Role:</strong><br>
                    <span class="badge bg-info">{{ ucfirst(Auth::user()->role) }}</span>
                </p>
                <p class="mb-2">
                    <strong>Akun dibuat:</strong><br>
                    <span class="exact-time" title="Tanggal lengkap">{{ Auth::user()->created_at->format('d M Y H:i') }}</span>
                    <small class="text-muted">• <span class="relative-time" data-time="{{ Auth::user()->created_at->toIso8601String() }}">memuat…</span></small>
                </p>
                <p class="mb-0">
                    <strong>Terakhir login:</strong><br>
                    @php($lastLogin = Auth::user()->last_login_at ?? Auth::user()->updated_at)
                    <span class="exact-time" title="Tanggal lengkap">{{ optional($lastLogin)->format('d M Y H:i') ?? '-' }}</span>
                    @if($lastLogin)
                        <small class="text-muted">• <span class="relative-time" data-time="{{ $lastLogin->toIso8601String() }}">memuat…</span></small>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Danger Zone -->
<div class="card border-danger mt-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Danger Zone
        </h5>
    </div>
    <div class="card-body">
        <h6>Hapus Akun</h6>
        <p class="text-muted">
            Setelah akun Anda dihapus, semua data dan informasi yang terkait akan dihapus secara permanen. 
            Permintaan hapus akun memerlukan persetujuan admin terlebih dahulu.
        </p>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            <i class="fas fa-paper-plane me-2"></i>
            Ajukan Hapus Akun
        </button>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Ajukan Permintaan Hapus Akun
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('siswa.account.delete') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>Informasi:</strong> Permintaan Anda akan ditinjau oleh admin terlebih dahulu.
                    </div>
                    <p>Setelah admin menyetujui, semua data Anda akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
                    <div class="mb-3">
                        <label for="delete_reason" class="form-label">
                            Alasan pengajuan hapus akun (opsional):
                        </label>
                        <textarea 
                            class="form-control" 
                            id="delete_reason" 
                            name="reason" 
                            rows="3"
                            placeholder="Contoh: Sudah lulus, pindah sekolah, dll."></textarea>
                        <small class="text-muted">Alasan ini akan dilihat oleh admin</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-paper-plane me-2"></i>
                        Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
 </div>

<script>
    // Toggle password visibility
    document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
        togglePasswordVisibility('current_password', this);
    });

    document.getElementById('toggleNewPassword').addEventListener('click', function() {
        togglePasswordVisibility('new_password', this);
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        togglePasswordVisibility('new_password_confirmation', this);
    });

    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
// Live update "x menit lalu"
function timeAgoFrom(date) {
    const units = [
        ['year', 365*24*60*60],
        ['month', 30*24*60*60],
        ['day', 24*60*60],
        ['hour', 60*60],
        ['minute', 60],
        ['second', 1],
    ];
    const rtf = new Intl.RelativeTimeFormat('id', { numeric: 'auto' });
    const now = Math.floor(Date.now()/1000);
    const ts = Math.floor(new Date(date).getTime()/1000);
    let diff = ts - now;
    for (const [unit, sec] of units) {
        if (Math.abs(diff) >= sec || unit === 'second') {
            return rtf.format(Math.round(diff/sec), unit);
        }
    }
    return '';
}
function refreshRelativeTimes(){
    document.querySelectorAll('.relative-time').forEach(el => {
        const t = el.getAttribute('data-time');
        if (t) el.textContent = timeAgoFrom(t);
    });
}
refreshRelativeTimes();
setInterval(refreshRelativeTimes, 60000);
</script>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pilihanku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --secondary-color: #f8fafc;
            --accent-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --dark-color: #1f2937;
            --light-color: #f9fafb;
            --border-color: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 2rem;
        }

        .login-container {
            background: white;
            border-radius: 1.5rem;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            min-height: 500px;
            display: flex;
        }

        .left-section {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .left-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .login-right {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .branding {
            position: relative;
            z-index: 1;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            margin-left: auto;
            margin-right: auto;
        }

        .login-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }

        .login-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
            width: 100%;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .form-subtitle {
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--light-color);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background: white;
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-login:active::before {
            width: 300px;
            height: 300px;
        }

        /* Google Login Button */
        .btn-google {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-primary);
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .btn-google:hover {
            border-color: var(--primary-color);
            background: var(--light-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: var(--text-primary);
        }

        .google-icon {
            flex-shrink: 0;
        }

        /* Divider */
        .divider-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        .divider-text {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .alert {
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border-left: 4px solid #dc2626;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            position: relative;
            z-index: 1;
        }

        .feature {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .feature i {
            font-size: 2rem;
            margin-bottom: 1rem;
            display: block;
        }

        .feature h6 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .feature p {
            font-size: 0.875rem;
            opacity: 0.9;
            margin: 0;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 1rem;
                border-radius: 1rem;
            }

            .left-section {
                padding: 2rem;
                min-height: 300px;
            }

            .login-right {
                padding: 2rem;
            }

            .login-title {
                font-size: 2rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .features {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }

        .left-section > * {
            animation: fadeInUp 0.6s ease-out;
        }

        .left-section > *:nth-child(1) { animation-delay: 0.1s; }
        .left-section > *:nth-child(2) { animation-delay: 0.2s; }
        .left-section > *:nth-child(3) { animation-delay: 0.3s; }
        .left-section > *:nth-child(4) { animation-delay: 0.4s; }

        .login-right > * {
            animation: fadeInUp 0.6s ease-out;
        }

        .login-right > *:nth-child(1) { animation-delay: 0.2s; }
        .login-right > *:nth-child(2) { animation-delay: 0.3s; }
        .login-right > *:nth-child(3) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="left-section">
            <div class="branding">
                <div class="logo">
                    <img src="{{ asset('images/Pilihanku3.png') }}" alt="Logo Pilihanku" style="width: 100%; height: 100%; object-fit: contain; border-radius: 50%;">
                </div>
            </div>
            <h1 class="login-title">Pilihanku</h1>
            <p class="login-subtitle">Sistem Pendukung Keputusan untuk Seleksi siswa</p>

            <div class="features">
                <div class="feature">
                    <i class="fas fa-chart-line"></i>
                    <h6>Analisis SPK</h6>
                    <p>Multi-kriteria decision making</p>
                </div>
                <div class="feature">
                    <i class="fas fa-users"></i>
                    <h6>Manajemen Data</h6>
                    <p>Kelola siswa & program studi</p>
                </div>
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <h6>Keamanan</h6>
                    <p>Sistem login yang aman</p>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="form-container">
                <h2 class="form-title">Selamat Datang</h2>
                <p class="form-subtitle">Silakan login untuk mengakses sistem</p>

                @if($errors->any())
                    <div class="alert alert-danger animate-fadeInUp">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <strong>Login Gagal!</strong>
                                <div class="mt-1">{{ $errors->first() }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger animate-fadeInUp">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-times-circle me-2"></i>
                            <div>
                                <strong>Error!</strong>
                                <div class="mt-1">{{ session('error') }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success animate-fadeInUp">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>
                                <strong>Berhasil!</strong>
                                <div class="mt-1">{{ session('success') }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="text-center mb-3">
                        <p class="text-muted mb-1">Belum punya akun?
                            <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold">
                                Daftar Sekarang
                            </a>
                        </p>
                        <p class="mb-0">
                            <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: var(--primary-color); font-weight:600;">
                                Lupa Password?
                            </a>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>
                        <input type="email"
                               class="form-control"
                               id="email"
                               name="email"
                               placeholder="Masukkan email Anda"
                               value="{{ old('email') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan password Anda"
                                   required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Login
                    </button>
                </form>

                <!-- Divider -->
                <div class="divider-container my-4">
                    <div class="divider-line"></div>
                    <span class="divider-text">atau</span>
                    <div class="divider-line"></div>
                </div>

                <!-- Google Login Button -->
                <a href="{{ route('google.login') }}" class="btn btn-google">
                    <svg class="google-icon" viewBox="0 0 24 24" width="20" height="20">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Login dengan Google
                </a>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Pilihanku v1.0
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const btn = document.querySelector('.btn-login');

            // Safe submit handler: run on form submit so we don't interfere with normal submission
            if (form && btn) {
                form.addEventListener('submit', function() {
                    // show spinner and disable button; do not re-enable here — let page navigation handle it
                    btn.__originalHTML = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                    btn.disabled = true;
                });

                // ripple effect on click (non-blocking)
                btn.addEventListener('click', function(e) {
                    try {
                        const ripple = document.createElement('span');
                        const rect = this.getBoundingClientRect();
                        const size = Math.max(rect.width, rect.height);
                        const clientX = (e && e.clientX) || (rect.left + rect.width / 2);
                        const clientY = (e && e.clientY) || (rect.top + rect.height / 2);
                        const x = clientX - rect.left - size / 2;
                        const y = clientY - rect.top - size / 2;

                        ripple.style.width = ripple.style.height = size + 'px';
                        ripple.style.left = x + 'px';
                        ripple.style.top = y + 'px';
                        ripple.classList.add('ripple');
                        this.appendChild(ripple);
                        setTimeout(() => ripple.remove(), 600);
                    } catch (err) {
                        // swallow any ripple-related errors so they don't block submission
                        console.debug('ripple error', err);
                    }
                });
            }

            // Auto-hide alerts after 5 seconds
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });

            // Add shake animation to form on error (safe guard in case elements are missing)
            document.querySelectorAll('.alert-danger').forEach(alert => {
                alert.addEventListener('animationend', function() {
                    const container = document.querySelector('.form-container');
                    if (container) container.style.animation = 'shake 0.5s ease-in-out';
                });
            });

            // Add focus animations to form inputs
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('focus', function() {
                    if (this.parentElement) this.parentElement.style.transform = 'translateY(-2px)';
                });

                input.addEventListener('blur', function() {
                    if (this.parentElement) this.parentElement.style.transform = 'translateY(0)';
                });
            });

            // Add CSS for ripple effect
            const style = document.createElement('style');
            style.textContent = `
                .btn-login { position: relative; overflow: hidden; }
                .ripple { position: absolute; border-radius: 50%; background: rgba(255,255,255,0.6); transform: scale(0); animation: ripple-animation 0.6s linear; pointer-events: none; }
                @keyframes ripple-animation { to { transform: scale(4); opacity: 0; } }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Pilihanku</title>
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
            padding: 2rem 1rem;
        }

        .register-container {
            background: white;
            border-radius: 1.5rem;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            max-width: 1100px;
            width: 100%;
            display: flex;
        }

        .left-section {
            flex: 0 0 380px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 3rem 2rem;
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

        .register-right {
            flex: 1;
            padding: 3rem;
            overflow-y: auto;
            max-height: 90vh;
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

        .register-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }

        .register-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
            line-height: 1.6;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
        }

        .info-box h6 {
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        .info-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .info-box li {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .info-box li::before {
            content: '✓';
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .form-container {
            max-width: 100%;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: var(--text-secondary);
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: var(--light-color);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background: white;
        }

        .form-control::placeholder {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .form-text {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-back {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-primary);
            width: 100%;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-back:hover {
            background: var(--light-color);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        /* Google Signup Button */
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

        .alert ul {
            margin-bottom: 0;
            padding-left: 1.5rem;
        }

        .alert li {
            margin-bottom: 0.25rem;
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        @media (max-width: 992px) {
            .register-container {
                flex-direction: column;
            }

            .left-section {
                flex: 0 0 auto;
                padding: 2rem;
                min-height: auto;
            }

            .register-right {
                padding: 2rem;
                max-height: none;
            }

            .register-title {
                font-size: 2rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .info-box {
                margin-top: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 1rem 0.5rem;
            }

            .register-container {
                border-radius: 1rem;
            }

            .left-section {
                padding: 1.5rem;
            }

            .register-right {
                padding: 1.5rem;
            }

            .register-title {
                font-size: 1.75rem;
            }

            .form-title {
                font-size: 1.35rem;
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

        .register-right > * {
            animation: fadeInUp 0.6s ease-out;
        }

        .register-right > *:nth-child(1) { animation-delay: 0.2s; }
        .register-right > *:nth-child(2) { animation-delay: 0.3s; }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Left Side - Branding -->
        <div class="left-section">
            <div class="branding">
                <div class="logo">
                    <img src="<?php echo e(asset('images/Pilihanku3.png')); ?>" alt="Logo Pilihanku" style="width: 100%; height: 100%; object-fit: contain; border-radius: 50%;">
                </div>
            </div>
            <h1 class="register-title">Pilihanku</h1>
            <p class="register-subtitle">Daftar untuk mengakses sistem SPK dan kelola data akademik Anda</p>

            <div class="info-box">
                <h6>Keuntungan Bergabung:</h6>
                <ul>
                    <li>Analisis SPK otomatis</li>
                    <li>Akses data program studi</li>
                    <li>Riwayat analisis lengkap</li>
                    <li>Export hasil ke PDF</li>
                    <li>Dashboard personal</li>
                </ul>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="register-right">
            <div class="form-container">
                <div class="form-header">
                    <h2 class="form-title">Registrasi siswa</h2>
                    <p class="form-subtitle">Daftar untuk mengakses sistem SPK</p>
                </div>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger animate-fadeInUp">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                            <div class="flex-grow-1">
                                <strong>Terjadi Kesalahan!</strong>
                                <ul class="mt-2">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('register')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user"></i>
                                    Nama Lengkap
                                </label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name')); ?>" required autofocus placeholder="Masukkan nama lengkap">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nisn" class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    NISN
                                </label>
                                <input type="text" class="form-control" id="nisn" name="nisn" value="<?php echo e(old('nisn')); ?>" required placeholder="Nomor Induk Siswa Nasional">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="email@example.com">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jurusan_sma" class="form-label">
                                    <i class="fas fa-graduation-cap"></i>
                                    Jurusan SMA/SMK
                                </label>
                                <input type="text" class="form-control" id="jurusan_sma" name="jurusan_sma" value="<?php echo e(old('jurusan_sma')); ?>" required placeholder="Contoh: IPA, IPS, Teknik Komputer">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tahun_lulus" class="form-label">
                                    <i class="fas fa-calendar"></i>
                                    Tahun Lulus
                                </label>
                                <input type="text" class="form-control" id="tahun_lulus" name="tahun_lulus" value="<?php echo e(old('tahun_lulus')); ?>" required placeholder="Contoh: 2023">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="asal_sekolah" class="form-label">
                            <i class="fas fa-school"></i>
                            Asal Sekolah
                        </label>
                        <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah" value="<?php echo e(old('asal_sekolah')); ?>" required placeholder="Nama sekolah lengkap">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Minimal 8 karakter">
                                <div class="form-text">Minimal 8 karakter</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Konfirmasi Password
                                </label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn-register">
                            <i class="fas fa-user-plus me-2"></i>
                            Daftar Sekarang
                        </button>
                        <a href="<?php echo e(route('login')); ?>" class="btn-back">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Login
                        </a>
                    </div>
                </form>

                <!-- Divider -->
                <div class="divider-container my-4">
                    <div class="divider-line"></div>
                    <span class="divider-text">atau</span>
                    <div class="divider-line"></div>
                </div>

                <!-- Google Signup Button -->
                <a href="<?php echo e(route('google.login')); ?>" class="btn btn-google">
                    <svg class="google-icon" viewBox="0 0 24 24" width="20" height="20">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Daftar dengan Google
                </a>

                <div class="footer-text">
                    &copy; <?php echo e(date('Y')); ?> Pilihanku v1.0
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\laragon\tugas_akhir\resources\views/auth/register.blade.php ENDPATH**/ ?>
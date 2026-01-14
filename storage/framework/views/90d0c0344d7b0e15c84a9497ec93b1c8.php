<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Pilihanku'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Pilihanku3.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
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
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-primary);
            overflow-x: hidden; /* prevent accidental horizontal scroll */
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            height: 100vh;
            position: fixed; /* sidebar always fixed */
            top: 0;
            left: 0;
            overflow-y: hidden;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            width: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0; /* fixed at top */
        }

        .sidebar-brand h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-nav {
            padding: 0 1rem;
            overflow-y: auto; /* only nav menu scrollable */
            flex: 1; /* take available space between header and user panel */
            margin: 1rem 0;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        /* Sidebar nav links */
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white !important;
        }

        .sidebar .nav-link i {
            font-size: 1.25rem;
            width: 20px;
            text-align: center;
        }

        .main-content {
            background-color: var(--secondary-color);
            min-height: 100vh;
            padding: 2rem;
            margin-left: var(--sidebar-width); /* offset for fixed sidebar */
            width: calc(100% - var(--sidebar-width)); /* avoid horizontal overflow */
        }

        .content-header {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-color);
        }

        .content-header h1 {
            color: var(--text-primary);
            font-weight: 700;
            margin: 0;
            font-size: 1.875rem;
        }

        .content-header p {
            color: var(--text-secondary);
            margin: 0.5rem 0 0 0;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            border-radius: 1rem 1rem 0 0 !important;
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--accent-color) 0%, #059669 100%);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            border: none;
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
            border: none;
        }

        .table {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--light-color);
            border: none;
            font-weight: 600;
            color: var(--text-primary);
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            border-color: var(--border-color);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: var(--light-color);
        }

        .modal-content {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--shadow-xl);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            border-radius: 1rem 1rem 0 0;
            background: var(--light-color);
        }

        .modal-title {
            font-weight: 700;
            color: var(--text-primary);
        }

        .form-control, .form-select {
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .alert {
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .stats-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stats-card.primary {
            border-left-color: var(--primary-color);
        }

        .stats-card.success {
            border-left-color: var(--accent-color);
        }

        .stats-card.info {
            border-left-color: var(--info-color);
        }

        .stats-card.warning {
            border-left-color: var(--warning-color);
        }

        .stats-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stats-icon.primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .stats-icon.success {
            background: linear-gradient(135deg, var(--accent-color) 0%, #059669 100%);
        }

        .stats-icon.info {
            background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
        }

        .stats-icon.warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0.5rem 0;
        }

        .stats-label {
            color: var(--text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }

        .user-dropdown {
            position: static; /* take part in flex layout */
            margin: 0 1rem 1rem 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .user-info:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
        }

        .user-panel {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0; /* fixed at bottom */
        }

        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .user-details h6 {
            margin: 0;
            font-weight: 600;
        }

        .user-details small {
            opacity: 0.8;
        }

        /* Topbar (account dropdown on top-right) */
        /* Removed - using sidebar-only layout */

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: var(--sidebar-width);
                height: 100vh;
                z-index: 1040;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0; /* no offset on mobile */
                width: 100%;
                padding: 1rem;
            }

            .content-header {
                padding: 1rem;
            }
        }

        .sidebar-toggle {
            display: none; /* hidden on desktop */
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1030;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0.5rem;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: inline-flex; /* show on mobile as fixed button */
            }
        }

        .avatar-sm {
            width: 2.5rem;
            height: 2.5rem;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .btn-group .btn {
            border-radius: 0.375rem;
            margin: 0 0.125rem;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.05);
        }

        .btn-outline-primary:hover,
        .btn-outline-info:hover,
        .btn-outline-danger:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
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

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translateY(0);
            }
            40%, 43% {
                transform: translateY(-10px);
            }
            70% {
                transform: translateY(-5px);
            }
            90% {
                transform: translateY(-2px);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-fadeInLeft {
            animation: fadeInLeft 0.6s ease-out;
        }

        .animate-fadeInRight {
            animation: fadeInRight 0.6s ease-out;
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        .animate-bounce {
            animation: bounce 1s infinite;
        }

        /* Page transitions */
        .page-enter {
            opacity: 0;
            transform: translateY(20px);
        }

        .page-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        /* Loading states */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }

        /* Hover effects */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        /* Button animations */
        .btn-animated {
            position: relative;
            overflow: hidden;
        }

        .btn-animated::before {
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

        .btn-animated:active::before {
            width: 300px;
            height: 300px;
        }

        /* Table row animations */
        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.05);
            transform: scale(1.01);
        }

        /* Modal animations */
        .modal.fade .modal-dialog {
            transform: translate(0, -50px);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: translate(0, 0);
        }

        /* Progress bar animations */
        .progress-bar {
            transition: width 0.6s ease;
        }

        /* Stats card animations */
        .stats-card {
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stats-icon {
            transition: all 0.3s ease;
        }

        /* Form animations */
        .form-control:focus {
            transform: scale(1.02);
            transition: all 0.3s ease;
        }

        /* Alert animations */
        .alert {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Badge animations */
        .badge {
            transition: all 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.1);
        }

        /* Sidebar animations */
        .sidebar .nav-link {
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            transform: translateX(5px);
        }

        /* Content animations */
        .content-header {
            animation: fadeInUp 0.6s ease-out;
        }

        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle menu">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <?php if(!Request::is('register')): ?>
            <div class="col-auto sidebar" id="sidebar">
                <div class="d-flex flex-column h-100">
                    <div class="sidebar-brand">
                        <h4>
                            <span style="display:inline-block;width:64px;height:64px;vertical-align:middle;margin-right:12px;">
                                <img src="<?php echo e(asset('images/Pilihanku3.png')); ?>" alt="Logo Pilihanku" style="width:100%;height:100%;object-fit:contain;border-radius:50%;">
                            </span>
                            Pilihanku
                        </h4>
                    </div>
                    <div class="sidebar-nav">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                                    <i class="fas fa-home"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <?php if(Auth::check() && Auth::user()->role === 'admin'): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('prodi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('prodi.*') ? 'active' : ''); ?>">
                                    <i class="fas fa-book"></i>
                                    <span>Program Studi</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('siswa.index')); ?>" class="nav-link <?php echo e(request()->routeIs('siswa.*') && !request()->routeIs('siswa.dashboard') ? 'active' : ''); ?>">
                                    <i class="fas fa-user-graduate"></i>
                                    <span>Siswa</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('reports.prodi')); ?>" class="nav-link <?php echo e(request()->routeIs('reports.prodi') ? 'active' : ''); ?>">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>Reports Prodi</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('reports.spk-analysis')); ?>" class="nav-link <?php echo e(request()->routeIs('reports.spk-analysis*') ? 'active' : ''); ?>">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Reports Analisis SPK</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(Auth::check() && in_array(Auth::user()->role, ['siswa', 'mahasiswa'])): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('prodi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('prodi.*') ? 'active' : ''); ?>">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span>Program Studi</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <!-- SPK Analysis Menu (Admin) -->
                            <?php if(Auth::check() && Auth::user()->role === 'admin'): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('spk.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('spk.*') || request()->routeIs('prodi.analysis') || request()->routeIs('prodi.list') ? 'active' : ''); ?>">
                                    <i class="fas fa-chart-line"></i>
                                    <span>SPK Analysis</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <!-- SPK Analysis Menu (Siswa/Mahasiswa) -->
                            <?php if(Auth::check() && in_array(Auth::user()->role, ['siswa', 'mahasiswa'])): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('siswa-spk.form')); ?>" class="nav-link <?php echo e(request()->routeIs('siswa-spk.*') ? 'active' : ''); ?>">
                                    <i class="fas fa-chart-line"></i>
                                    <span>SPK Analysis</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(Auth::check() && in_array(Auth::user()->role, ['siswa', 'mahasiswa'])): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('spk.history')); ?>" class="nav-link <?php echo e(request()->routeIs('spk.history') ? 'active' : ''); ?>">
                                    <i class="fas fa-history"></i>
                                    <span>Riwayat Analisis</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(Auth::check() && Auth::user()->role === 'admin'): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.announcement.form')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.announcement.*') ? 'active' : ''); ?>">
                                    <i class="fas fa-bullhorn"></i>
                                    <span>Pengumuman</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.deletion-requests')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.deletion-requests*') ? 'active' : ''); ?>">
                                    <i class="fas fa-user-times"></i>
                                    <span>Hapus Akun</span>
                                    <?php ($pendingRequests = \App\Models\AccountDeletionRequest::where('status', 'pending')->count()); ?>
                                    <?php if($pendingRequests > 0): ?>
                                        <span class="badge bg-danger ms-auto"><?php echo e($pendingRequests); ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <!-- User Panel - Fixed at bottom -->
                    <?php if(Auth::check() && !Request::is('register')): ?>
                    <div class="user-panel">
                        <?php ($notifUnread = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count()); ?>
                        <div class="mb-3 text-center">
                            <a href="<?php echo e(route('notifications.index')); ?>" class="btn btn-sm btn-outline-light w-100 position-relative">
                                <i class="fas fa-bell"></i> Notifikasi
                                <?php if($notifUnread > 0): ?>
                                    <span id="notifBadge" class="badge bg-danger position-absolute rounded-pill px-2" style="top:4px;right:8px;"><?php echo e($notifUnread); ?></span>
                                <?php else: ?>
                                    <span id="notifBadge" class="badge bg-danger position-absolute rounded-pill px-2" style="top:4px;right:8px;display:none;">0</span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="user-info dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar">
                                    <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                                </div>
                                <div class="user-details">
                                    <h6><?php echo e(Auth::user()->name); ?></h6>
                                    <small><?php echo e(ucfirst(Auth::user()->role)); ?></small>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(Auth::user()->issiswa() ? route('siswa.profile') : route('admin.profile')); ?>">
                                        <i class="fas fa-user me-2"></i>Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(Auth::user()->issiswa() ? route('siswa.settings') : route('admin.settings')); ?>">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
            <?php endif; ?>
            
            <!-- Main Content -->
            <div class="main-content">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('alert-success') || alert.classList.contains('alert-info')) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }
            });
        }, 5000);

        // Add loading animation to buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function() {
                if (this.type === 'submit' || this.classList.contains('btn-primary')) {
                    this.classList.add('loading');
                    setTimeout(() => {
                        this.classList.remove('loading');
                    }, 2000);
                }
            });
        });

        // Add hover effects to cards
        document.querySelectorAll('.card').forEach(card => {
            card.classList.add('card-hover');
        });

        // Add animation classes to buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.classList.add('btn-animated');
        });

        // Animate stats cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.6s ease-out';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.stats-card, .card').forEach(card => {
            observer.observe(card);
        });

        // Add ripple effect to buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add CSS for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            .btn {
                position: relative;
                overflow: hidden;
            }
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add typing animation to text elements
        function typeWriter(element, text, speed = 100) {
            let i = 0;
            element.innerHTML = '';
            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);
                }
            }
            type();
        }

        // Initialize page animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate page content
            const contentElements = document.querySelectorAll('.content-header, .card, .stats-card');
            contentElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    element.style.transition = 'all 0.6s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Poll unread notifications count every 20s
            const badge = document.getElementById('notifBadge');
            const poll = () => {
                fetch("<?php echo e(route('notifications.unread')); ?>", {headers: {'X-Requested-With':'XMLHttpRequest'}})
                    .then(r => r.json())
                    .then(data => {
                        if (!badge) return;
                        const c = data.count ?? 0;
                        if (c > 0) {
                            badge.style.display = 'inline-block';
                            badge.textContent = c;
                        } else {
                            badge.style.display = 'none';
                        }
                    })
                    .catch(()=>{});
            };
            poll(); // initial
            setInterval(poll, 12000); // 12s polling for more responsive updates
        });
    </script>
</body>
</html>

<?php /**PATH C:\laragon\tugas_akhir\resources\views/layouts/app.blade.php ENDPATH**/ ?>
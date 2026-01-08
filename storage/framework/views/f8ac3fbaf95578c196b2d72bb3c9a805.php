

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <h1>Dashboard Pilihanku</h1>
    <p>Selamat datang di sistem Pendukung Keputusan untuk Seleksi siswa</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stats-card primary">
            <div class="d-flex align-items-center">
                <div class="stats-icon primary me-3">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <div class="stats-number"><?php echo e(App\Models\Siswa::count()); ?></div>
                    <div class="stats-label">Total Siswa</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stats-card success">
            <div class="d-flex align-items-center">
                <div class="stats-icon success me-3">
                    <i class="fas fa-book"></i>
                </div>
                <div>
                    <div class="stats-number"><?php echo e(App\Models\Prodi::count()); ?></div>
                    <div class="stats-label">Program Studi</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stats-card info">
            <div class="d-flex align-items-center">
                <div class="stats-icon info me-3">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <div class="stats-number"><?php echo e(App\Models\Siswa::count()); ?></div>
                    <div class="stats-label">Total Siswa</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stats-card warning">
            <div class="d-flex align-items-center">
                <div class="stats-icon warning me-3">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <div class="stats-number">0</div>
                    <div class="stats-label">Analisis SPK</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Grafik Distribusi Siswa per Program Studi
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Grafik akan ditampilkan di sini</h6>
                    <p class="text-muted">Data visualisasi distribusi siswa berdasarkan program studi</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tasks me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('siswa.index')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Siswa
                    </a>
                    <a href="<?php echo e(route('prodi.index')); ?>" class="btn btn-success">
                        <i class="fas fa-book me-2"></i>
                        Kelola Program Studi
                    </a>
                    <a href="<?php echo e(route('spk.dashboard')); ?>" class="btn btn-info">
                        <i class="fas fa-chart-line me-2"></i>
                        SPK Analysis
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Sistem SPK
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Tentang Pilihanku</h6>
                        <p class="text-muted">
                            Pilihanku adalah Sistem Pendukung Keputusan (SPK) yang dirancang untuk membantu dalam proses seleksi 
                            dan evaluasi siswa berdasarkan berbagai kriteria yang telah ditentukan.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i> Multi-kriteria decision making</li>
                            <li><i class="fas fa-check text-success me-2"></i> Analisis berbobot</li>
                            <li><i class="fas fa-check text-success me-2"></i> Ranking otomatis</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Fitur Utama</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-database text-primary mb-2"></i>
                                    <h6 class="mb-1">Data Management</h6>
                                    <small class="text-muted">Kelola data siswa dan prodi</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-calculator text-success mb-2"></i>
                                    <h6 class="mb-1">SPK Engine</h6>
                                    <small class="text-muted">Algoritma perhitungan SPK</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-chart-bar text-info mb-2"></i>
                                    <h6 class="mb-1">Visualization</h6>
                                    <small class="text-muted">Grafik dan laporan</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-download text-warning mb-2"></i>
                                    <h6 class="mb-1">Export Data</h6>
                                    <small class="text-muted">Ekspor hasil analisis</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tugas_akhir\resources\views/dashboard.blade.php ENDPATH**/ ?>
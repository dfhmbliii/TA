<?php $__env->startSection('title', 'Analisis Per Program Studi'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Analisis Per Program Studi</h1>
            <p>Kelola matriks perbandingan mata pelajaran untuk setiap program studi</p>
        </div>
        <a href="<?php echo e(route('spk.dashboard')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali ke Dashboard
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header" style="background: linear-gradient(135deg, <?php echo e(getFakultasColor($prodi->nama_fakultas)); ?>, <?php echo e(adjustColor(getFakultasColor($prodi->nama_fakultas))); ?>);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-graduation-cap me-2"></i>
                    <?php echo e($prodi->nama_prodi); ?>

                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    <i class="fas fa-university me-2"></i>
                    <?php echo e($prodi->nama_fakultas); ?>

                </p>
                <p class="text-muted mb-3">
                    <i class="fas fa-code me-2"></i>
                    <span class="badge bg-primary"><?php echo e($prodi->kode_prodi); ?></span>
                </p>

                <?php if($prodi->alternatives->isEmpty()): ?>
                    <div class="alert alert-warning mb-3">
                        <small><i class="fas fa-exclamation-triangle me-1"></i> Belum ada alternatif</small>
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2"><strong>Mata Pelajaran:</strong></small>
                        <div class="d-flex flex-wrap gap-1">
                            <?php $__currentLoopData = $prodi->alternatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-secondary"><?php echo e($alt->nama_alternatif); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar" role="progressbar" 
                            style="width: <?php echo e($prodi->alternatives->sum('bobot') * 100); ?>%">
                            <?php echo e(number_format($prodi->alternatives->sum('bobot'), 2)); ?>

                        </div>
                    </div>
                <?php endif; ?>

                <a href="<?php echo e(route('prodi.analysis', $prodi->id)); ?>" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-chart-line me-2"></i>
                    Kelola Matriks
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php
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
?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\tugas_akhir\resources\views/spk/prodi-list.blade.php ENDPATH**/ ?>
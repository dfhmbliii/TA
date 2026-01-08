

<?php $__env->startSection('title', 'Hasil Rekomendasi Program Studi'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <h1><i class="fas fa-chart-bar me-2"></i>Hasil Analisis SPK</h1>
    <p>Rekomendasi Program Studi berdasarkan minat, bakat, dan kemampuan Anda</p>
</div>

<!-- Summary Card -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-3"><i class="fas fa-trophy text-warning me-2"></i>Rekomendasi Terbaik</h5>
                        <h3 class="text-primary mb-2"><?php echo e($prodiScores[0]['prodi']->nama_prodi ?? 'N/A'); ?></h3>
                        <p class="text-muted mb-3"><?php echo e($prodiScores[0]['prodi']->nama_fakultas ?? ''); ?></p>
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <h2 class="mb-0 text-success"><?php echo e(number_format($prodiScores[0]['score'], 2)); ?></h2>
                                <small class="text-muted">Skor Total</small>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <h5 class="mb-0">
                                    <span class="badge bg-<?php echo e($prodiScores[0]['score'] >= 80 ? 'success' : 
                                        ($prodiScores[0]['score'] >= 70 ? 'primary' : 
                                        ($prodiScores[0]['score'] >= 60 ? 'info' : 'warning'))); ?> px-3 py-2">
                                        <?php echo e($prodiScores[0]['score'] >= 80 ? 'Sangat Sesuai' : 
                                            ($prodiScores[0]['score'] >= 70 ? 'Sesuai' : 
                                            ($prodiScores[0]['score'] >= 60 ? 'Cukup Sesuai' : 'Kurang Sesuai'))); ?>

                                    </span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-graduation-cap text-primary" style="font-size: 6rem; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Input Summary -->
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card shadow border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Data Input Anda</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <small class="text-muted d-block">Minat</small>
                            <span class="badge bg-danger"><?php echo e(ucwords(str_replace('_', ' ', $data['minat']))); ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <small class="text-muted d-block">Bakat</small>
                            <span class="badge bg-warning text-dark"><?php echo e(ucfirst(str_replace('_', ' ', $data['bakat']))); ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <small class="text-muted d-block">Prospek Karir</small>
                            <span class="badge bg-success"><?php echo e(ucwords(str_replace('_', ' ', $data['prospek_karir']))); ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <small class="text-muted d-block">Nilai Rata-rata</small>
                            <span class="badge bg-info"><?php echo e(number_format(array_sum($data['nilai_mapel']) / count($data['nilai_mapel']), 2)); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ranking Table -->
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-list-ol me-2"></i>
                        Ranking Program Studi (Top <?php echo e(count($prodiScores)); ?>)
                    </h6>
                    <a href="<?php echo e(route('siswa-spk.form')); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-redo me-1"></i>Analisis Ulang
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="5%">Rank</th>
                                <th width="30%">Program Studi</th>
                                <th width="20%">Fakultas</th>
                                <th class="text-center" width="10%">Skor Total</th>
                                <th class="text-center" width="15%">Kategori</th>
                                <th class="text-center" width="10%">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $prodiScores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center">
                                    <?php if($index === 0): ?>
                                        <i class="fas fa-trophy text-warning fa-lg"></i>
                                    <?php elseif($index === 1): ?>
                                        <i class="fas fa-medal text-secondary fa-lg"></i>
                                    <?php elseif($index === 2): ?>
                                        <i class="fas fa-award text-bronze fa-lg" style="color: #CD7F32;"></i>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark"><?php echo e($index + 1); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center me-2">
                                            <?php echo e(substr($item['prodi']->nama_prodi, 0, 2)); ?>

                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo e($item['prodi']->nama_prodi); ?></div>
                                            <small class="text-muted"><?php echo e($item['prodi']->kode_prodi ?? ''); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                        $fakultas = $item['prodi']->nama_fakultas;
                                        $colorClass = 'secondary';
                                        if (stripos($fakultas, 'Rekayasa Industri') !== false) $colorClass = 'success';
                                        elseif (stripos($fakultas, 'Teknik Elektro') !== false) $colorClass = 'info';
                                        elseif (stripos($fakultas, 'Informatika') !== false) $colorClass = 'warning';
                                        elseif (stripos($fakultas, 'Industri Kreatif') !== false) $colorClass = 'danger';
                                    ?>
                                    <span class="badge bg-<?php echo e($colorClass); ?>"><?php echo e($fakultas); ?></span>
                                </td>
                                <td class="text-center">
                                    <h5 class="mb-0 text-<?php echo e($item['score'] >= 80 ? 'success' : ($item['score'] >= 70 ? 'primary' : 'secondary')); ?>">
                                        <?php echo e(number_format($item['score'], 2)); ?>

                                    </h5>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-<?php echo e($item['score'] >= 80 ? 'success' : 
                                        ($item['score'] >= 70 ? 'primary' : 
                                        ($item['score'] >= 60 ? 'info' : 'warning'))); ?>">
                                        <?php echo e($item['score'] >= 80 ? 'Sangat Sesuai' : 
                                            ($item['score'] >= 70 ? 'Sesuai' : 
                                            ($item['score'] >= 60 ? 'Cukup Sesuai' : 'Kurang Sesuai'))); ?>

                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#detail<?php echo e($index); ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="detail<?php echo e($index); ?>">
                                <td colspan="6" class="bg-light">
                                    <div class="p-3">
                                        <h6 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Detail Skor per Kriteria</h6>
                                        <div class="row">
                                            <?php $__currentLoopData = $item['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-3 mb-3">
                                                <div class="card border-0 bg-white shadow-sm">
                                                    <div class="card-body p-3">
                                                        <small class="text-muted d-block mb-1"><?php echo e(ucwords(str_replace('_', ' ', $key))); ?></small>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h5 class="mb-0"><?php echo e(number_format($detail['weighted'], 2)); ?></h5>
                                                            <span class="badge bg-primary"><?php echo e(number_format($detail['weight'] * 100, 0)); ?>%</span>
                                                        </div>
                                                        <div class="progress mt-2" style="height: 4px;">
                                                            <div class="progress-bar" role="progressbar" style="width: <?php echo e($detail['score']); ?>%"></div>
                                                        </div>
                                                        <small class="text-muted">Nilai: <?php echo e(number_format($detail['score'], 2)); ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="d-flex gap-2 justify-content-center">
            <a href="<?php echo e(route('siswa.dashboard')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-home me-2"></i>Kembali ke Dashboard
            </a>
            <a href="<?php echo e(route('prodi.show', $prodiScores[0]['prodi']->id)); ?>" class="btn btn-primary">
                <i class="fas fa-info-circle me-2"></i>Lihat Detail Program Studi
            </a>
            <button class="btn btn-success" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Cetak Hasil
            </button>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        font-size: 0.875rem;
        font-weight: bold;
    }
    
    .vr {
        width: 2px;
        background-color: #dee2e6;
        opacity: 1;
        align-self: stretch;
    }
    
    @media print {
        .btn, .content-header p, .card-header .btn {
            display: none !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tugas_akhir\resources\views/spk/siswa-result.blade.php ENDPATH**/ ?>
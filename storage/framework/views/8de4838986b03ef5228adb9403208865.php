<?php $__env->startSection('title','Notifikasi'); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header d-flex justify-content-between align-items-center">
    <div>
        <h1>Notifikasi</h1>
        <p>Semua notifikasi terbaru Anda</p>
    </div>
    <div class="d-flex gap-2">
        <form action="<?php echo e(route('notifications.markAll')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca</button>
        </form>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('notifications.index')); ?>" id="filterForm">
            <div class="row g-3">
                <!-- Filter by Type -->
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="fas fa-tag me-2"></i>Tipe Notifikasi</label>
                    <select name="type" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Tipe</option>
                        <option value="spk_result" <?php echo e(request('type') == 'spk_result' ? 'selected' : ''); ?>>SPK Result</option>
                        <option value="system" <?php echo e(request('type') == 'system' ? 'selected' : ''); ?>>System</option>
                        <option value="account_deletion" <?php echo e(request('type') == 'account_deletion' ? 'selected' : ''); ?>>Account Deletion</option>
                        <option value="announcement" <?php echo e(request('type') == 'announcement' ? 'selected' : ''); ?>>Announcement</option>
                    </select>
                </div>

                <!-- Filter by Status -->
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="fas fa-check-circle me-2"></i>Status</label>
                    <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Status</option>
                        <option value="unread" <?php echo e(request('status') == 'unread' ? 'selected' : ''); ?>>Belum Dibaca</option>
                        <option value="read" <?php echo e(request('status') == 'read' ? 'selected' : ''); ?>>Sudah Dibaca</option>
                    </select>
                </div>

                <!-- Filter by Date -->
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="fas fa-calendar me-2"></i>Periode</label>
                    <select name="date" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Waktu</option>
                        <option value="today" <?php echo e(request('date') == 'today' ? 'selected' : ''); ?>>Hari Ini</option>
                        <option value="week" <?php echo e(request('date') == 'week' ? 'selected' : ''); ?>>Minggu Ini</option>
                        <option value="month" <?php echo e(request('date') == 'month' ? 'selected' : ''); ?>>Bulan Ini</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters & Reset -->
            <?php if(request()->hasAny(['type', 'status', 'date'])): ?>
                <div class="mt-3 d-flex align-items-center gap-2">
                    <span class="badge bg-secondary">
                        <i class="fas fa-filter me-1"></i>Filter Aktif:
                    </span>
                    <?php if(request('type')): ?>
                        <span class="badge bg-primary">
                            Tipe: <?php echo e(ucfirst(str_replace('_', ' ', request('type')))); ?>

                        </span>
                    <?php endif; ?>
                    <?php if(request('status')): ?>
                        <span class="badge bg-info">
                            Status: <?php echo e(request('status') == 'unread' ? 'Belum Dibaca' : 'Sudah Dibaca'); ?>

                        </span>
                    <?php endif; ?>
                    <?php if(request('date')): ?>
                        <span class="badge bg-warning text-dark">
                            Periode: <?php echo e(ucfirst(request('date'))); ?>

                        </span>
                    <?php endif; ?>
                    <a href="<?php echo e(route('notifications.index')); ?>" class="btn btn-sm btn-outline-danger ms-2">
                        <i class="fas fa-times me-1"></i>Reset Filter
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-bell me-2"></i> 
            <?php if(request()->hasAny(['type', 'status', 'date'])): ?>
                Hasil Filter: <?php echo e($notifications->total()); ?> notifikasi
                <span class="text-muted small">(<?php echo e($unreadCount); ?> total belum dibaca)</span>
            <?php else: ?>
                Notifikasi (<?php echo e($unreadCount); ?> belum dibaca)
            <?php endif; ?>
        </span>
    </div>
    <div class="list-group list-group-flush">
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="list-group-item <?php echo e($n->is_read ? '' : 'bg-light'); ?>">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <h6 class="mb-0"><?php echo e($n->title); ?></h6>
                            <?php
                                $typeConfig = [
                                    'spk_result' => ['label' => 'SPK Result', 'bg' => 'success', 'icon' => 'chart-line'],
                                    'system' => ['label' => 'System', 'bg' => 'info', 'icon' => 'cog'],
                                    'account_deletion' => ['label' => 'Account', 'bg' => 'danger', 'icon' => 'user-times'],
                                    'announcement' => ['label' => 'Announcement', 'bg' => 'warning', 'icon' => 'bullhorn'],
                                ];
                                $config = $typeConfig[$n->type] ?? ['label' => ucfirst($n->type), 'bg' => 'secondary', 'icon' => 'bell'];
                            ?>
                            <span class="badge bg-<?php echo e($config['bg']); ?>">
                                <i class="fas fa-<?php echo e($config['icon']); ?> me-1"></i><?php echo e($config['label']); ?>

                            </span>
                            <?php if(!$n->is_read): ?>
                                <span class="badge bg-primary">Baru</span>
                            <?php endif; ?>
                        </div>
                        <p class="mb-1 small text-muted"><?php echo e($n->message); ?></p>
                        <small class="text-muted"><i class="far fa-clock me-1"></i><?php echo e($n->created_at->diffForHumans()); ?></small>
                    </div>
                    <div class="text-end ms-3">
                        <?php if(!$n->is_read): ?>
                            <form action="<?php echo e(route('notifications.mark',$n->id)); ?>" method="POST" class="mb-2">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm btn-primary text-nowrap"><i class="fas fa-eye me-1"></i> Tandai Dibaca</button>
                            </form>
                        <?php endif; ?>
                        <?php if($n->type === 'spk_result' && isset($n->data['result_id'])): ?>
                            <a href="<?php echo e(url('/riwayat')); ?>" class="btn btn-sm btn-outline-secondary text-nowrap"><i class="fas fa-chart-line me-1"></i> Lihat Hasil</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="list-group-item text-center py-5">
                <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                <p class="mb-0 text-muted">
                    <?php if(request()->hasAny(['type', 'status', 'date'])): ?>
                        Tidak ada notifikasi yang sesuai dengan filter.
                    <?php else: ?>
                        Belum ada notifikasi.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
    <div class="card-footer">
        <?php echo e($notifications->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\tugas_akhir\resources\views/notifications/index.blade.php ENDPATH**/ ?>
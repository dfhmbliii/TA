<?php $__env->startSection('title', 'SPK Analysis'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="d-flex align-items-center gap-2">
                <span>SPK Analysis - Analisis Kriteria</span>
                <?php
                    $cr = session('ahp_consistency_ratio');
                ?>
                <?php if(!is_null($cr)): ?>
                    <?php if($cr < 0.1): ?>
                        <span class="badge bg-success">Konsisten (CR <?php echo e(number_format($cr, 3)); ?>)</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Tidak Konsisten (CR <?php echo e(number_format($cr, 3)); ?>)</span>
                    <?php endif; ?>
                <?php endif; ?>
            </h1>
            <p>Tentukan bobot kriteria menggunakan metode AHP (Analytical Hierarchy Process)</p>
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

<?php if(session('ahp_consistency_ratio')): ?>
    <?php
        $cr = session('ahp_consistency_ratio');
        $isConsistent = $cr < 0.1;
    ?>
    <div class="alert <?php echo e($isConsistent ? 'alert-success' : 'alert-warning'); ?> alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">
            <i class="fas <?php echo e($isConsistent ? 'fa-check-circle' : 'fa-exclamation-triangle'); ?> me-2"></i>
            Consistency Ratio (CR): <?php echo e(number_format($cr, 4)); ?>

        </h5>
        <p class="mb-0">
            <?php if($isConsistent): ?>
                <strong>Konsisten!</strong> Perbandingan Anda konsisten (CR < 0.1). Bobot kriteria dapat digunakan.
            <?php else: ?>
                <strong>Tidak Konsisten!</strong> Perbandingan Anda tidak konsisten (CR ≥ 0.1). Mohon periksa kembali nilai perbandingan.
            <?php endif; ?>
        </p>
        <small class="d-block mt-2">λmax: <?php echo e(number_format(session('ahp_lambda_max'), 4)); ?></small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Kriteria Card -->
<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Daftar Kriteria
                    </h5>
                    <?php if($kriteria->isEmpty()): ?>
                        <form action="<?php echo e(route('spk.initialize-criteria')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Initialize
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <?php if($kriteria->isEmpty()): ?>
                    <p class="text-muted text-center py-3">Belum ada kriteria. Klik "Initialize" untuk menambahkan kriteria default.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?php echo e($k->nama_kriteria); ?></h6>
                                    <small class="text-muted"><?php echo e($k->kode_kriteria); ?></small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary">
                                        Bobot: <?php echo e(number_format($k->bobot, 4)); ?>

                                    </span>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editKriteriaModal<?php echo e($k->id); ?>"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="<?php echo e(route('spk.delete-kriteria', $k->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-outline-danger"
                                                onclick="return confirm('Hapus kriteria <?php echo e($k->nama_kriteria); ?>? Semua data perbandingan terkait akan ikut terhapus.')"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted d-block mb-2"><strong>Total Bobot:</strong></small>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo e($kriteria->sum('bobot') * 100); ?>%">
                                <?php echo e(number_format($kriteria->sum('bobot'), 4)); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- AHP Scale Reference -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Skala Perbandingan AHP</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>1</td><td>Sama penting</td></tr>
                        <tr><td>3</td><td>Sedikit lebih penting</td></tr>
                        <tr><td>5</td><td>Lebih penting</td></tr>
                        <tr><td>7</td><td>Sangat lebih penting</td></tr>
                        <tr><td>9</td><td>Mutlak lebih penting</td></tr>
                        <tr><td>2,4,6,8</td><td>Nilai antara</td></tr>
                        <tr><td>1/3, 1/5, ...</td><td>Kebalikan</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pairwise Comparison Matrix -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Matriks Perbandingan Berpasangan
                </h5>
            </div>
            <div class="card-body">
                <?php if($kriteria->isEmpty()): ?>
                    <p class="text-muted text-center py-5">Inisialisasi kriteria terlebih dahulu untuk membuat matriks perbandingan.</p>
                <?php else: ?>
                    <form action="<?php echo e(route('spk.store-pairwise')); ?>" method="POST" id="pairwiseForm">
                        <?php echo csrf_field(); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th><?php echo e($k->nama_kriteria); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <th class="table-light"><?php echo e($k1->nama_kriteria); ?></th>
                                            <?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td>
                                                    <?php if($k1->id == $k2->id): ?>
                                                        <span class="badge bg-secondary">1</span>
                                                    <?php elseif($k1->id < $k2->id): ?>
                                                        <input type="number"
                                                            class="form-control form-control-sm text-center matrix-input"
                                                            name="comparisons[<?php echo e($k1->id); ?>_<?php echo e($k2->id); ?>][nilai]"
                                                            value="<?php echo e($matrix[$k1->id][$k2->id] ?? 1); ?>"
                                                            step="0.001"
                                                            min="0.10"
                                                            max="9"
                                                            data-row="<?php echo e($k1->id); ?>"
                                                            data-col="<?php echo e($k2->id); ?>"
                                                            style="width: 80px; display: inline-block;">
                                                        <input type="hidden" name="comparisons[<?php echo e($k1->id); ?>_<?php echo e($k2->id); ?>][kriteria_1_id]" value="<?php echo e($k1->id); ?>">
                                                        <input type="hidden" name="comparisons[<?php echo e($k1->id); ?>_<?php echo e($k2->id); ?>][kriteria_2_id]" value="<?php echo e($k2->id); ?>">
                                                    <?php else: ?>
                                                        <span class="badge bg-info reciprocal" data-row="<?php echo e($k1->id); ?>" data-col="<?php echo e($k2->id); ?>">
                                                            <?php echo e($matrix[$k1->id][$k2->id] ? number_format($matrix[$k1->id][$k2->id], 2) : '1.00'); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>
                                            <td class="table-light">
                                                <strong class="row-total" data-row="<?php echo e($k1->id); ?>">
                                                    <?php echo e(number_format(array_sum($matrix[$k1->id] ?? []), 2)); ?>

                                                </strong>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetMatrix()">
                                <i class="fas fa-undo me-2"></i>
                                Reset ke 1
                            </button>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-calculator me-2"></i>
                                    Hitung Bobot AHP
                                </button>
                                <form action="<?php echo e(route('spk.apply-weights')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i>
                                        Terapkan Bobot
                                    </button>
                                </form>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto-update reciprocal values with AHP scale snapping for readability
function snapToAHPScale(x) {
    const scale = [1,2,3,4,5,6,7,8,9];
    let best = x, bestDiff = Infinity;
    for (const s of scale) {
        const d = Math.abs(x - s);
        if (d < bestDiff) { bestDiff = d; best = s; }
    }
    // Snap only if close enough (within 0.05)
    return bestDiff <= 0.05 ? best : x;
}

// Snap small decimals to nearest reciprocal 1/n (n=2..9)
function snapToReciprocalScale(x) {
    const candidates = [1/2,1/3,1/4,1/5,1/6,1/7,1/8,1/9];
    let best = x, bestDiff = Infinity;
    for (const c of candidates) {
        const d = Math.abs(x - c);
        if (d < bestDiff) { bestDiff = d; best = c; }
    }
    return bestDiff <= 0.01 ? best : x; // tighter threshold for decimals
}

document.querySelectorAll('.matrix-input').forEach(input => {
    // Allow free editing: don't force a value while typing
    input.addEventListener('input', function() {
        const row = this.dataset.row;
        const col = this.dataset.col;
        // Support locale input with comma decimals
        const raw = (this.value || '').toString();
        if (raw.trim() === '') {
            // If empty, let user continue editing; defer normalization to blur
            return;
        }
        let value = parseFloat(raw.replace(',', '.'));
        if (isNaN(value)) {
            // Invalid partial input; don't overwrite while typing
            return;
        }

        // If user types small decimal, snap to nearest 1/n for clarity
        if (value > 0 && value < 1) {
            value = snapToReciprocalScale(value);
            this.value = Number(value).toFixed(3); // show 3 decimals for small values
        }

        // Update reciprocal cell
        const reciprocal = document.querySelector(`.reciprocal[data-row="${col}"][data-col="${row}"]`);
        if (reciprocal) {
            const recip = 1 / value;
            const snapped = snapToAHPScale(recip);
            reciprocal.textContent = Number(snapped).toFixed(2);
        }

        // Update row totals
        updateRowTotal(row);
        updateRowTotal(col);
    });
    });

    // On blur, normalize empty/invalid to 1 and refresh computed cells
    input.addEventListener('blur', function() {
        const row = this.dataset.row;
        const col = this.dataset.col;
        const raw = (this.value || '').toString();
        let value = parseFloat(raw.replace(',', '.'));
        if (isNaN(value) || raw.trim() === '') {
            value = 1;
            this.value = '1';
        }

        if (value > 0 && value < 1) {
            value = snapToReciprocalScale(value);
            this.value = Number(value).toFixed(3);
        }

        const reciprocal = document.querySelector(`.reciprocal[data-row="${col}"][data-col="${row}"]`);
        if (reciprocal) {
            const recip = 1 / value;
            const snapped = snapToAHPScale(recip);
            reciprocal.textContent = Number(snapped).toFixed(2);
        }

        updateRowTotal(row);
        updateRowTotal(col);
    });
});

function updateRowTotal(rowId) {
    let total = 0;

    // Sum all values in the row
    document.querySelectorAll(`[data-row="${rowId}"]`).forEach(cell => {
        if (cell.classList.contains('matrix-input')) {
            total += parseFloat(cell.value) || 1;
        } else if (cell.classList.contains('reciprocal')) {
            total += parseFloat(cell.textContent) || 1;
        }
    });

    // Add diagonal value (always 1)
    total += 1;

    const totalCell = document.querySelector(`.row-total[data-row="${rowId}"]`);
    if (totalCell) {
        totalCell.textContent = total.toFixed(2);
    }
}

function resetMatrix() {
    if (confirm('Reset semua nilai perbandingan ke 1?')) {
        document.querySelectorAll('.matrix-input').forEach(input => {
            input.value = 1;
            input.dispatchEvent(new Event('input'));
        });
    }
}
</script>
<?php $__env->stopPush(); ?>

<!-- Edit Kriteria Modals -->
<?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editKriteriaModal<?php echo e($k->id); ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Kriteria
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('spk.update-kriteria', $k->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kriteria<?php echo e($k->id); ?>" class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control" id="nama_kriteria<?php echo e($k->id); ?>"
                            name="nama_kriteria" value="<?php echo e($k->nama_kriteria); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kode_kriteria<?php echo e($k->id); ?>" class="form-label">Kode Kriteria</label>
                        <input type="text" class="form-control" id="kode_kriteria<?php echo e($k->id); ?>"
                            name="kode_kriteria" value="<?php echo e($k->kode_kriteria); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi<?php echo e($k->id); ?>" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi<?php echo e($k->id); ?>"
                            name="deskripsi" rows="3"><?php echo e($k->deskripsi); ?></textarea>
                    </div>
                    <div class="alert alert-info">
                        <small><i class="fas fa-info-circle me-1"></i>
                        Bobot akan dihitung ulang setelah Anda melakukan perbandingan di matriks.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\tugas_akhir\resources\views/spk/analysis.blade.php ENDPATH**/ ?>
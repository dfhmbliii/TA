<?php $__env->startSection('title', 'Analisis ' . $prodi->nama_prodi); ?>

<?php
function getFakultasColor($fakultas) {
    $fakultasLower = strtolower($fakultas);
    if (str_contains($fakultasLower, 'rekayasa industri')) return '#208E3B';
    elseif (str_contains($fakultasLower, 'teknik elektro')) return '#007ACC';
    elseif (str_contains($fakultasLower, 'informatika')) return '#A48B31';
    elseif (str_contains($fakultasLower, 'industri kreatif')) return '#F57D20';
    return '#6c757d';
}
?>

<?php $__env->startSection('content'); ?>
<div class="content-header" style="border-left-color: <?php echo e(getFakultasColor($prodi->nama_fakultas)); ?>;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Analisis: <?php echo e($prodi->nama_prodi); ?></h1>
            <p>Matriks perbandingan berpasangan mata pelajaran</p>
        </div>
        <a href="<?php echo e(route('prodi.list')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali
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

<?php if(session("prodi_{$prodi->id}_cr")): ?>
    <?php
        $cr = session("prodi_{$prodi->id}_cr");
        $isConsistent = $cr < 0.1;
    ?>
    <div class="alert <?php echo e($isConsistent ? 'alert-success' : 'alert-warning'); ?> alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">
            <i class="fas <?php echo e($isConsistent ? 'fa-check-circle' : 'fa-exclamation-triangle'); ?> me-2"></i>
            Consistency Ratio (CR): <?php echo e(number_format($cr, 4)); ?>

        </h5>
        <p class="mb-0">
            <?php if($isConsistent): ?>
                <strong>Konsisten!</strong> Perbandingan konsisten (CR < 0.1).
            <?php else: ?>
                <strong>Tidak Konsisten!</strong> Mohon periksa kembali nilai perbandingan (CR ≥ 0.1).
            <?php endif; ?>
        </p>
        <small class="d-block mt-2">λmax: <?php echo e(number_format(session("prodi_{$prodi->id}_lambda"), 4)); ?></small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Alternatif List -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header" style="background-color: <?php echo e(getFakultasColor($prodi->nama_fakultas)); ?>; color: white;">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Mata Pelajaran
                </h5>
            </div>
            <div class="card-body">
                <?php if($alternatives->isEmpty()): ?>
                    <p class="text-muted text-center py-3">Belum ada mata pelajaran. Klik tombol di bawah untuk menambahkan.</p>
                    <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#addAlternativeModal">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Mata Pelajaran
                    </button>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $alternatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?php echo e($alt->nama_alternatif); ?></h6>
                                    <small class="text-muted"><?php echo e($alt->kode_alternatif); ?></small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge" style="background-color: <?php echo e(getFakultasColor($prodi->nama_fakultas)); ?>;">
                                        <?php echo e(number_format($alt->bobot, 4)); ?>

                                    </span>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editAlternativeModal<?php echo e($alt->id); ?>"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="<?php echo e(route('prodi.delete-alternative', [$prodi->id, $alt->id])); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                onclick="return confirm('Hapus <?php echo e($alt->nama_alternatif); ?>? Data perbandingan terkait akan ikut terhapus.')"
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
                            <div class="progress-bar" role="progressbar" 
                                style="width: <?php echo e($alternatives->sum('bobot') * 100); ?>%; background-color: <?php echo e(getFakultasColor($prodi->nama_fakultas)); ?>;">
                                <?php echo e(number_format($alternatives->sum('bobot'), 4)); ?>

                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-3" data-bs-toggle="modal" data-bs-target="#addAlternativeModal">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Mata Pelajaran
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- AHP Scale Reference -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Skala AHP</h6>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pairwise Comparison Matrix -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header" style="background-color: <?php echo e(getFakultasColor($prodi->nama_fakultas)); ?>; color: white;">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Matriks Perbandingan Berpasangan
                </h5>
            </div>
            <div class="card-body">
                <?php if($alternatives->isEmpty()): ?>
                    <p class="text-muted text-center py-5">Tambahkan mata pelajaran terlebih dahulu untuk membuat matriks perbandingan.</p>
                <?php else: ?>
                    <form action="<?php echo e(route('prodi.store-pairwise', $prodi->id)); ?>" method="POST" id="pairwiseForm">
                        <?php echo csrf_field(); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <?php $__currentLoopData = $alternatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th><?php echo e($alt->nama_alternatif); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $alternatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <th class="table-light"><?php echo e($a1->nama_alternatif); ?></th>
                                            <?php $__currentLoopData = $alternatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td>
                                                    <?php if($a1->id == $a2->id): ?>
                                                        <span class="badge bg-secondary">1</span>
                                                    <?php elseif($a1->id < $a2->id): ?>
                                                        <input type="number" 
                                                            class="form-control form-control-sm text-center matrix-input" 
                                                            name="comparisons[<?php echo e($a1->id); ?>_<?php echo e($a2->id); ?>][nilai]"
                                                            value="<?php echo e($matrix[$a1->id][$a2->id] ?? 1); ?>"
                                                            step="0.01"
                                                            min="0.11"
                                                            max="9"
                                                            data-row="<?php echo e($a1->id); ?>"
                                                            data-col="<?php echo e($a2->id); ?>"
                                                            style="width: 80px; display: inline-block;">
                                                        <input type="hidden" name="comparisons[<?php echo e($a1->id); ?>_<?php echo e($a2->id); ?>][alternative_1_id]" value="<?php echo e($a1->id); ?>">
                                                        <input type="hidden" name="comparisons[<?php echo e($a1->id); ?>_<?php echo e($a2->id); ?>][alternative_2_id]" value="<?php echo e($a2->id); ?>">
                                                    <?php else: ?>
                                                        <span class="badge bg-info reciprocal" data-row="<?php echo e($a1->id); ?>" data-col="<?php echo e($a2->id); ?>">
                                                            <?php echo e($matrix[$a1->id][$a2->id] ? number_format($matrix[$a1->id][$a2->id], 2) : '1.00'); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <td class="table-light">
                                                <strong class="row-total" data-row="<?php echo e($a1->id); ?>">
                                                    <?php echo e(number_format(array_sum($matrix[$a1->id] ?? []), 2)); ?>

                                                </strong>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetMatrix()">
                                <i class="fas fa-undo me-2"></i>
                                Reset ke 1
                            </button>
                            <button type="submit" class="btn btn-primary" style="background-color: <?php echo e(getFakultasColor($prodi->nama_fakultas)); ?>; border-color: <?php echo e(getFakultasColor($prodi->nama_fakultas)); ?>;">
                                <i class="fas fa-calculator me-2"></i>
                                Hitung Bobot AHP
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Alternative Modal -->
<div class="modal fade" id="addAlternativeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Mata Pelajaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('prodi.initialize-alternatives', $prodi->id)); ?>" method="POST" id="addAlternativeForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div id="alternativesList">
                        <div class="mb-3 alternative-item">
                            <label class="form-label">Mata Pelajaran 1</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="alternatives[0][nama]" placeholder="Nama mata pelajaran" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="alternatives[0][kode]" placeholder="Kode" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAlternativeField()">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Lagi
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let alternativeCount = 1;

function addAlternativeField() {
    const html = `
        <div class="mb-3 alternative-item">
            <label class="form-label">Mata Pelajaran ${alternativeCount + 1}</label>
            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="alternatives[${alternativeCount}][nama]" placeholder="Nama mata pelajaran" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="alternatives[${alternativeCount}][kode]" placeholder="Kode" required>
                </div>
            </div>
        </div>
    `;
    document.getElementById('alternativesList').insertAdjacentHTML('beforeend', html);
    alternativeCount++;
}

// Auto-update reciprocal values
document.querySelectorAll('.matrix-input').forEach(input => {
    input.addEventListener('input', function() {
        const row = this.dataset.row;
        const col = this.dataset.col;
        const value = parseFloat(this.value) || 1;
        
        // Update reciprocal cell
        const reciprocal = document.querySelector(`.reciprocal[data-row="${col}"][data-col="${row}"]`);
        if (reciprocal) {
            reciprocal.textContent = (1 / value).toFixed(2);
        }
        
        // Update row totals
        updateRowTotal(row);
        updateRowTotal(col);
    });
});

function updateRowTotal(rowId) {
    let total = 0;
    
    document.querySelectorAll(`[data-row="${rowId}"]`).forEach(cell => {
        if (cell.classList.contains('matrix-input')) {
            total += parseFloat(cell.value) || 1;
        } else if (cell.classList.contains('reciprocal')) {
            total += parseFloat(cell.textContent) || 1;
        }
    });
    
    total += 1; // diagonal
    
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

<!-- Edit Alternative Modals -->
<?php $__currentLoopData = $alternatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editAlternativeModal<?php echo e($alt->id); ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Mata Pelajaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('prodi.update-alternative', [$prodi->id, $alt->id])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_alternatif<?php echo e($alt->id); ?>" class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" class="form-control" id="nama_alternatif<?php echo e($alt->id); ?>" 
                            name="nama_alternatif" value="<?php echo e($alt->nama_alternatif); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kode_alternatif<?php echo e($alt->id); ?>" class="form-label">Kode</label>
                        <input type="text" class="form-control" id="kode_alternatif<?php echo e($alt->id); ?>" 
                            name="kode_alternatif" value="<?php echo e($alt->kode_alternatif); ?>" required>
                    </div>
                    <div class="alert alert-info">
                        <small><i class="fas fa-info-circle me-1"></i> 
                        Bobot akan dihitung ulang setelah perbandingan di matriks.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" style="background-color: <?php echo e(getFakultasColor($prodi->nama_fakultas)); ?>; border-color: <?php echo e(getFakultasColor($prodi->nama_fakultas)); ?>;">
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\tugas_akhir\resources\views/spk/prodi-analysis.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'SPK - Perbandingan Prospek Karir'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0 d-flex align-items-center gap-2">
            <span>Perbandingan Prospek Karir</span>
            <?php ($krCr = session('karir_cr')); ?>
            <?php if(!is_null($krCr)): ?>
                <?php if($krCr < 0.1): ?>
                    <span class="badge bg-success">Konsisten (CR <?php echo e(number_format($krCr, 3)); ?>)</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Tidak Konsisten (CR <?php echo e(number_format($krCr, 3)); ?>)</span>
                <?php endif; ?>
            <?php endif; ?>
        </h1>
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

<?php if(session('karir_cr')): ?>
    <?php ($krCr = session('karir_cr')); ?>
    <?php ($krLambda = session('karir_lambda_max')); ?>
    <div class="alert <?php echo e($krCr < 0.1 ? 'alert-success' : 'alert-warning'); ?> alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">
            <i class="fas <?php echo e($krCr < 0.1 ? 'fa-check-circle' : 'fa-exclamation-triangle'); ?> me-2"></i>
            Consistency Ratio (CR): <?php echo e(number_format($krCr, 4)); ?>

        </h5>
        <p class="mb-0">
            <?php if($krCr < 0.1): ?>
                <strong>Konsisten!</strong> Perbandingan Anda konsisten (CR < 0.1). Bobot dapat digunakan.
            <?php else: ?>
                <strong>Tidak Konsisten!</strong> Perbandingan belum konsisten (CR ≥ 0.1). Silakan tinjau kembali input.
            <?php endif; ?>
        </p>
        <small class="d-block mt-2">λmax: <?php echo e(number_format($krLambda, 4)); ?></small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Daftar Kategori Prospek Karir
                    </h5>
                    <?php if($categories->isEmpty()): ?>
                        <form action="<?php echo e(route('spk.karir.initialize')); ?>" method="POST">
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
                <?php if($categories->isEmpty()): ?>
                    <p class="text-muted text-center py-3">Belum ada kategori. Klik "Initialize" untuk menambahkan kategori default.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?php echo e($c->nama); ?></h6>
                                    <small class="text-muted"><?php echo e($c->kode); ?></small>
                                </div>
                                <span class="badge bg-primary">
                                    Bobot: <?php echo e(number_format($c->bobot, 4)); ?>

                                </span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted d-block mb-2"><strong>Total Bobot:</strong></small>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo e($categories->sum('bobot') * 100); ?>%">
                                <?php echo e(number_format($categories->sum('bobot'), 4)); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

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

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Matriks Perbandingan Berpasangan
                </h5>
            </div>
            <div class="card-body">
                <?php if($categories->isEmpty()): ?>
                    <p class="text-muted text-center py-5">Inisialisasi kategori terlebih dahulu untuk membuat matriks perbandingan.</p>
                <?php else: ?>
                    <form action="<?php echo e(route('spk.karir.store-pairwise')); ?>" method="POST" id="pairwiseForm">
                        <?php echo csrf_field(); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th><?php echo e($c->nama); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <th class="table-light"><?php echo e($c1->nama); ?></th>
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td>
                                                    <?php if($c1->id == $c2->id): ?>
                                                        <span class="badge bg-secondary">1</span>
                                                    <?php elseif($c1->id < $c2->id): ?>
                                                        <input type="number" class="form-control form-control-sm text-center matrix-input"
                                                               name="comparisons[<?php echo e($c1->id); ?>_<?php echo e($c2->id); ?>][nilai]"
                                                               value="<?php echo e($matrix[$c1->id][$c2->id] ?? 1); ?>"
                                                               step="0.001" min="0.10" max="9"
                                                               data-row="<?php echo e($c1->id); ?>" data-col="<?php echo e($c2->id); ?>"
                                                               style="width: 80px; display: inline-block;">
                                                        <input type="hidden" name="comparisons[<?php echo e($c1->id); ?>_<?php echo e($c2->id); ?>][category_1_id]" value="<?php echo e($c1->id); ?>">
                                                        <input type="hidden" name="comparisons[<?php echo e($c1->id); ?>_<?php echo e($c2->id); ?>][category_2_id]" value="<?php echo e($c2->id); ?>">
                                                    <?php else: ?>
                                                        <span class="badge bg-info reciprocal" data-row="<?php echo e($c1->id); ?>" data-col="<?php echo e($c2->id); ?>">
                                                            <?php echo e($matrix[$c1->id][$c2->id] ? number_format($matrix[$c1->id][$c2->id], 2) : '1.00'); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <td class="table-light"><strong class="row-total" data-row="<?php echo e($c1->id); ?>"><?php echo e(number_format(array_sum($matrix[$c1->id] ?? []), 2)); ?></strong></td>
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
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-calculator me-2"></i>
                                    Hitung Bobot AHP
                                </button>
                                <form action="<?php echo e(route('spk.karir.apply-weights')); ?>" method="POST">
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
function snapToAHPScale(x){const s=[1,2,3,4,5,6,7,8,9];let b=x,d=Infinity;for(const v of s){const t=Math.abs(x-v);if(t<d){d=t;b=v}}return d<=0.05?b:x}
function snapToReciprocalScale(x){const c=[1/2,1/3,1/4,1/5,1/6,1/7,1/8,1/9];let b=x,d=Infinity;for(const v of c){const t=Math.abs(x-v);if(t<d){d=t;b=v}}return d<=0.01?b:x}

document.querySelectorAll('.matrix-input').forEach(input=>{
  input.addEventListener('input',function(){
    const row=this.dataset.row,col=this.dataset.col;
    const raw=(this.value||'').toString();
    if(raw.trim()===''){return}
    let value=parseFloat(raw.replace(',','.'));
    if(isNaN(value)){return}
    const reciprocal=document.querySelector(`.reciprocal[data-row="${col}"][data-col="${row}"]`);
    if(reciprocal){const recip=1/value;const snapped=snapToAHPScale(recip);reciprocal.textContent=Number(snapped).toFixed(2)}
    updateRowTotal(row);updateRowTotal(col);
  });
  input.addEventListener('blur',function(){
    const row=this.dataset.row,col=this.dataset.col;
    const raw=(this.value||'').toString();
    let value=parseFloat(raw.replace(',','.'));
    if(isNaN(value)||raw.trim()===''){value=1;this.value='1'}
    if(value>0&&value<1){value=snapToReciprocalScale(value);this.value=Number(value).toFixed(3)}
    const reciprocal=document.querySelector(`.reciprocal[data-row="${col}"][data-col="${row}"]`);
    if(reciprocal){const recip=1/value;const snapped=snapToAHPScale(recip);reciprocal.textContent=Number(snapped).toFixed(2)}
    updateRowTotal(row);updateRowTotal(col);
  });
});

function updateRowTotal(rowId){let total=0;document.querySelectorAll(`[data-row="${rowId}"]`).forEach(cell=>{if(cell.classList.contains('matrix-input')){total+=parseFloat(cell.value)||1}else if(cell.classList.contains('reciprocal')){total+=parseFloat(cell.textContent)||1}});total+=1;const el=document.querySelector(`.row-total[data-row="${rowId}"]`);if(el){el.textContent=total.toFixed(2)}}
function resetMatrix(){if(confirm('Reset semua nilai perbandingan ke 1?')){document.querySelectorAll('.matrix-input').forEach(input=>{input.value=1;input.dispatchEvent(new Event('input'))})}}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tugas_akhir\resources\views/spk/karir-analysis.blade.php ENDPATH**/ ?>
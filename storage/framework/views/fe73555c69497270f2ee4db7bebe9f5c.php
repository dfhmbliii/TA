<?php $__env->startSection('title', 'Analisis SPK - Rekomendasi Program Studi'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <h1><i class="fas fa-graduation-cap me-2"></i>Rekomendasi Program Studi</h1>
    <p>Sistem Pendukung Keputusan untuk menemukan program studi yang sesuai dengan minat dan kemampuan Anda</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Formulir Analisis SPK
                </h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('siswa-spk.calculate')); ?>" method="POST" id="spkForm">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Alert Info -->
                    <div class="alert alert-info border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk:</strong> Pilih kriteria yang sesuai dengan minat dan kemampuan Anda. Untuk mata pelajaran, masukkan nilai rata-rata rapor Anda.
                    </div>

                    <!-- Dynamic Kriteria Forms -->
                    <?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!in_array($k->kode_kriteria, ['NILAI','AKADEMIK'])): ?>
                        <div class="mb-4">
                            <label for="kriteria_<?php echo e(strtolower($k->kode_kriteria)); ?>" class="form-label fw-bold">
                                <i class="fas fa-<?php echo e($k->kode_kriteria === 'MINAT' ? 'heart' : ($k->kode_kriteria === 'BAKAT' ? 'star' : 'briefcase')); ?> text-<?php echo e($k->kode_kriteria === 'MINAT' ? 'danger' : ($k->kode_kriteria === 'BAKAT' ? 'warning' : 'success')); ?> me-2"></i>
                                <?php echo e($k->nama_kriteria); ?>

                            </label>
                            <select name="kriteria_<?php echo e(strtolower($k->kode_kriteria)); ?>" 
                                    id="kriteria_<?php echo e(strtolower($k->kode_kriteria)); ?>" 
                                    class="form-select form-select-lg <?php $__errorArgs = ['kriteria_' . strtolower($k->kode_kriteria)];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    required>
                                <option value="">-- Pilih <?php echo e($k->nama_kriteria); ?> --</option>
                                <?php if(isset($kriteriaOptions[$k->kode_kriteria])): ?>
                                    <?php $__currentLoopData = $kriteriaOptions[$k->kode_kriteria]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['kriteria_' . strtolower($k->kode_kriteria)];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php if($k->deskripsi): ?>
                            <div class="form-text"><?php echo e($k->deskripsi); ?></div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <hr class="my-4">

                    <!-- Mata Pelajaran Relevan -->
                    <h6 class="mb-3 fw-bold">
                        <i class="fas fa-book text-primary me-2"></i>
                        Nilai Mata Pelajaran Relevan
                    </h6>
                    <p class="text-muted small mb-3">Masukkan nilai rata-rata rapor Anda untuk mata pelajaran berikut (skala 0-100):</p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="nilai_bahasa_inggris" class="form-label">
                                <i class="fas fa-language me-1"></i>Bahasa Inggris
                            </label>
                            <input type="number" name="nilai_mapel[bahasa_inggris]" id="nilai_bahasa_inggris" 
                                   class="form-control <?php $__errorArgs = ['nilai_mapel.bahasa_inggris'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   min="0" max="100" step="0.1" required placeholder="Contoh: 85.5">
                            <?php $__errorArgs = ['nilai_mapel.bahasa_inggris'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label for="nilai_informatika" class="form-label">
                                <i class="fas fa-laptop-code me-1"></i>Informatika / TIK
                            </label>
                            <input type="number" name="nilai_mapel[informatika]" id="nilai_informatika" 
                                   class="form-control <?php $__errorArgs = ['nilai_mapel.informatika'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   min="0" max="100" step="0.1" required placeholder="Contoh: 90">
                            <?php $__errorArgs = ['nilai_mapel.informatika'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label for="nilai_matematika" class="form-label">
                                <i class="fas fa-calculator me-1"></i>Matematika
                            </label>
                            <input type="number" name="nilai_mapel[matematika]" id="nilai_matematika" 
                                   class="form-control <?php $__errorArgs = ['nilai_mapel.matematika'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   min="0" max="100" step="0.1" required placeholder="Contoh: 88">
                            <?php $__errorArgs = ['nilai_mapel.matematika'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label for="nilai_seni_budaya" class="form-label">
                                <i class="fas fa-palette me-1"></i>Seni Budaya
                            </label>
                            <input type="number" name="nilai_mapel[seni_budaya]" id="nilai_seni_budaya" 
                                   class="form-control <?php $__errorArgs = ['nilai_mapel.seni_budaya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   min="0" max="100" step="0.1" required placeholder="Contoh: 82">
                            <?php $__errorArgs = ['nilai_mapel.seni_budaya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-calculator me-2"></i>
                            Hitung Rekomendasi Program Studi
                        </button>
                        <a href="<?php echo e(route('siswa.dashboard')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="col-lg-4">
        <!-- Kriteria AHP -->
        <div class="card shadow mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Kriteria Penilaian
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">Sistem menggunakan metode AHP (Analytical Hierarchy Process) dengan kriteria berikut:</p>
                
                <?php if($kriteria->count() > 0): ?>
                    <?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold"><?php echo e($k->nama_kriteria); ?></span>
                            <span class="badge bg-primary"><?php echo e(number_format($k->bobot * 100, 0)); ?>%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo e($k->bobot * 100); ?>%"></div>
                        </div>
                        <?php if($k->deskripsi): ?>
                        <p class="small text-muted mb-0 mt-1"><?php echo e($k->deskripsi); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-muted small">Kriteria belum dikonfigurasi oleh admin.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Program Studi Available -->
        <div class="card shadow">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-university me-2"></i>
                    Program Studi Tersedia
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">Sistem akan merekomendasikan dari <?php echo e($prodis->count()); ?> program studi:</p>
                
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $prodis->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-group-item px-0 py-2 border-0">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <small><?php echo e($prodi->nama_prodi); ?></small>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php if($prodis->count() > 5): ?>
                    <div class="list-group-item px-0 py-2 border-0">
                        <small class="text-muted">Dan <?php echo e($prodis->count() - 5); ?> lainnya...</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .form-select-lg, .form-control {
        border-radius: 8px;
    }
    
    .card {
        border-radius: 12px;
        border: none;
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }
    
    .progress {
        border-radius: 10px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Form validation
    document.getElementById('spkForm').addEventListener('submit', function(e) {
        const nilaiInputs = document.querySelectorAll('input[type="number"]');
        let valid = true;
        
        nilaiInputs.forEach(input => {
            const value = parseFloat(input.value);
            if (isNaN(value) || value < 0 || value > 100) {
                valid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Pastikan semua nilai mata pelajaran diisi dengan benar (0-100)');
        }
    });
    
    // Real-time validation for number inputs
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (value < 0) this.value = 0;
            if (value > 100) this.value = 100;
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tugas_akhir\resources\views/spk/siswa-form.blade.php ENDPATH**/ ?>
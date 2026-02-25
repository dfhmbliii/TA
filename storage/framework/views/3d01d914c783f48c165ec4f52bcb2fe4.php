<?php $__env->startSection('title', 'Program Studi'); ?>

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
    return '#6c757d'; // default gray
}
?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <h1>Kelola Program Studi</h1>
    <p>Manajemen data program studi untuk sistem SPK</p>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-book me-2"></i>
                Daftar Program Studi
            </h5>
            <?php if(Auth::check() && Auth::user()->role === 'admin'): ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProdiModal">
                <i class="fas fa-plus me-2"></i>
                Tambah Program Studi
            </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="fas fa-graduation-cap me-2"></i> Nama Program Studi</th>
                        <th><i class="fas fa-university me-2"></i> Nama Fakultas</th>
                        <th><i class="fas fa-code me-2"></i> Kode Prodi</th>
                        <th><i class="fas fa-info-circle me-2"></i> Deskripsi</th>
                        <th class="text-center"><i class="fas fa-cogs me-2"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php
                                    $color = getFakultasColor($prodi->nama_fakultas);
                                ?>
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="background-color: <?php echo e($color); ?>; width: 40px; height: 40px; min-width: 40px; font-weight: 600; font-size: 18px;">
                                    <?php echo e(substr($prodi->nama_prodi, 0, 1)); ?>

                                </div>
                                <div>
                                    <h6 class="mb-0"><?php echo e($prodi->nama_prodi); ?></h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php
                                $color = getFakultasColor($prodi->nama_fakultas);
                            ?>
                            <span class="badge" style="background-color: <?php echo e($color); ?>; color: white;"><?php echo e($prodi->nama_fakultas); ?></span>
                        </td>
                        <td>
                            <?php
                                $color = getFakultasColor($prodi->nama_fakultas);
                            ?>
                            <span class="badge" style="background-color: <?php echo e($color); ?>; color: white;"><?php echo e($prodi->kode_prodi); ?></span>
                        </td>
                        <td><?php echo e($prodi->deskripsi); ?></td>
                        <?php if(Auth::check() && Auth::user()->role === 'admin'): ?>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('prodi.show', $prodi->id)); ?>" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editProdi(<?php echo e($prodi->id); ?>)" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="<?php echo e(route('prodi.kurikulum', $prodi->id)); ?>" class="btn btn-sm btn-outline-success" title="Edit Kurikulum">
                                    <i class="fas fa-book-open"></i>
                                </a>
                                <form action="<?php echo e(route('prodi.destroy', $prodi->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus program studi ini?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <?php else: ?>
                        <td class="text-center">
                            <a href="<?php echo e(route('prodi.show', $prodi->id)); ?>" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createProdiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Program Studi Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('prodi.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Terjadi Kesalahan Validasi
                            </h6>
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nama_prodi" class="form-label">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    Nama Program Studi
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['nama_prodi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nama_prodi" name="nama_prodi" value="<?php echo e(old('nama_prodi')); ?>" required placeholder="Masukkan nama program studi">
                                <?php $__errorArgs = ['nama_prodi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Nama lengkap program studi</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nama_fakultas" class="form-label">
                                    <i class="fas fa-university me-1"></i>
                                    Nama Fakultas
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['nama_fakultas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nama_fakultas" name="nama_fakultas" value="<?php echo e(old('nama_fakultas')); ?>" required placeholder="Masukkan nama fakultas">
                                <?php $__errorArgs = ['nama_fakultas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Nama fakultas program studi</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="kode_prodi" class="form-label">
                                    <i class="fas fa-code me-1"></i>
                                    Kode Program Studi
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['kode_prodi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="kode_prodi" name="kode_prodi" value="<?php echo e(old('kode_prodi')); ?>" required placeholder="Contoh: TI, SI, MI">
                                <?php $__errorArgs = ['kode_prodi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Kode singkat program studi</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">
                            <i class="fas fa-info-circle me-1"></i>
                            Deskripsi Program Studi
                        </label>
                        <textarea class="form-control <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi program studi"><?php echo e(old('deskripsi')); ?></textarea>
                        <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Deskripsi lengkap tentang program studi</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Simpan Program Studi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Prodi Modal -->
<div class="modal fade" id="editProdiModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Program Studi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProdiForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <h6 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="edit_nama_prodi" class="form-label">Nama Program Studi</label>
                            <input type="text" class="form-control" id="edit_nama_prodi" name="nama_prodi" value="<?php echo e(old('nama_prodi')); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="edit_nama_fakultas" class="form-label">Nama Fakultas</label>
                            <input type="text" class="form-control" id="edit_nama_fakultas" name="nama_fakultas" value="<?php echo e(old('nama_fakultas')); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="edit_kode_prodi" class="form-label">Kode Program Studi</label>
                            <input type="text" class="form-control" id="edit_kode_prodi" name="kode_prodi" value="<?php echo e(old('kode_prodi')); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi Ringkas</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="2"><?php echo e(old('deskripsi')); ?></textarea>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3 text-primary"><i class="fas fa-bullseye me-2"></i>Visi & Misi</h6>
                    <div class="mb-3">
                        <label for="edit_visi_misi" class="form-label">Konten Visi & Misi</label>
                        <textarea class="form-control" id="edit_visi_misi" name="visi_misi" rows="4" placeholder="Masukkan visi dan misi program studi..."><?php echo e(old('visi_misi')); ?></textarea>
                        <div class="form-text">Atau gunakan link eksternal di bawah jika konten ada di website resmi</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_visi_misi_url" class="form-label">Link Visi & Misi (Opsional)</label>
                        <input type="url" class="form-control" id="edit_visi_misi_url" name="visi_misi_url" value="<?php echo e(old('visi_misi_url')); ?>" placeholder="https://...">
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3 text-primary"><i class="fas fa-briefcase me-2"></i>Prospek Kerja</h6>
                    <div class="mb-3">
                        <label for="edit_prospek_kerja" class="form-label">Konten Prospek Kerja</label>
                        <textarea class="form-control" id="edit_prospek_kerja" name="prospek_kerja" rows="4" placeholder="Masukkan informasi prospek kerja lulusan..."><?php echo e(old('prospek_kerja')); ?></textarea>
                        <div class="form-text">Atau gunakan link eksternal di bawah jika konten ada di website resmi</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_prospek_url" class="form-label">Link Prospek Kerja (Opsional)</label>
                        <input type="url" class="form-control" id="edit_prospek_url" name="prospek_url" value="<?php echo e(old('prospek_url')); ?>" placeholder="https://...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Program Studi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let editModal = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        editModal = new bootstrap.Modal(document.getElementById('editProdiModal'));
    });

    function editProdi(id, nama, fakultas, kode, deskripsi) {
        // Fetch full prodi data via AJAX to get all detail fields
        fetch(`/prodi/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(prodi => {
            const form = document.getElementById('editProdiForm');
            form.action = `/prodi/${id}`;
            
            // Basic fields
            document.getElementById('edit_nama_prodi').value = prodi.nama_prodi || '';
            document.getElementById('edit_nama_fakultas').value = prodi.nama_fakultas || '';
            document.getElementById('edit_kode_prodi').value = prodi.kode_prodi || '';
            document.getElementById('edit_deskripsi').value = prodi.deskripsi || '';
            
            // Detail fields
            document.getElementById('edit_visi_misi').value = prodi.visi_misi || '';
            document.getElementById('edit_visi_misi_url').value = prodi.visi_misi_url || '';
            document.getElementById('edit_prospek_kerja').value = prodi.prospek_kerja || '';
            document.getElementById('edit_prospek_url').value = prodi.prospek_url || '';
            
            editModal.show();
        })
        .catch(error => {
            console.error('Error fetching prodi data:', error);
            alert('Gagal memuat data program studi');
        });
    }

    // Add loading state to all forms when submitting
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                
                // Re-enable after 10s if no redirect (timeout/error case)
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }, 10000);
            }
        });
    });

    // Show any error messages in modals if they exist
    <?php if($errors->any()): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const createModal = new bootstrap.Modal(document.getElementById('createProdiModal'));
            createModal.show();
        });
        <?php if(session('editProdiId')): ?>
            // Reopen modal with the prodi ID that had validation errors
            editProdi(<?php echo e(session('editProdiId')); ?>);
        <?php endif; ?>
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\tugas_akhir\resources\views/prodi/index.blade.php ENDPATH**/ ?>
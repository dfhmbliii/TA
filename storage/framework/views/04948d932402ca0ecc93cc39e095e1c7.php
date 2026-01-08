

<?php $__env->startSection('title', 'siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <h1>Kelola Data siswa</h1>
    <p>Manajemen data siswa untuk sistem SPK</p>
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
                <i class="fas fa-user-graduate me-2"></i>
                Daftar siswa
            </h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createsiswaModal">
                <i class="fas fa-plus me-2"></i>
                Tambah siswa
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            <i class="fas fa-id-card me-2"></i>
                            NISN
                        </th>
                        <th>
                            <i class="fas fa-user me-2"></i>
                            Nama
                        </th>
                        <th>
                            <i class="fas fa-graduation-cap me-2"></i>
                            Jurusan SMA/SMK
                        </th>
                        <th>
                            <i class="fas fa-school me-2"></i>
                            Asal Sekolah
                        </th>
                        <th>
                            <i class="fas fa-calendar me-2"></i>
                            Tahun Lulus
                        </th>
                        <th>
                            <i class="fas fa-envelope me-2"></i>
                            Email
                        </th>
                        <th class="text-center">
                            <i class="fas fa-user-check me-2"></i>
                            Status Akun
                        </th>
                        <th class="text-center">
                            <i class="fas fa-cogs me-2"></i>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <span class="badge bg-primary"><?php echo e($siswa->nisn); ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                    <?php echo e(substr($siswa->name, 0, 1)); ?>

                                </div>
                                <div>
                                    <h6 class="mb-0"><?php echo e($siswa->name); ?></h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo e($siswa->jurusan_sma); ?></span>
                        </td>
                        <td><?php echo e($siswa->asal_sekolah); ?></td>
                        <td>
                            <span class="badge bg-secondary"><?php echo e($siswa->tahun_lulus); ?></span>
                        </td>
                        <td><?php echo e($siswa->email); ?></td>
                        <td class="text-center">
                            <?php
                                $user = \App\Models\User::where('email', $siswa->email)->first();
                            ?>
                            <?php if($user): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    Belum Daftar
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editsiswaModal<?php echo e($siswa->id); ?>" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewsiswaModal<?php echo e($siswa->id); ?>" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php
                                    $user = \App\Models\User::where('email', $siswa->email)->first();
                                ?>
                                <?php if($user): ?>
                                    <button class="btn btn-sm btn-outline-danger" onclick="event.preventDefault(); if(confirm('Reset password untuk <?php echo e($siswa->name); ?>?')) { document.getElementById('reset-password-<?php echo e($siswa->id); ?>').submit(); }" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <form id="reset-password-<?php echo e($siswa->id); ?>" action="<?php echo e(route('siswa.reset-password', $siswa->id)); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                <?php endif; ?>
                                <form action="<?php echo e(route('siswa.destroy', $siswa->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modals -->
<?php $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editsiswaModal<?php echo e($siswa->id); ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('siswa.update', $siswa->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nisn<?php echo e($siswa->id); ?>" class="form-label">NISN</label>
                        <input type="text" class="form-control" id="nisn<?php echo e($siswa->id); ?>" name="nisn" value="<?php echo e($siswa->nisn); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="name<?php echo e($siswa->id); ?>" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name<?php echo e($siswa->id); ?>" name="name" value="<?php echo e($siswa->name); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="jurusan_sma<?php echo e($siswa->id); ?>" class="form-label">Jurusan SMA/SMK</label>
                        <input type="text" class="form-control" id="jurusan_sma<?php echo e($siswa->id); ?>" name="jurusan_sma" value="<?php echo e($siswa->jurusan_sma); ?>" required placeholder="Contoh: IPA, IPS, Teknik Komputer">
                    </div>
                    <div class="mb-3">
                        <label for="asal_sekolah<?php echo e($siswa->id); ?>" class="form-label">Asal Sekolah</label>
                        <input type="text" class="form-control" id="asal_sekolah<?php echo e($siswa->id); ?>" name="asal_sekolah" value="<?php echo e($siswa->asal_sekolah); ?>" required placeholder="Nama sekolah lengkap">
                    </div>
                    <div class="mb-3">
                        <label for="tahun_lulus<?php echo e($siswa->id); ?>" class="form-label">Tahun Lulus</label>
                        <input type="text" class="form-control" id="tahun_lulus<?php echo e($siswa->id); ?>" name="tahun_lulus" value="<?php echo e($siswa->tahun_lulus); ?>" required placeholder="Contoh: 2023">
                    </div>
                    <div class="mb-3">
                        <label for="email<?php echo e($siswa->id); ?>" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email<?php echo e($siswa->id); ?>" name="email" value="<?php echo e($siswa->email); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update siswa</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- View Modals -->
<?php $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="viewsiswaModal<?php echo e($siswa->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">NISN</label>
                        <p><?php echo e($siswa->nisn); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama</label>
                        <p><?php echo e($siswa->name); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <p><?php echo e($siswa->email); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Jurusan SMA/SMK</label>
                        <p><?php echo e($siswa->jurusan_sma); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Asal Sekolah</label>
                        <p><?php echo e($siswa->asal_sekolah); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tahun Lulus</label>
                        <p><?php echo e($siswa->tahun_lulus); ?></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<!-- Create Modal -->
<div class="modal fade" id="createsiswaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>
                    Tambah siswa Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('siswa.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nisn" class="form-label">
                                    <i class="fas fa-id-card me-1"></i>
                                    NISN
                                </label>
                                <input type="text" class="form-control" id="nisn" name="nisn" required placeholder="Masukkan NISN">
                                <div class="form-text">Nomor Induk Siswa Nasional</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nama Lengkap
                                </label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Masukkan nama lengkap">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jurusan_sma" class="form-label">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    Jurusan SMA/SMK
                                </label>
                                <input type="text" class="form-control" id="jurusan_sma" name="jurusan_sma" required placeholder="Contoh: IPA, IPS, Teknik Komputer">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tahun_lulus" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>
                                    Tahun Lulus
                                </label>
                                <input type="text" class="form-control" id="tahun_lulus" name="tahun_lulus" required placeholder="Contoh: 2023">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="asal_sekolah" class="form-label">
                            <i class="fas fa-school me-1"></i>
                            Asal Sekolah
                        </label>
                        <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah" required placeholder="Nama sekolah lengkap">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>
                            Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="email@example.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Simpan siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tugas_akhir\resources\views/siswa/index.blade.php ENDPATH**/ ?>
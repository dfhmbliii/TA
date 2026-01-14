<?php $__env->startSection('title', $prodi->nama_prodi); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><?php echo e($prodi->nama_prodi); ?></h1>
            <p class="text-muted">
                <span class="badge bg-secondary me-2"><?php echo e($prodi->nama_fakultas); ?></span>
                <span class="badge bg-primary"><?php echo e($prodi->kode_prodi); ?></span>
            </p>
        </div>
        <a href="<?php echo e(route('prodi.index')); ?>" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs mb-4" id="prodiDetailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="ringkasan-tab" data-bs-toggle="tab" data-bs-target="#ringkasan" type="button" role="tab" aria-controls="ringkasan" aria-selected="true">
                    <i class="fas fa-info-circle me-2"></i>Ringkasan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="visi-tab" data-bs-toggle="tab" data-bs-target="#visi" type="button" role="tab" aria-controls="visi" aria-selected="false">
                    <i class="fas fa-bullseye me-2"></i>Visi & Misi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="prospek-tab" data-bs-toggle="tab" data-bs-target="#prospek" type="button" role="tab" aria-controls="prospek" aria-selected="false">
                    <i class="fas fa-briefcase me-2"></i>Prospek Kerja
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="kurikulum-tab" data-bs-toggle="tab" data-bs-target="#kurikulum" type="button" role="tab" aria-controls="kurikulum" aria-selected="false">
                    <i class="fas fa-book me-2"></i>Kurikulum
                </button>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Ringkasan Tab -->
            <div class="tab-pane fade show active" id="ringkasan" role="tabpanel">
                <h5 class="mb-3">Deskripsi Program Studi</h5>
                <?php if($prodi->deskripsi): ?>
                    <p class="text-muted"><?php echo e($prodi->deskripsi); ?></p>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Deskripsi program studi belum tersedia.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Visi & Misi Tab -->
            <div class="tab-pane fade" id="visi" role="tabpanel">
                <h5 class="mb-3">Visi & Misi</h5>
                <?php if($prodi->visi_misi): ?>
                    <div class="content-text">
                        <?php echo nl2br(e($prodi->visi_misi)); ?>

                    </div>
                <?php elseif($prodi->visi_misi_url): ?>
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-external-link-alt me-2"></i>
                        Visi & Misi tersedia di website resmi program studi.
                    </div>
                    <a href="<?php echo e($prodi->visi_misi_url); ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-external-link-alt me-2"></i>Lihat Visi & Misi
                    </a>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Visi & Misi belum tersedia.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Prospek Kerja Tab -->
            <div class="tab-pane fade" id="prospek" role="tabpanel">
                <h5 class="mb-3">Prospek Kerja</h5>
                <?php if($prodi->prospek_kerja): ?>
                    <div class="content-text">
                        <?php echo nl2br(e($prodi->prospek_kerja)); ?>

                    </div>
                <?php elseif($prodi->prospek_url): ?>
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-external-link-alt me-2"></i>
                        Informasi prospek kerja tersedia di website resmi program studi.
                    </div>
                    <a href="<?php echo e($prodi->prospek_url); ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-external-link-alt me-2"></i>Lihat Prospek Kerja
                    </a>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Informasi prospek kerja belum tersedia.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Kurikulum Tab -->
            <div class="tab-pane fade" id="kurikulum" role="tabpanel">
                <h5 class="mb-3">
                    <i class="fas fa-book-open me-2"></i>
                    Struktur Kurikulum
                </h5>

                <?php if($prodi->kurikulum_data && count($prodi->kurikulum_data) > 0): ?>
                    <!-- Summary Info -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                                    <h3 class="mb-0"><?php echo e($prodi->jumlah_semester ?? 8); ?></h3>
                                    <small class="text-muted">Semester</small>
                                </div>
                            </div>
                        </div>
                        <?php
                            $calculatedTotalSks = collect($prodi->kurikulum_data ?? [])->sum('sks');
                        ?>
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-graduation-cap fa-2x text-success mb-2"></i>
                                    <h3 class="mb-0"><?php echo e($calculatedTotalSks ?: ($prodi->total_sks ?? '-')); ?></h3>
                                    <small class="text-muted d-block">Total SKS</small>
                                    <small class="text-muted">Dihitung dari data kurikulum</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fa-2x text-info mb-2"></i>
                                    <h3 class="mb-0"><?php echo e(count($prodi->kurikulum_data)); ?></h3>
                                    <small class="text-muted">Mata Kuliah</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter by Semester -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-filter me-2"></i>Filter Semester:
                        </label>
                        <div class="btn-group flex-wrap" role="group">
                            <button class="btn btn-sm btn-primary active" data-semester="all">Semua</button>
                            <?php for($i = 1; $i <= ($prodi->jumlah_semester ?? 8); $i++): ?>
                                <button class="btn btn-sm btn-outline-primary" data-semester="<?php echo e($i); ?>">Semester <?php echo e($i); ?></button>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Kurikulum Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th width="10%" class="text-center">Semester</th>
                                    <th width="15%">Kode MK</th>
                                    <th width="35%">Nama Mata Kuliah</th>
                                    <th width="15%">Praktikum</th>
                                    <th width="10%" class="text-center">SKS</th>
                                    <th width="15%">Kategori</th>
                                </tr>
                            </thead>
                            <tbody id="kurikulumTableBody">
                                <?php $__currentLoopData = $prodi->kurikulum_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-semester="<?php echo e($mk['semester'] ?? 1); ?>">
                                        <td class="text-center">
                                            <span class="badge bg-primary"><?php echo e($mk['semester'] ?? 1); ?></span>
                                        </td>
                                        <td>
                                            <code><?php echo e($mk['kode'] ?? '-'); ?></code>
                                        </td>
                                        <td>
                                            <strong><?php echo e($mk['nama'] ?? '-'); ?></strong>
                                            <?php if(isset($mk['prasyarat']) && $mk['prasyarat']): ?>
                                                <br><small class="text-muted">
                                                    <i class="fas fa-link me-1"></i>Prasyarat: <?php echo e($mk['prasyarat']); ?>

                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($mk['praktikum'] ?? '-'); ?>

                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success"><?php echo e($mk['sks'] ?? 0); ?> SKS</span>
                                        </td>
                                        <td>
                                            <?php
                                                $kategoriConfig = [
                                                    'wajib' => ['label' => 'Wajib', 'bg' => 'danger'],
                                                    'pilihan' => ['label' => 'Pilihan', 'bg' => 'warning'],
                                                    'mkwu' => ['label' => 'MKWU', 'bg' => 'info'],
                                                ];
                                                $kat = strtolower($mk['kategori'] ?? 'wajib');
                                                $config = $kategoriConfig[$kat] ?? ['label' => ucfirst($kat), 'bg' => 'secondary'];
                                            ?>
                                            <span class="badge bg-<?php echo e($config['bg']); ?>"><?php echo e($config['label']); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($prodi->kurikulum_url): ?>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Untuk informasi kurikulum lengkap dan terbaru, kunjungi
                            <a href="<?php echo e($prodi->kurikulum_url); ?>" target="_blank" class="alert-link">
                                website resmi program studi <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Informasi kurikulum belum tersedia.
                        <?php if($prodi->kurikulum_url): ?>
                            <a href="<?php echo e($prodi->kurikulum_url); ?>" target="_blank" class="alert-link">
                                Lihat di website resmi <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.content-text {
    line-height: 1.8;
    font-size: 1rem;
}
.nav-tabs .nav-link {
    color: #6c757d;
}
.nav-tabs .nav-link.active {
    color: var(--primary-color, #4f46e5);
    font-weight: 600;
}
.nav-tabs .nav-link:hover {
    color: var(--primary-color, #4f46e5);
}
</style>

<script>
// Initialize Bootstrap tabs
document.addEventListener('DOMContentLoaded', function() {
    const triggerTabList = document.querySelectorAll('#prodiDetailTabs button');
    triggerTabList.forEach(function(triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl);

        triggerEl.addEventListener('click', function(event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });

    // Semester filter for curriculum table
    const semesterButtons = document.querySelectorAll('[data-semester]');
    const tableRows = document.querySelectorAll('#kurikulumTableBody tr[data-semester]');

    if(semesterButtons.length > 0 && tableRows.length > 0) {
        semesterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const semester = this.getAttribute('data-semester');

                // Update button active state
                semesterButtons.forEach(btn => {
                    btn.classList.remove('active', 'btn-primary');
                    btn.classList.add('btn-outline-primary');
                });
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary', 'active');

                // Filter table rows
                tableRows.forEach(row => {
                    const rowSemester = row.getAttribute('data-semester');
                    if(semester === 'all' || rowSemester === semester) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\tugas_akhir\resources\views/prodi/show.blade.php ENDPATH**/ ?>
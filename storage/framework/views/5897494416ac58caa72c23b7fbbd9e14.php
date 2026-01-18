<?php $__env->startSection('title', 'Riwayat Analisis SPK'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <h1>Riwayat Analisis SPK</h1>
    <p>Lihat semua hasil analisis SPK yang pernah dilakukan</p>
</div>

<?php if($spkResults->count() > 0): ?>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-history me-2"></i>
            Daftar Riwayat Analisis
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Total Skor</th>
                        <th>Kategori</th>
                        <th>Kriteria</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $spkResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(($spkResults->currentPage() - 1) * $spkResults->perPage() + $index + 1); ?></td>
                        <td>
                            <i class="fas fa-calendar-alt me-2 text-muted"></i>
                            <?php echo e($result->created_at->format('d M Y, H:i')); ?>

                        </td>
                        <td>
                            <strong class="text-primary"><?php echo e(number_format($result->total_score, 2)); ?></strong>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($result->total_score >= 35 ? 'success' : 
                                ($result->total_score >= 28 ? 'primary' : 
                                ($result->total_score >= 20 ? 'info' : 'warning'))); ?>">
                                <?php echo e($result->category); ?>

                            </span>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?php
                                    $input = is_array($result->input_data) ? $result->input_data : json_decode($result->input_data, true);
                                ?>
                                <?php if($input && is_array($input)): ?>
                                    Minat: <?php echo e(ucwords(str_replace('_', ' ', $input['minat'] ?? '-'))); ?>, 
                                    Bakat: <?php echo e(ucwords(str_replace('_', ' ', $input['bakat'] ?? '-'))); ?>

                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </small>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" data-detail-btn 
                                data-result-id="<?php echo e($result->id); ?>" 
                                data-total-score="<?php echo e($result->total_score); ?>" 
                                data-category="<?php echo e($result->category); ?>" 
                                data-created="<?php echo e($result->created_at->format('d F Y, H:i:s')); ?>">
                                <i class="fas fa-eye me-1"></i>
                                Detail
                            </button>
                            <a href="<?php echo e(route('spk.view-pdf', $result->id)); ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-file-pdf me-1"></i>
                                View
                            </a>
                            <a href="<?php echo e(route('spk.export-pdf', $result->id)); ?>" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-download me-1"></i>
                                Download
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($spkResults->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>

<!-- Single Detail Modal (Outside of loop) -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-chart-bar me-2"></i>Detail Analisis SPK</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                
                <!-- Summary Card -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="text-muted mb-3"><i class="fas fa-calendar me-2"></i>Tanggal Analisis</h6>
                                <p id="modalCreatedDate" class="mb-4 fs-6">-</p>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Total Skor</h6>
                                        <h3 class="text-success mb-0" id="modalTotalScore">-</h3>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Kategori</h6>
                                        <div id="modalCategory">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="fas fa-chart-pie text-primary" style="font-size: 4rem; opacity: 0.15;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Input Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Data Input Anda</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div>
                                    <small class="text-muted d-block mb-2">Minat</small>
                                    <span class="badge bg-danger fs-6" id="modalInputMinat">-</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div>
                                    <small class="text-muted d-block mb-2">Bakat</small>
                                    <span class="badge bg-warning text-dark fs-6" id="modalInputBakat">-</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div>
                                    <small class="text-muted d-block mb-2">Prospek Karir</small>
                                    <span class="badge bg-success fs-6" id="modalInputKarir">-</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div>
                                    <small class="text-muted d-block mb-2">Nilai Rata-rata</small>
                                    <span class="badge bg-info fs-6" id="modalInputNilai">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi Prodi Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Rekomendasi Program Studi</h6>
                    </div>
                    <div class="card-body" id="modalRekomendasiProdi">
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-spinner fa-spin me-2"></i>Memuat rekomendasi...
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-detail-btn]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            try {
                const resultId = this.getAttribute('data-result-id');
                const totalScore = this.getAttribute('data-total-score');
                const category = this.getAttribute('data-category');
                const created = this.getAttribute('data-created');
                
                // Update modal content
                document.getElementById('modalCreatedDate').textContent = created;
                document.getElementById('modalTotalScore').textContent = parseFloat(totalScore).toFixed(2);
                
                // Category badge with better styling
                const categoryBadge = `<span class="badge bg-${
                    category === 'Sangat Sesuai' ? 'success' : 
                    (category === 'Sesuai' ? 'primary' : 
                    (category === 'Cukup Sesuai' ? 'info' : 'warning'))
                } fs-6 px-3 py-2">${category}</span>`;
                document.getElementById('modalCategory').innerHTML = categoryBadge;
                
                // Show modal first
                const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                modal.show();
                
                // Fetch detail data (input + rekomendasi) from server
                const rekomendasiContainer = document.getElementById('modalRekomendasiProdi');
                rekomendasiContainer.innerHTML = `
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-spinner fa-spin me-2"></i>Memuat data...
                    </div>
                `;
                
                // Fetch detail analysis
                fetch(`/spk/result/${resultId}/detail`)
                    .then(response => {
                        console.log('Detail response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Detail response data:', data);
                        if (data.success && data.data && data.data.input_data) {
                            const inputData = data.data.input_data;
                            console.log('Input data from server:', inputData);
                            
                            // Populate input data dengan badge
                            if (inputData && typeof inputData === 'object' && Object.keys(inputData).length > 0) {
                                const minatText = inputData['minat'] ? 
                                    inputData['minat'].charAt(0).toUpperCase() + inputData['minat'].slice(1).replace(/_/g, ' ') : '-';
                                document.getElementById('modalInputMinat').textContent = minatText;
                                console.log('Set minat to:', minatText);
                                
                                const bakatText = inputData['bakat'] ? 
                                    inputData['bakat'].charAt(0).toUpperCase() + inputData['bakat'].slice(1).replace(/_/g, ' ') : '-';
                                document.getElementById('modalInputBakat').textContent = bakatText;
                                console.log('Set bakat to:', bakatText);
                                
                                const karirText = inputData['prospek_karir'] ? 
                                    inputData['prospek_karir'].charAt(0).toUpperCase() + inputData['prospek_karir'].slice(1).replace(/_/g, ' ') : '-';
                                document.getElementById('modalInputKarir').textContent = karirText;
                                console.log('Set karir to:', karirText);
                                
                                // Calculate average nilai_mapel
                                if (inputData['nilai_mapel'] && typeof inputData['nilai_mapel'] === 'object') {
                                    const nilaiArray = Object.values(inputData['nilai_mapel'])
                                        .map(n => parseFloat(n))
                                        .filter(n => !isNaN(n));
                                    
                                    if (nilaiArray.length > 0) {
                                        const avgNilai = nilaiArray.reduce((a, b) => a + b, 0) / nilaiArray.length;
                                        document.getElementById('modalInputNilai').textContent = avgNilai.toFixed(2);
                                        console.log('Set nilai to:', avgNilai.toFixed(2));
                                    } else {
                                        document.getElementById('modalInputNilai').textContent = '-';
                                    }
                                } else {
                                    document.getElementById('modalInputNilai').textContent = '-';
                                }
                            } else {
                                console.log('Input data is empty or not an object');
                                document.getElementById('modalInputMinat').textContent = '-';
                                document.getElementById('modalInputBakat').textContent = '-';
                                document.getElementById('modalInputKarir').textContent = '-';
                                document.getElementById('modalInputNilai').textContent = '-';
                            }
                        } else {
                            console.error('Failed to fetch detail data:', data);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching detail:', error);
                    });
                
                // Fetch rekomendasi prodi
                fetch(`/spk/result/${resultId}/rekomendasi`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.rekomendasi && data.rekomendasi.length > 0) {
                            let html = '<div class="list-group list-group-flush">';
                            data.rekomendasi.forEach((prodi, index) => {
                                html += `
                                    <div class="list-group-item px-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="badge bg-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                                    ${index + 1}
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">${prodi.nama_prodi || prodi.name || 'N/A'}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-chart-line me-1"></i>
                                                    Skor: <strong>${prodi.score ? parseFloat(prodi.score).toFixed(2) : 'N/A'}</strong>
                                                    ${prodi.percentage ? `(${parseFloat(prodi.percentage).toFixed(1)}%)` : ''}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            html += '</div>';
                            rekomendasiContainer.innerHTML = html;
                        } else {
                            rekomendasiContainer.innerHTML = `
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <p class="mb-0">Tidak ada rekomendasi program studi tersedia</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching rekomendasi:', error);
                        rekomendasiContainer.innerHTML = `
                            <div class="text-center text-danger py-4">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                <p class="mb-0">Gagal memuat rekomendasi program studi</p>
                            </div>
                        `;
                    });
                
            } catch (error) {
                console.error('Error showing detail modal:', error);
                alert('Gagal menampilkan detail analisis');
            }
        });
    });
});
</script>
<?php else: ?>
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-history fa-4x text-muted mb-3"></i>
        <h5>Belum Ada Riwayat Analisis</h5>
        <p class="text-muted mb-4">Anda belum pernah melakukan analisis SPK</p>
        <a href="<?php echo e(route('siswa-spk.form')); ?>" class="btn btn-primary">
            <i class="fas fa-play me-2"></i>
            Mulai Analisis Sekarang
        </a>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\tugas_akhir\resources\views/siswa/history.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Laporan Analisis SPK'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i>Laporan Analisis SPK</h4>
        <div>
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Cetak
            </button>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form id="filterForm" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Siswa/Mahasiswa</label>
                    <select class="form-select" name="siswa_id" id="siswaFilter">
                        <option value="">Semua Siswa</option>
                        <?php $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?> (<?php echo e($s->nisn); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Bulan</label>
                    <input type="month" class="form-control" name="from_date" id="fromDate">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Bulan</label>
                    <input type="month" class="form-control" name="to_date" id="toDate">
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="analysisTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Siswa</th>
                            <th width="15%">NISN</th>
                            <th width="12%">Tanggal</th>
                            <th class="text-center" width="10%">Total Skor</th>
                            <th class="text-center" width="12%">Kategori</th>
                            <th width="18%">Rekomendasi</th>
                            <th class="text-center" width="13%">Detail</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="8" class="text-center text-muted">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Analisis SPK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <small class="text-muted d-block">Nama Siswa</small>
                            <strong id="modalSiswa">-</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <small class="text-muted d-block">Tanggal Analisis</small>
                            <strong id="modalDate">-</strong>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label fw-bold">Data Input</label>
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Minat</small>
                            <p id="modalInputMinat" class="mb-0">-</p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Bakat</small>
                            <p id="modalInputBakat" class="mb-0">-</p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Prospek Karir</small>
                            <p id="modalInputKarir" class="mb-0">-</p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Nilai Rata-rata</small>
                            <p id="modalInputNilai" class="mb-0">-</p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label fw-bold">Hasil Analisis</label>
                    <div class="row">
                        <div class="col-md-8">
                            <small class="text-muted d-block">Skor & Kategori</small>
                            <div>
                                <h5 class="mb-1" id="modalScore">0.00</h5>
                                <span class="badge" id="modalCategory">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label fw-bold">Breakdown Skor per Kriteria</label>
                    <div class="row" id="criteriaBreakdown">
                        <p class="text-muted">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const tableBody = document.getElementById('tableBody');
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));

    async function loadData() {
        const siswaId = document.getElementById('siswaFilter').value;
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;

        try {
            const params = new URLSearchParams();
            if (siswaId) params.append('siswa_id', siswaId);
            if (fromDate) params.append('from_date', fromDate);
            if (toDate) params.append('to_date', toDate);

            const response = await fetch(`<?php echo e(route('reports.spk-analysis.data')); ?>?${params}`);
            const data = await response.json();

            renderTable(data.results);
        } catch (error) {
            console.error('Error loading data:', error);
            tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Gagal memuat data</td></tr>';
        }
    }

    function renderTable(results) {
        if (!results || results.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Tidak ada data</td></tr>';
            return;
        }

        tableBody.innerHTML = results.map((item, idx) => `
            <tr>
                <td>${idx + 1}</td>
                <td>${item.siswa_name}</td>
                <td>${item.nisn}</td>
                <td>${item.created_at}</td>
                <td class="text-center"><strong>${item.total_score}</strong></td>
                <td class="text-center">
                    <span class="badge bg-${
                        item.total_score >= 35 ? 'success' : 
                        (item.total_score >= 28 ? 'primary' : 
                        (item.total_score >= 20 ? 'info' : 'warning'))
                    }">
                        ${item.category}
                    </span>
                </td>
                <td>${item.rekomendasi_prodi}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary" onclick="showDetail(${item.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    window.showDetail = async function(resultId) {
        try {
            const response = await fetch(`<?php echo e(route('reports.spk-analysis.detail', ':id')); ?>`.replace(':id', resultId));
            const item = await response.json();

            // Parse input data dengan safe check
            let inputData = item.input_data;
            if (typeof inputData === 'string') {
                try {
                    inputData = JSON.parse(inputData);
                } catch (e) {
                    console.error('Failed to parse input_data:', e);
                    inputData = {};
                }
            }
            
            let criteria = item.criteria_values;
            if (typeof criteria === 'string') {
                try {
                    criteria = JSON.parse(criteria);
                } catch (e) {
                    criteria = {};
                }
            }
            
            let weights = item.weights;
            if (typeof weights === 'string') {
                try {
                    weights = JSON.parse(weights);
                } catch (e) {
                    weights = {};
                }
            }

            // Calculate average nilai_mapel
            let avgNilai = '-';
            if (inputData && inputData.nilai_mapel && Array.isArray(inputData.nilai_mapel)) {
                const nilaiArray = inputData.nilai_mapel.map(n => parseFloat(n)).filter(n => !isNaN(n));
                if (nilaiArray.length > 0) {
                    avgNilai = (nilaiArray.reduce((a, b) => a + b, 0) / nilaiArray.length).toFixed(2);
                }
            }

            console.log('Input Data received:', inputData); // Debug log

            // Update modal
            document.getElementById('modalSiswa').textContent = item.siswa_name;
            document.getElementById('modalDate').textContent = item.created_at;
            
            // Set input data
            document.getElementById('modalInputMinat').textContent = (inputData?.minat) ? 
                inputData.minat.charAt(0).toUpperCase() + inputData.minat.slice(1).replace(/_/g, ' ') : '-';
            document.getElementById('modalInputBakat').textContent = (inputData?.bakat) ? 
                inputData.bakat.charAt(0).toUpperCase() + inputData.bakat.slice(1).replace(/_/g, ' ') : '-';
            document.getElementById('modalInputKarir').textContent = (inputData?.prospek_karir) ? 
                inputData.prospek_karir.charAt(0).toUpperCase() + inputData.prospek_karir.slice(1).replace(/_/g, ' ') : '-';
            document.getElementById('modalInputNilai').textContent = avgNilai;
            
            document.getElementById('modalScore').textContent = item.total_score;
            
            const categoryBadge = document.getElementById('modalCategory');
            categoryBadge.className = `badge bg-${
                item.total_score >= 35 ? 'success' : 
                (item.total_score >= 28 ? 'primary' : 
                (item.total_score >= 20 ? 'info' : 'warning'))
            }`;
            categoryBadge.textContent = item.category;

            // Render criteria breakdown
            const breakdown = document.getElementById('criteriaBreakdown');
            if (criteria && Object.keys(criteria).length > 0) {
                breakdown.innerHTML = Object.entries(criteria).map(([key, value]) => {
                    const weight = weights[key] || 0;
                    return `
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <small class="text-muted d-block mb-1">${key.replace(/_/g, ' ').toUpperCase()}</small>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">${value}</h6>
                                        <span class="badge bg-primary">${weight}%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" style="width: ${weight}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            modal.show();
        } catch (error) {
            console.error('Error loading detail:', error);
            alert('Gagal memuat detail analisis');
        }
    };

    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        loadData();
    });

    // Initial load
    loadData();
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @media print {
        .btn, .card-body form, .modal {
            display: none !important;
        }
        .table {
            font-size: 12px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\tugas_akhir\resources\views/reports/spk-analysis.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Laporan Analisis SPK'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3 d-print-none">
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
            document.getElementById('modalInputMinat').textContent = inputData?.minat_text || '-';
            document.getElementById('modalInputBakat').textContent = inputData?.bakat_text || '-';
            document.getElementById('modalInputKarir').textContent = inputData?.karir_text || '-';
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
        @page {
            size: landscape;
            margin: 1cm;
        }

        body {
            background-color: white !important;
            font-size: 11pt;
            color: black !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Hide unnecessary elements */
        .sidebar, 
        .header, 
        .navbar, 
        .btn, 
        .card-header, 
        form, 
        .modal, 
        #filterForm, 
        .d-print-none,
        .content-header {
            display: none !important;
        }

        /* Adjust content width */
        .main-content, .content, .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Card styling reset for print */
        .card {
            border: none !important;
            box-shadow: none !important;
            background: none !important;
        }

        .card-body {
            padding: 0 !important;
        }

        /* Table styling */
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 10pt;
            margin-top: 20px;
        }

        .table th, .table td {
            border: 1px solid #000 !important;
            padding: 8px !important;
            color: black !important;
        }

        .table thead th {
            background-color: #f0f0f0 !important;
            font-weight: bold;
            text-align: center;
        }

        /* Badges to text */
        .badge {
            border: 1px solid #000;
            color: black !important;
            background: none !important;
            font-weight: normal;
            padding: 2px 6px;
        }

        /* Show print header */
        .d-print-block {
            display: block !important;
        }

        /* Hide Detail column in print */
        .table th:last-child, 
        .table td:last-child {
            display: none;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<!-- Print Header (Hidden on Screen) -->
<div class="d-none d-print-block mb-4">
    <div class="row align-items-center border-bottom pb-3 mb-3" style="border-bottom: 3px double black !important;">
        <div class="col-2 text-center">
            <!-- Use absolute path or base64 for best print result, but asset usually works in browser print -->
            <img src="<?php echo e(asset('images/Pilihanku3.png')); ?>" alt="Logo" style="width: 80px; height: auto;">
        </div>
        <div class="col-8 text-center">
            <h3 class="mb-0 fw-bold text-uppercase" style="font-size: 18pt; letter-spacing: 1px;">Sistem Pendukung Keputusan (SPK)</h3>
            <h4 class="mb-1 fw-bold" style="font-size: 16pt;">PEMILIHAN PROGRAM STUDI</h4>
            <div class="small">
                Alamat: Jl. Jend. Sudirman No. 123, Jakarta Pusat<br>
                Telp: (021) 1234567 | Email: info@pilihanku.sch.id
            </div>
        </div>
        <div class="col-2"></div>
    </div>
    
    <div class="text-center mb-4">
        <h5 class="fw-bold text-decoration-underline">LAPORAN HASIL ANALISIS SPK</h5>
        <p class="mb-0">Periode: <?php echo e(request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('F Y') : 'Semua'); ?> s/d <?php echo e(request('to_date') ? \Carbon\Carbon::parse(request('to_date'))->format('F Y') : 'Sekarang'); ?></p>
    </div>
</div>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\tugas_akhir\resources\views/reports/spk-analysis.blade.php ENDPATH**/ ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Laporan siswa per Prodi per Tahun</h4>
        <div>
            <a href="<?php echo e(route('reports.prodi.export')); ?>" class="btn btn-outline-secondary btn-sm" id="exportCsvBtn">Export CSV</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form id="filterForm" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Dari Tahun</label>
                    <select class="form-select" name="from" id="fromYear">
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($y); ?>" <?php if($y==($years[count($years)-min(5,count($years))]??$y)): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tahun</label>
                    <select class="form-select" name="to" id="toYear">
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($y); ?>" <?php if($y==end($years)): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Prodi (opsional)</label>
                    <select class="form-select" name="prodi_id" id="prodiFilter">
                        <option value="">Semua Prodi</option>
                        <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p->id); ?>"><?php echo e($p->nama_prodi); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2 form-check mt-4 ms-2">
                    <input class="form-check-input" type="checkbox" id="includeEmpty" name="includeEmpty" checked>
                    <label class="form-check-label" for="includeEmpty">Tampilkan Prodi Kosong</label>
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <canvas id="prodiChart" height="120"></canvas>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
    const ctx = document.getElementById('prodiChart').getContext('2d');
    let chart;

    function randomColor(i){
        const colors = [
            '#4e79a7','#f28e2b','#e15759','#76b7b2','#59a14f','#edc948','#b07aa1','#ff9da7','#9c755f','#bab0ac'
        ];
        return colors[i % colors.length];
    }

    function buildChart(years, series){
        const datasets = series.map((s, idx) => ({
            label: s.label,
            data: s.data,
            borderColor: randomColor(idx),
            backgroundColor: randomColor(idx)+'33',
            tension: 0.2,
        }));
        const data = { labels: years, datasets };
        const options = {
            responsive: true,
            interaction: { mode: 'nearest', intersect: false },
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: (ctx)=> `${ctx.dataset.label}: ${ctx.formattedValue}` } }
            },
            scales: { y: { beginAtZero: true, ticks: { precision:0 } } }
        };
        if (chart) { chart.destroy(); }
        chart = new Chart(ctx, { type: 'line', data, options });
    }

    async function fetchData(params){
        const url = new URL("<?php echo e(route('reports.prodi.data')); ?>", window.location.origin);
        Object.entries(params).forEach(([k,v])=>{ if(v!=='' && v!=null){ url.searchParams.set(k,v); } });
        const res = await fetch(url);
        if(!res.ok){ throw new Error('Gagal memuat data'); }
        return res.json();
    }

    async function refresh(){
        const params = {
            from: document.getElementById('fromYear').value,
            to: document.getElementById('toYear').value,
            prodi_id: document.getElementById('prodiFilter').value,
            includeEmpty: document.getElementById('includeEmpty').checked
        };
        const json = await fetchData(params);
        buildChart(json.years, json.series);
        // update export link
        const exportUrl = new URL("<?php echo e(route('reports.prodi.export')); ?>", window.location.origin);
        Object.entries(params).forEach(([k,v])=>{ if(v!=='' && v!=null){ exportUrl.searchParams.set(k,v); } });
        document.getElementById('exportCsvBtn').href = exportUrl.toString();
    }

    document.getElementById('filterForm').addEventListener('submit', function(e){ e.preventDefault(); refresh(); });

    // initial
    refresh();
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\tugas_akhir\resources\views/reports/prodi.blade.php ENDPATH**/ ?>
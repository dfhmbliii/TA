@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Laporan siswa per Prodi per Tahun</h4>
        <div>
            <a href="{{ route('reports.prodi.export') }}" class="btn btn-outline-secondary btn-sm" id="exportCsvBtn">Export CSV</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form id="filterForm" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Dari Tahun</label>
                    <select class="form-select" name="from" id="fromYear">
                        @foreach ($years as $y)
                        <option value="{{ $y }}" @selected($y==($years[count($years)-min(5,count($years))]??$y))>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tahun</label>
                    <select class="form-select" name="to" id="toYear">
                        @foreach ($years as $y)
                        <option value="{{ $y }}" @selected($y==end($years))>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Prodi (opsional)</label>
                    <select class="form-select" name="prodi_id" id="prodiFilter">
                        <option value="">Semua Prodi</option>
                        @foreach ($prodis as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                        @endforeach
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
            <canvas id="prodiChart" height="80"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const canvas = document.getElementById('prodiChart');
    if (!canvas) {
        console.error('Canvas element not found');
        return;
    }
    const ctx = canvas.getContext('2d');
    let chart;

    function buildBarChart(series){
        // Group totals per prodi (registered vs recommendation), same as dashboard
        const prodiData = {};

        series.forEach(item => {
            const prodiName = item.label
                .replace(' (Terdaftar)', '')
                .replace(' (Rekomendasi SPK)', '');

            if (!prodiData[prodiName]) {
                prodiData[prodiName] = { registered: 0, recommended: 0 };
            }

            const total = item.data.reduce((a, b) => a + b, 0);
            if (item.type === 'registered') {
                prodiData[prodiName].registered = total;
            } else if (item.type === 'recommendation') {
                prodiData[prodiName].recommended = total;
            }
        });

        const labels = Object.keys(prodiData);
        const registeredData = labels.map(label => prodiData[label].registered);
        const recommendedData = labels.map(label => prodiData[label].recommended);

        const data = {
            labels,
            datasets: [
                {
                    label: 'Siswa Terdaftar',
                    data: registeredData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Rekomendasi SPK',
                    data: recommendedData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        };

        if (chart) { chart.destroy(); }
        chart = new Chart(ctx, { type: 'bar', data, options });
    }

    async function fetchData(params){
        const url = new URL("{{ route('reports.prodi.data') }}", window.location.origin);
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
        try {
            const json = await fetchData(params);
            buildBarChart(json.series);
        } catch(error) {
            console.error('Error loading chart:', error);
            document.querySelector('#prodiChart').parentElement.innerHTML = '<div class="alert alert-danger">Gagal memuat grafik</div>';
        }
        const exportUrl = new URL("{{ route('reports.prodi.export') }}", window.location.origin);
        Object.entries(params).forEach(([k,v])=>{ if(v!=='' && v!=null){ exportUrl.searchParams.set(k,v); } });
        document.getElementById('exportCsvBtn').href = exportUrl.toString();
    }

    document.getElementById('filterForm').addEventListener('submit', function(e){ e.preventDefault(); refresh(); });
    refresh();
});
</script>
@endpush

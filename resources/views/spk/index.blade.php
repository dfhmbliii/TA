@extends('layouts.app')

@section('title', 'SPK Analysis')

@section('content')
<div class="content-header">
    <h1>Analisis SPK</h1>
    <p>Sistem Pendukung Keputusan untuk Seleksi dan Evaluasi siswa</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Konfigurasi Kriteria SPK
                </h5>
            </div>
            <div class="card-body">
                <form id="spkForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    IPK (Indeks Prestasi Kumulatif)
                                </label>
                                <div class="input-group">
                                    <input type="range" class="form-range" id="ipkWeight" min="0" max="100" value="30">
                                    <span class="input-group-text" id="ipkValue">30%</span>
                                </div>
                                <small class="text-muted">Bobot untuk nilai IPK</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-trophy me-1"></i>
                                    Prestasi Akademik
                                </label>
                                <div class="input-group">
                                    <input type="range" class="form-range" id="prestasiWeight" min="0" max="100" value="25">
                                    <span class="input-group-text" id="prestasiValue">25%</span>
                                </div>
                                <small class="text-muted">Bobot untuk prestasi akademik</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-users me-1"></i>
                                    Kepemimpinan
                                </label>
                                <div class="input-group">
                                    <input type="range" class="form-range" id="kepemimpinanWeight" min="0" max="100" value="20">
                                    <span class="input-group-text" id="kepemimpinanValue">20%</span>
                                </div>
                                <small class="text-muted">Bobot untuk kemampuan kepemimpinan</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-heart me-1"></i>
                                    Aktivitas Sosial
                                </label>
                                <div class="input-group">
                                    <input type="range" class="form-range" id="sosialWeight" min="0" max="100" value="15">
                                    <span class="input-group-text" id="sosialValue">15%</span>
                                </div>
                                <small class="text-muted">Bobot untuk aktivitas sosial</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-comments me-1"></i>
                                    Kemampuan Komunikasi
                                </label>
                                <div class="input-group">
                                    <input type="range" class="form-range" id="komunikasiWeight" min="0" max="100" value="10">
                                    <span class="input-group-text" id="komunikasiValue">10%</span>
                                </div>
                                <small class="text-muted">Bobot untuk kemampuan komunikasi</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Kreativitas
                                </label>
                                <div class="input-group">
                                    <input type="range" class="form-range" id="kreativitasWeight" min="0" max="100" value="0">
                                    <span class="input-group-text" id="kreativitasValue">0%</span>
                                </div>
                                <small class="text-muted">Bobot untuk kreativitas</small>
                            </div>
                        </div>
                    </div>

                    <!-- Kriteria menus: Minat, Bakat, Program Studi, Mata Pelajaran Relevan, Prospek Karir -->
                    <div class="mt-4">
                        <h6 class="mb-3"><i class="fas fa-list me-2"></i>Menu Kriteria</h6>

                        <div class="mb-3">
                            <label for="minat" class="form-label">Minat (pilih satu atau beberapa)</label>
                            <select id="minat" class="form-select" multiple>
                                <option value="proses_bisnis">Proses Bisnis</option>
                                <option value="pemrograman">Pemrograman</option>
                                <option value="seni_estetika">Seni dan Estetika Visual</option>
                                <option value="fisika_matematika">Penerapan Fisika dan Matematika</option>
                            </select>
                            <div class="form-text">Pilih minat yang relevan dengan calon siswa.</div>
                        </div>

                        <div class="mb-3">
                            <label for="bakat" class="form-label">Bakat (pilih satu atau beberapa)</label>
                            <select id="bakat" class="form-select" multiple>
                                <option value="problem_solving">Kemampuan memecahkan masalah (problem-solving) dari sudut pandang bisnis</option>
                                <option value="debugging">Memiliki ketekunan dan ketelitian tinggi dalam mencari kesalahan teknis (debugging)</option>
                                <option value="elektronik">Kemampuan menganalisis rangkaian elektronik dan pemrosesan sinyal</option>
                                <option value="kepekaan_visual">Kepekaan visual (rasa estetika) yang baik terhadap komposisi, warna, dan tipografi</option>
                            </select>
                            <div class="form-text">Pilih bakat yang menonjol pada peserta.</div>
                        </div>

                        <div class="mb-3">
                            <label for="mata_pelajaran" class="form-label">Mata Pelajaran Relevan</label>
                            <select id="mata_pelajaran" class="form-select" multiple>
                                <option value="bahasa_inggris">Bahasa Inggris</option>
                                <option value="informatika">Informatika</option>
                                <option value="matematika">Matematika</option>
                                <option value="seni_budaya">Seni Budaya</option>
                            </select>
                            <div class="form-text">Pilih mata pelajaran yang relevan dengan latar belakang peserta.</div>
                        </div>

                        <div class="mb-3">
                            <label for="program_studi" class="form-label">Program Studi (pilih satu)</label>
                            <select id="program_studi" class="form-select">
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Pilih program studi tujuan yang relevan.</div>
                        </div>

                        <div class="mb-3">
                            <label for="prospek_karir" class="form-label">Prospek Karir (pilih satu atau beberapa)</label>
                            <select id="prospek_karir" class="form-select" multiple>
                                <option value="system_analyst">System Analyst</option>
                                <option value="software_developer">Software Developer/Engineer</option>
                                <option value="network_engineer">Network Engineer</option>
                                <option value="graphic_designer">Graphic Designer</option>
                            </select>
                            <div class="form-text">Pilih prospek karir yang menjadi target penempatan.</div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Total Bobot:</strong> <span id="totalWeight">100%</span>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar" id="weightProgress" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Visualisasi Bobot
                </h5>
            </div>
            <div class="card-body">
                <canvas id="weightChart" width="300" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list-ol me-2"></i>
                        Hasil Ranking SPK
                    </h5>
                    <div class="btn-group">
                        <button class="btn btn-primary" onclick="calculateSPK()">
                            <i class="fas fa-calculator me-2"></i>
                            Hitung SPK
                        </button>
                        <button class="btn btn-success" onclick="exportResults()">
                            <i class="fas fa-download me-2"></i>
                            Export Hasil
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="rankingTable">
                        <thead>
                            <tr>
                                <th>Ranking</th>
                                <th>Nama siswa</th>
                                <th>Program Studi</th>
                                <th>IPK</th>
                                <th>Prestasi</th>
                                <th>Kepemimpinan</th>
                                <th>Sosial</th>
                                <th>Komunikasi</th>
                                <th>Kreativitas</th>
                                <th>Total Score</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="fas fa-calculator fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">Klik "Hitung SPK" untuk melihat hasil analisis</h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SPK Method Modal -->
<div class="modal fade" id="spkMethodModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Metode SPK yang Digunakan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Weighted Sum Model (WSM)</h6>
                        <p class="text-muted">
                            Metode ini menggunakan penjumlahan terbobot dari semua kriteria untuk menentukan ranking alternatif.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i> Mudah dipahami</li>
                            <li><i class="fas fa-check text-success me-2"></i> Komputasi cepat</li>
                            <li><i class="fas fa-check text-success me-2"></i> Cocok untuk kriteria independen</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Rumus Perhitungan</h6>
                        <div class="bg-light p-3 rounded">
                            <code>
                                Score = Σ(Wi × Xi)<br>
                                Dimana:<br>
                                Wi = Bobot kriteria i<br>
                                Xi = Nilai kriteria i
                            </code>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Update weight values when sliders change
    document.querySelectorAll('input[type="range"]').forEach(slider => {
        slider.addEventListener('input', function() {
            const valueSpan = document.getElementById(this.id.replace('Weight', 'Value'));
            valueSpan.textContent = this.value + '%';
            updateTotalWeight();
            updateChart();
        });
    });

    function updateTotalWeight() {
        const sliders = document.querySelectorAll('input[type="range"]');
        let total = 0;
        sliders.forEach(slider => {
            total += parseInt(slider.value);
        });
        
        document.getElementById('totalWeight').textContent = total + '%';
        document.getElementById('weightProgress').style.width = total + '%';
        
        if (total !== 100) {
            document.getElementById('weightProgress').classList.add('bg-warning');
            document.getElementById('weightProgress').classList.remove('bg-success');
        } else {
            document.getElementById('weightProgress').classList.add('bg-success');
            document.getElementById('weightProgress').classList.remove('bg-warning');
        }
    }

    // Initialize chart
    let weightChart;
    function initChart() {
        const ctx = document.getElementById('weightChart').getContext('2d');
        weightChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['IPK', 'Prestasi', 'Kepemimpinan', 'Sosial', 'Komunikasi', 'Kreativitas'],
                datasets: [{
                    data: [30, 25, 20, 15, 10, 0],
                    backgroundColor: [
                        '#4f46e5',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#6b7280'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    function updateChart() {
        if (weightChart) {
            const sliders = document.querySelectorAll('input[type="range"]');
            const values = Array.from(sliders).map(slider => parseInt(slider.value));
            weightChart.data.datasets[0].data = values;
            weightChart.update();
        }
    }

    function calculateSPK() {
        // Simulate SPK calculation
        const tableBody = document.querySelector('#rankingTable tbody');
        tableBody.innerHTML = `
            <tr>
                <td><span class="badge bg-success">1</span></td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                            A
                        </div>
                        <div>
                            <h6 class="mb-0">Ahmad Rizki</h6>
                            <small class="text-muted">NIM: 2023001</small>
                        </div>
                    </div>
                </td>
                <td><span class="badge bg-info">Teknik Informatika</span></td>
                <td><span class="badge bg-success">3.85</span></td>
                <td><span class="badge bg-primary">85</span></td>
                <td><span class="badge bg-warning">78</span></td>
                <td><span class="badge bg-info">82</span></td>
                <td><span class="badge bg-secondary">75</span></td>
                <td><span class="badge bg-dark">70</span></td>
                <td><strong class="text-success">82.5</strong></td>
                <td><span class="badge bg-success">Sangat Baik</span></td>
            </tr>
            <tr>
                <td><span class="badge bg-primary">2</span></td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                            S
                        </div>
                        <div>
                            <h6 class="mb-0">Siti Nurhaliza</h6>
                            <small class="text-muted">NIM: 2023002</small>
                        </div>
                    </div>
                </td>
                <td><span class="badge bg-info">Sistem Informasi</span></td>
                <td><span class="badge bg-success">3.75</span></td>
                <td><span class="badge bg-primary">80</span></td>
                <td><span class="badge bg-warning">85</span></td>
                <td><span class="badge bg-info">78</span></td>
                <td><span class="badge bg-secondary">82</span></td>
                <td><span class="badge bg-dark">75</span></td>
                <td><strong class="text-primary">80.2</strong></td>
                <td><span class="badge bg-primary">Baik</span></td>
            </tr>
            <tr>
                <td><span class="badge bg-warning">3</span></td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                            B
                        </div>
                        <div>
                            <h6 class="mb-0">Budi Santoso</h6>
                            <small class="text-muted">NIM: 2023003</small>
                        </div>
                    </div>
                </td>
                <td><span class="badge bg-info">Teknik Informatika</span></td>
                <td><span class="badge bg-success">3.65</span></td>
                <td><span class="badge bg-primary">75</span></td>
                <td><span class="badge bg-warning">80</span></td>
                <td><span class="badge bg-info">75</span></td>
                <td><span class="badge bg-secondary">78</span></td>
                <td><span class="badge bg-dark">72</span></td>
                <td><strong class="text-warning">76.8</strong></td>
                <td><span class="badge bg-warning">Cukup Baik</span></td>
            </tr>
        `;
        
        // Show success message
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            <strong>Berhasil!</strong> Analisis SPK telah selesai dihitung.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.content-header').after(alert);
    }

    function exportResults() {
        // Simulate export functionality
        alert('Fitur export akan segera tersedia!');
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initChart();
        updateTotalWeight();
    });
</script>
@endpush


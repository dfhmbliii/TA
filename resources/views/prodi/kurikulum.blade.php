@extends('layouts.app')
@section('title', 'Edit Kurikulum - ' . $prodi->nama_prodi)

@section('content')
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>
                <i class="fas fa-book-open me-2"></i>
                Edit Kurikulum
            </h1>
            <p class="text-muted">{{ $prodi->nama_prodi }} - {{ $prodi->nama_fakultas }}</p>
        </div>
        <div>
            <a href="{{ route('prodi.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('prodi.show', $prodi->id) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-eye me-2"></i>Preview
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Summary Card -->
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Kurikulum
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Total SKS</label>
                    <input type="number" id="totalSks" class="form-control" value="{{ $prodi->total_sks ?? 0 }}" min="0" readonly>
                    <div class="form-text text-muted">Nilai ini dihitung otomatis dari daftar mata kuliah. Ubah SKS per mata kuliah lalu klik "Hitung Total SKS" atau simpan kurikulum.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Jumlah Semester</label>
                    <input type="number" id="jumlahSemester" class="form-control" value="{{ $prodi->jumlah_semester ?? 8 }}" min="1" max="14">
                </div>
                <hr>
                    <div class="d-grid gap-2">
                    <button class="btn btn-success" onclick="showAddMataKuliahModal()">
                        <i class="fas fa-plus me-2"></i>Tambah Mata Kuliah
                    </button>
                    <button class="btn btn-primary" onclick="saveKurikulum()">
                        <i class="fas fa-save me-2"></i>Simpan Kurikulum
                    </button>
                    <button class="btn btn-warning" onclick="calculateSKS()">
                        <i class="fas fa-calculator me-2"></i>Hitung Total SKS
                    </button>
                </div>
                <hr>
                <div class="alert alert-info mb-0">
                    <small>
                        <i class="fas fa-lightbulb me-1"></i>
                        <strong>Tips:</strong> Gunakan drag & drop untuk mengurutkan mata kuliah
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Kurikulum Editor -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Daftar Mata Kuliah
                    <span class="badge bg-primary ms-2" id="countMK">{{ count($prodi->kurikulum_data ?? []) }}</span>
                </h5>
            </div>
            <div class="card-body">
                <div id="kurikulumList">
                    @php
                        $kurikulumBySemester = collect($prodi->kurikulum_data ?? [])->groupBy('semester')->sortKeys();
                    @endphp

                    @if($kurikulumBySemester->isEmpty())
                        <div class="alert alert-info text-center" id="emptyState">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada mata kuliah. Klik "Tambah Mata Kuliah" untuk mulai.</p>
                        </div>
                    @else
                        <div class="accordion" id="semesterAccordion">
                            @foreach($kurikulumBySemester as $semester => $mataKuliahList)
                                @php
                                    $totalSKS = collect($mataKuliahList)->sum('sks');
                                    $jumlahMK = count($mataKuliahList);
                                @endphp
                                <div class="accordion-item semester-group" data-semester="{{ $semester }}">
                                    <h2 class="accordion-header" id="heading-semester-{{ $semester }}">
                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse-semester-{{ $semester }}"
                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                            <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                <div>
                                                    <i class="fas fa-graduation-cap me-2"></i>
                                                    <strong>Semester {{ $semester }}</strong>
                                                </div>
                                                <div>
                                                    <span class="badge bg-primary me-2">{{ $jumlahMK }} MK</span>
                                                    <span class="badge bg-success">{{ $totalSKS }} SKS</span>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-semester-{{ $semester }}"
                                         class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                         data-bs-parent="#semesterAccordion">
                                        <div class="accordion-body p-2">
                                            @foreach($mataKuliahList as $index => $mk)
                                                <div class="list-group-item mb-2 mk-item"
                                                     data-index="{{ $loop->parent->index * 100 + $loop->index }}"
                                                     data-semester="{{ $semester }}">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div class="flex-grow-1">
                                                            <span class="drag-handle me-2" title="Drag untuk pindah urutan">
                                                                <i class="fas fa-grip-vertical text-muted"></i>
                                                            </span>
                                                            <span class="badge bg-primary me-2">Semester <span class="semester-value">{{ $mk['semester'] ?? 1 }}</span></span>
                                                            <span class="badge bg-secondary me-2">
                                                                <span class="sks-value">{{ $mk['sks'] ?? 0 }}</span> SKS
                                                            </span>
                                                            <span class="badge bg-{{ $mk['kategori'] == 'wajib' ? 'danger' : ($mk['kategori'] == 'pilihan' ? 'warning' : 'info') }}">
                                                                {{ ucfirst($mk['kategori'] ?? 'wajib') }}
                                                            </span>
                                                        </div>
                                                        <button class="btn btn-sm btn-danger" onclick="deleteMataKuliah({{ $loop->parent->index * 100 + $loop->index }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2">
                                                        <div class="col-md-3">
                                                            <input type="number" class="form-control form-control-sm mk-semester"
                                                                   placeholder="Semester" value="{{ $mk['semester'] ?? 1 }}" min="1" max="14" onchange="reorganizeSemesters()">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control form-control-sm mk-kode"
                                                                   placeholder="Kode MK" value="{{ $mk['kode'] ?? '' }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control form-control-sm mk-nama"
                                                                   placeholder="Nama Mata Kuliah" value="{{ $mk['nama'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="row g-2 mt-1">
                                                        <div class="col-md-2">
                                                            <input type="number" class="form-control form-control-sm mk-sks"
                                                                   placeholder="SKS" value="{{ $mk['sks'] ?? 0 }}" min="0" max="10">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control form-control-sm mk-praktikum"
                                                                   placeholder="Praktikum (opsional)" value="{{ $mk['praktikum'] ?? '' }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select class="form-select form-select-sm mk-kategori">
                                                                <option value="wajib" {{ ($mk['kategori'] ?? '') == 'wajib' ? 'selected' : '' }}>Wajib</option>
                                                                <option value="pilihan" {{ ($mk['kategori'] ?? '') == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                                                                <option value="mkwu" {{ ($mk['kategori'] ?? '') == 'mkwu' ? 'selected' : '' }}>MKWU</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control form-control-sm mk-prasyarat"
                                                                   placeholder="Prasyarat (opsional)" value="{{ $mk['prasyarat'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for submission -->
<form id="kurikulumForm" action="{{ route('prodi.kurikulum.update', $prodi->id) }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="kurikulum_data" id="kurikulumDataInput">
    <input type="hidden" name="total_sks" id="totalSksInput">
    <input type="hidden" name="jumlah_semester" id="jumlahSemesterInput">
</form>

<!-- Modal: Tambah Mata Kuliah -->
<div class="modal fade" id="addMkModal" tabindex="-1" aria-labelledby="addMkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMkModalLabel"><i class="fas fa-plus-circle me-2"></i>Tambah Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Semester</label>
                        <select id="modalSemester" class="form-select">
                            @for($i=1;$i<=($prodi->jumlah_semester ?? 8);$i++)
                                <option value="{{ $i }}" {{ $i==1? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">SKS</label>
                        <input id="modalSks" type="number" min="0" class="form-control" value="3">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kategori</label>
                        <select id="modalKategori" class="form-select">
                            <option value="wajib">Wajib</option>
                            <option value="pilihan">Pilihan</option>
                            <option value="mkwu">MKWU</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label">Kode MK</label>
                        <input id="modalKode" class="form-control" placeholder="Kode MK">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label">Praktikum</label>
                        <input id="modalPraktikum" class="form-control" placeholder="Praktikum (opsional)">
                    </div>
                    <div class="col-12 mt-2">
                        <label class="form-label">Nama Mata Kuliah</label>
                        <input id="modalNama" class="form-control" placeholder="Nama Mata Kuliah">
                    </div>
                    <div class="col-12 mt-2">
                        <label class="form-label">Prasyarat (opsional)</label>
                        <input id="modalPrasyarat" class="form-control" placeholder="Prasyarat">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="submitAddMataKuliahModal()">Tambah Mata Kuliah</button>
            </div>
        </div>
    </div>
</div>

<script>
let mkCounter = Date.now(); // Use timestamp for unique ID

function addMataKuliah() {
    const emptyState = document.getElementById('emptyState');
    if(emptyState) {
        emptyState.remove();
        // Create accordion structure
        const list = document.getElementById('kurikulumList');
        list.innerHTML = '<div class="accordion" id="semesterAccordion"></div>';
    }

    const accordion = document.getElementById('semesterAccordion');
    if(!accordion) {
        console.error('Accordion not found');
        return;
    }

    // Check if semester 1 exists
    let semester1 = accordion.querySelector('.semester-group[data-semester="1"]');
    if(!semester1) {
        // Create semester 1 accordion, open by default
        semester1 = createSemesterAccordion(1, true);
        accordion.appendChild(semester1);
    }

    // Add to semester 1 body
    const semester1Body = semester1.querySelector('.accordion-body');
    const newItem = createMataKuliahItem(mkCounter, 1, {});
    semester1Body.appendChild(newItem);

    mkCounter++; // Increment for next item
    updateCount();
    updateSemesterBadges();

    // Enable drag drop and live listeners
    enableDragDrop(newItem);
    addLiveUpdateListeners(newItem);

    // Focus on nama mata kuliah input
    const namaInput = newItem.querySelector('.mk-nama');
    if(namaInput) {
        namaInput.focus();
    }
}



// Show modal for adding mata kuliah
function showAddMataKuliahModal() {
    const modalEl = document.getElementById('addMkModal');
    const modal = new bootstrap.Modal(modalEl);
    // reset fields
    document.getElementById('modalKode').value = '';
    document.getElementById('modalNama').value = '';
    document.getElementById('modalSks').value = 3;
    document.getElementById('modalPraktikum').value = '';
    document.getElementById('modalPrasyarat').value = '';
    document.getElementById('modalKategori').value = 'wajib';
    document.getElementById('modalSemester').value = 1;
    modal.show();
}

function submitAddMataKuliahModal() {
    const semester = parseInt(document.getElementById('modalSemester').value) || 1;
    const kode = document.getElementById('modalKode').value.trim();
    const nama = document.getElementById('modalNama').value.trim();
    const sks = parseInt(document.getElementById('modalSks').value) || 0;
    const praktikum = document.getElementById('modalPraktikum').value.trim();
    const kategori = document.getElementById('modalKategori').value;
    const prasyarat = document.getElementById('modalPrasyarat').value.trim();

    if (!nama) { alert('Nama mata kuliah harus diisi'); return; }

    // Ensure accordion exists
    if (!document.getElementById('semesterAccordion')) {
        document.getElementById('kurikulumList').innerHTML = '<div class="accordion" id="semesterAccordion"></div>';
    }

    let semesterGroup = document.querySelector('.semester-group[data-semester="' + semester + '"]');
    if (!semesterGroup) {
        const sGroup = createSemesterAccordion(semester, true);
        document.getElementById('semesterAccordion').appendChild(sGroup);
        semesterGroup = sGroup;
    }

    const body = semesterGroup.querySelector('.accordion-body');
    const newItem = createMataKuliahItem(mkCounter, semester, { kode, nama, sks, praktikum, kategori, prasyarat });
    body.appendChild(newItem);
    enableDragDrop(newItem);
    addLiveUpdateListeners(newItem);
    updateCount();
    updateSemesterBadges();
    updateTotalSKS();
    mkCounter++;

    // hide modal
    const modalEl = document.getElementById('addMkModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();
}

function createSemesterAccordion(semester, isOpen = false) {
    const div = document.createElement('div');
    div.className = 'accordion-item semester-group';
    div.dataset.semester = semester;
    div.innerHTML = `
        <h2 class="accordion-header" id="heading-semester-${semester}">
            <button class="accordion-button ${isOpen ? '' : 'collapsed'}" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapse-semester-${semester}"
                    aria-expanded="${isOpen}">
                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                    <div>
                        <i class="fas fa-graduation-cap me-2"></i>
                        <strong>Semester ${semester}</strong>
                    </div>
                    <div>
                        <span class="badge bg-primary me-2 semester-mk-count">0 MK</span>
                        <span class="badge bg-success semester-sks-count">0 SKS</span>
                    </div>
                </div>
            </button>
        </h2>
        <div id="collapse-semester-${semester}" class="accordion-collapse collapse ${isOpen ? 'show' : ''}" data-bs-parent="#semesterAccordion">
            <div class="accordion-body p-2"></div>
        </div>
    `;
    return div;
}

function createMataKuliahItem(index, semester, data = {}) {
    const div = document.createElement('div');
    div.className = 'list-group-item mb-2 mk-item';
    div.dataset.index = index;
    div.dataset.semester = semester;

    // use provided data or defaults
    const kode = data.kode || '';
    const nama = data.nama || '';
    const sks = typeof data.sks !== 'undefined' ? data.sks : 0;
    const praktikum = data.praktikum || '';
    const kategori = data.kategori || 'wajib';
    const prasyarat = data.prasyarat || '';

    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="flex-grow-1">
                <span class="drag-handle me-2" title="Drag untuk pindah urutan">
                    <i class="fas fa-grip-vertical text-muted"></i>
                </span>
                <span class="badge bg-primary me-2">Semester <span class="semester-value">${semester}</span></span>
                <span class="badge bg-secondary me-2">
                    <span class="sks-value">${sks}</span> SKS
                </span>
                <span class="badge bg-${kategori === 'wajib' ? 'danger' : (kategori === 'pilihan' ? 'warning' : 'info')}">${kategori ? kategori.charAt(0).toUpperCase() + kategori.slice(1) : 'Wajib'}</span>
            </div>
            <button class="btn btn-sm btn-danger" onclick="deleteMataKuliah(${index})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-md-3">
                <input type="number" class="form-control form-control-sm mk-semester"
                       placeholder="Semester" value="${semester}" min="1" max="14" onchange="reorganizeSemesters()">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm mk-kode"
                       placeholder="Kode MK" value="${kode}">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm mk-nama"
                       placeholder="Nama Mata Kuliah" value="${nama}">
            </div>
        </div>
        <div class="row g-2 mt-1">
            <div class="col-md-2">
                <input type="number" class="form-control form-control-sm mk-sks"
                       placeholder="SKS" value="${sks}" min="0" max="10" onchange="updateSemesterBadges()">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control form-control-sm mk-praktikum"
                       placeholder="Praktikum (opsional)" value="${praktikum}">
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-sm mk-kategori">
                    <option value="wajib" ${kategori === 'wajib' ? 'selected' : ''}>Wajib</option>
                    <option value="pilihan" ${kategori === 'pilihan' ? 'selected' : ''}>Pilihan</option>
                    <option value="mkwu" ${kategori === 'mkwu' ? 'selected' : ''}>MKWU</option>
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control form-control-sm mk-prasyarat"
                       placeholder="Prasyarat (opsional)" value="${prasyarat}">
            </div>
        </div>
    `;
    return div;
}

function reorganizeSemesters() {
    const items = document.querySelectorAll('.mk-item');
    const accordion = document.getElementById('semesterAccordion');

    // Track which semesters are currently open
    const openSemesters = new Set();
    document.querySelectorAll('.accordion-collapse.show').forEach(collapse => {
        const semesterGroup = collapse.closest('.semester-group');
        if(semesterGroup) {
            openSemesters.add(semesterGroup.dataset.semester);
        }
    });

    // Group items by semester
    const bySemester = {};
    items.forEach(item => {
        const semesterInput = item.querySelector('.mk-semester');
        const semester = parseInt(semesterInput.value) || 1;

        // Update item semester
        item.dataset.semester = semester;
        item.querySelector('.semester-value').textContent = semester;

        if(!bySemester[semester]) {
            bySemester[semester] = [];
        }
        bySemester[semester].push(item);
    });

    // Clear accordion
    accordion.innerHTML = '';

    // Recreate semester groups, keeping previous open state
    Object.keys(bySemester).sort((a, b) => a - b).forEach(semester => {
        const shouldBeOpen = openSemesters.has(semester);
        const semesterGroup = createSemesterAccordion(semester, shouldBeOpen);
        const body = semesterGroup.querySelector('.accordion-body');

        bySemester[semester].forEach(item => {
            body.appendChild(item);
            // Re-enable drag drop after moving
            enableDragDrop(item);
        });

        accordion.appendChild(semesterGroup);
    });

    updateSemesterBadges();
    updateCount();
}

function updateSemesterBadges() {
    document.querySelectorAll('.semester-group').forEach(group => {
        const items = group.querySelectorAll('.mk-item');
        const totalSKS = Array.from(items).reduce((sum, item) => {
            return sum + (parseInt(item.querySelector('.mk-sks').value) || 0);
        }, 0);

        group.querySelector('.semester-mk-count').textContent = `${items.length} MK`;
        group.querySelector('.semester-sks-count').textContent = `${totalSKS} SKS`;
    });
}

// Update overall total SKS for all semesters and sync inputs
function updateTotalSKS() {
    const allItems = document.querySelectorAll('.mk-item');
    const overall = Array.from(allItems).reduce((sum, item) => {
        return sum + (parseInt(item.querySelector('.mk-sks').value) || 0);
    }, 0);

    const totalVisible = document.getElementById('totalSks');
    if (totalVisible) totalVisible.value = overall;

    const totalHidden = document.getElementById('totalSksInput');
    if (totalHidden) totalHidden.value = overall;
}

function deleteMataKuliah(index) {
    if(!confirm('Hapus mata kuliah ini?')) return;

    const item = document.querySelector(`[data-index="${index}"]`);
    if(item) {
        const semesterGroup = item.closest('.semester-group');
        item.remove();

        // Remove semester group if empty
        const remainingItems = semesterGroup.querySelectorAll('.mk-item');
        if(remainingItems.length === 0) {
            semesterGroup.remove();
        }

        updateCount();
        updateSemesterBadges();

        // Show empty state if no items
        const allItems = document.querySelectorAll('.mk-item');
        if(allItems.length === 0) {
            const list = document.getElementById('kurikulumList');
            list.innerHTML = `
                <div class="alert alert-info text-center" id="emptyState">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p class="mb-0">Belum ada mata kuliah. Klik "Tambah Mata Kuliah" untuk mulai.</p>
                </div>
            `;
        }
    }
}

function calculateSKS() {
    let total = 0;
    document.querySelectorAll('.mk-sks').forEach(input => {
        total += parseInt(input.value) || 0;
    });
    // Update visible and hidden inputs, and semester summaries
    const totalVisible = document.getElementById('totalSks');
    if (totalVisible) totalVisible.value = total;

    const totalHidden = document.getElementById('totalSksInput');
    if (totalHidden) totalHidden.value = total;

    updateSemesterBadges();
    // Keep overall totals in sync as well
    updateTotalSKS();
    alert(`Total SKS: ${total}`);
}

function updateCount() {
    const count = document.querySelectorAll('.mk-item').length;
    document.getElementById('countMK').textContent = count;
}

function saveKurikulum() {
    const items = document.querySelectorAll('.mk-item');
    const kurikulumData = [];

    items.forEach(item => {
            const data = {
            semester: parseInt(item.querySelector('.mk-semester').value) || 1,
            kode: item.querySelector('.mk-kode').value.trim(),
            nama: item.querySelector('.mk-nama').value.trim(),
            sks: parseInt(item.querySelector('.mk-sks').value) || 0,
            praktikum: item.querySelector('.mk-praktikum') ? item.querySelector('.mk-praktikum').value.trim() : '',
            kategori: item.querySelector('.mk-kategori').value,
            prasyarat: item.querySelector('.mk-prasyarat').value.trim()
        };

        if(data.nama) { // Only add if nama is not empty
            kurikulumData.push(data);
        }
    });

    if(kurikulumData.length === 0) {
        alert('Tidak ada mata kuliah untuk disimpan!');
        return;
    }

    // Calculate total SKS defensively just before submit
    const total = kurikulumData.reduce((s, m) => s + (parseInt(m.sks) || 0), 0);

    // Set form values
    document.getElementById('kurikulumDataInput').value = JSON.stringify(kurikulumData);
    document.getElementById('totalSksInput').value = total;
    const totalVisible = document.getElementById('totalSks');
    if (totalVisible) totalVisible.value = total;
    document.getElementById('jumlahSemesterInput').value = document.getElementById('jumlahSemester').value;

    // Submit form
    document.getElementById('kurikulumForm').submit();
}

// Live update badges
function addLiveUpdateListeners(item) {
    const semesterInput = item.querySelector('.mk-semester');
    const sksInput = item.querySelector('.mk-sks');

    semesterInput.addEventListener('input', function() {
        item.querySelector('.semester-value').textContent = this.value;
    });

    sksInput.addEventListener('input', function() {
        item.querySelector('.sks-value').textContent = this.value;
        updateSemesterBadges();
        updateTotalSKS();
    });
}

// Drag and Drop functionality
let draggedElement = null;

function handleDragStart(e) {
    draggedElement = e.currentTarget;
    draggedElement.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', draggedElement.innerHTML);
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';

    // Add visual indicator
    const target = e.target.closest('.mk-item');
    if (target && target !== draggedElement) {
        // Remove all previous indicators
        document.querySelectorAll('.mk-item').forEach(item => {
            item.classList.remove('drag-over-top', 'drag-over-bottom');
        });

        const rect = target.getBoundingClientRect();
        const midpoint = rect.top + rect.height / 2;

        if (e.clientY < midpoint) {
            target.classList.add('drag-over-top');
        } else {
            target.classList.add('drag-over-bottom');
        }
    }
}

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();

    const target = e.target.closest('.mk-item');

    if (draggedElement && target && draggedElement !== target) {
        const rect = target.getBoundingClientRect();
        const midpoint = rect.top + rect.height / 2;
        const parent = target.parentNode;

        // Insert before or after based on drop position
        if (e.clientY < midpoint) {
            parent.insertBefore(draggedElement, target);
        } else {
            parent.insertBefore(draggedElement, target.nextSibling);
        }
    }
}

function handleDragEnd(e) {
    e.currentTarget.classList.remove('dragging');

    // Remove all drag-over classes
    document.querySelectorAll('.mk-item').forEach(item => {
        item.classList.remove('drag-over-top', 'drag-over-bottom');
    });

    draggedElement = null;
}

function enableDragDrop(item) {
    item.draggable = true;
    item.addEventListener('dragstart', handleDragStart);
    item.addEventListener('dragover', handleDragOver);
    item.addEventListener('drop', handleDrop);
    item.addEventListener('dragend', handleDragEnd);
}

// Initialize event listeners for existing items
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.mk-item').forEach(item => {
        enableDragDrop(item);
        addLiveUpdateListeners(item);
    });
    updateSemesterBadges();
    updateTotalSKS();
});
</script>

<style>
.mk-item {
    border-left: 4px solid #4f46e5;
    transition: all 0.2s;
    cursor: move;
}
.mk-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.mk-item.dragging {
    opacity: 0.5;
    transform: rotate(2deg);
}
.mk-item.drag-over-top {
    border-top: 3px solid #4f46e5;
}
.mk-item.drag-over-bottom {
    border-bottom: 3px solid #4f46e5;
}
.drag-handle {
    cursor: grab;
    padding: 2px 4px;
}
.drag-handle:active {
    cursor: grabbing;
}
.accordion-button:not(.collapsed) {
    background-color: #e7f3ff;
    color: #0056b3;
}
</style>
@endsection

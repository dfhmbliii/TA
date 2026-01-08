@extends('layouts.app')
@section('title','Notifikasi')
@section('content')
<div class="content-header d-flex justify-content-between align-items-center">
    <div>
        <h1>Notifikasi</h1>
        <p>Semua notifikasi terbaru Anda</p>
    </div>
    <div class="d-flex gap-2">
        <form action="{{ route('notifications.markAll') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca</button>
        </form>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('notifications.index') }}" id="filterForm">
            <div class="row g-3">
                <!-- Filter by Type -->
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="fas fa-tag me-2"></i>Tipe Notifikasi</label>
                    <select name="type" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Tipe</option>
                        <option value="spk_result" {{ request('type') == 'spk_result' ? 'selected' : '' }}>SPK Result</option>
                        <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>System</option>
                        <option value="account_deletion" {{ request('type') == 'account_deletion' ? 'selected' : '' }}>Account Deletion</option>
                        <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                    </select>
                </div>

                <!-- Filter by Status -->
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="fas fa-check-circle me-2"></i>Status</label>
                    <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Status</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                    </select>
                </div>

                <!-- Filter by Date -->
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="fas fa-calendar me-2"></i>Periode</label>
                    <select name="date" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Waktu</option>
                        <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters & Reset -->
            @if(request()->hasAny(['type', 'status', 'date']))
                <div class="mt-3 d-flex align-items-center gap-2">
                    <span class="badge bg-secondary">
                        <i class="fas fa-filter me-1"></i>Filter Aktif:
                    </span>
                    @if(request('type'))
                        <span class="badge bg-primary">
                            Tipe: {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="badge bg-info">
                            Status: {{ request('status') == 'unread' ? 'Belum Dibaca' : 'Sudah Dibaca' }}
                        </span>
                    @endif
                    @if(request('date'))
                        <span class="badge bg-warning text-dark">
                            Periode: {{ ucfirst(request('date')) }}
                        </span>
                    @endif
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-danger ms-2">
                        <i class="fas fa-times me-1"></i>Reset Filter
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-bell me-2"></i> 
            @if(request()->hasAny(['type', 'status', 'date']))
                Hasil Filter: {{ $notifications->total() }} notifikasi
                <span class="text-muted small">({{ $unreadCount }} total belum dibaca)</span>
            @else
                Notifikasi ({{ $unreadCount }} belum dibaca)
            @endif
        </span>
    </div>
    <div class="list-group list-group-flush">
        @forelse($notifications as $n)
            <div class="list-group-item {{ $n->is_read ? '' : 'bg-light' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <h6 class="mb-0">{{ $n->title }}</h6>
                            @php
                                $typeConfig = [
                                    'spk_result' => ['label' => 'SPK Result', 'bg' => 'success', 'icon' => 'chart-line'],
                                    'system' => ['label' => 'System', 'bg' => 'info', 'icon' => 'cog'],
                                    'account_deletion' => ['label' => 'Account', 'bg' => 'danger', 'icon' => 'user-times'],
                                    'announcement' => ['label' => 'Announcement', 'bg' => 'warning', 'icon' => 'bullhorn'],
                                ];
                                $config = $typeConfig[$n->type] ?? ['label' => ucfirst($n->type), 'bg' => 'secondary', 'icon' => 'bell'];
                            @endphp
                            <span class="badge bg-{{ $config['bg'] }}">
                                <i class="fas fa-{{ $config['icon'] }} me-1"></i>{{ $config['label'] }}
                            </span>
                            @if(!$n->is_read)
                                <span class="badge bg-primary">Baru</span>
                            @endif
                        </div>
                        <p class="mb-1 small text-muted">{{ $n->message }}</p>
                        <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $n->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-end ms-3">
                        @if(!$n->is_read)
                            <form action="{{ route('notifications.mark',$n->id) }}" method="POST" class="mb-2">
                                @csrf
                                <button class="btn btn-sm btn-primary text-nowrap"><i class="fas fa-eye me-1"></i> Tandai Dibaca</button>
                            </form>
                        @endif
                        @if($n->type === 'spk_result' && isset($n->data['result_id']))
                            <a href="{{ url('/riwayat') }}" class="btn btn-sm btn-outline-secondary text-nowrap"><i class="fas fa-chart-line me-1"></i> Lihat Hasil</a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="list-group-item text-center py-5">
                <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                <p class="mb-0 text-muted">
                    @if(request()->hasAny(['type', 'status', 'date']))
                        Tidak ada notifikasi yang sesuai dengan filter.
                    @else
                        Belum ada notifikasi.
                    @endif
                </p>
            </div>
        @endforelse
    </div>
    <div class="card-footer">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Kelola Permintaan Hapus Akun')

@section('content')
<div class="content-header">
    <h1>Kelola Permintaan Hapus Akun</h1>
    <p>Tinjau dan proses permintaan hapus akun dari mahasiswa</p>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>
                Daftar Permintaan
            </h5>
            <div class="btn-group">
                <button class="btn btn-sm btn-warning active" data-filter="pending">
                    <i class="fas fa-clock me-1"></i>
                    Pending
                </button>
                <button class="btn btn-sm btn-outline-success" data-filter="approved">
                    <i class="fas fa-check me-1"></i>
                    Approved
                </button>
                <button class="btn btn-sm btn-outline-danger" data-filter="rejected">
                    <i class="fas fa-times me-1"></i>
                    Rejected
                </button>
                <button class="btn btn-sm btn-outline-primary" data-filter="all">
                    <i class="fas fa-globe me-1"></i>
                    Semua
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($requests->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Tidak ada permintaan hapus akun</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Alasan</th>
                        <th>Tanggal Request</th>
                        <th>Status</th>
                        <th>Admin</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                    <tr data-status="{{ $req->status }}">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                    {{ substr($req->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <strong>{{ $req->user->name ?? 'Unknown' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $req->user->email ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($req->reason)
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                      data-bs-toggle="tooltip" title="{{ $req->reason }}">
                                    {{ $req->reason }}
                                </span>
                            @else
                                <span class="text-muted fst-italic">Tidak ada alasan</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                {{ $req->created_at->format('d M Y H:i') }}
                                <br>
                                <span class="text-muted">{{ $req->created_at->diffForHumans() }}</span>
                            </small>
                        </td>
                        <td>
                            @if($req->status === 'pending')
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>
                                    Pending
                                </span>
                            @elseif($req->status === 'approved')
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>
                                    Approved
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times me-1"></i>
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($req->approver)
                                <small>
                                    {{ $req->approver->name }}
                                    <br>
                                    <span class="text-muted">{{ $req->approved_at?->format('d M Y H:i') }}</span>
                                </small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($req->status === 'pending')
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-success text-nowrap" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#approveModal{{ $req->id }}">
                                        <i class="fas fa-check me-1"></i>
                                        Approve
                                    </button>
                                    <button class="btn btn-sm btn-danger text-nowrap" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal{{ $req->id }}">
                                        <i class="fas fa-times me-1"></i>
                                        Reject
                                    </button>
                                </div>
                            @else
                                <button class="btn btn-sm btn-info" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailModal{{ $req->id }}">
                                    <i class="fas fa-eye me-1"></i>
                                    Detail
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    @if($requests->hasPages())
    <div class="card-footer">
        {{ $requests->links() }}
    </div>
    @endif
</div>

<!-- Modals for each request -->
@foreach($requests as $req)
<!-- Approve Modal -->
<div class="modal fade" id="approveModal{{ $req->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>
                    Approve Permintaan Hapus Akun
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.deletion-requests.approve', $req->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>Peringatan!</strong> Akun user akan langsung dihapus permanen setelah approval.
                    </div>
                    <p>Anda yakin ingin menyetujui permintaan hapus akun dari:</p>
                    <div class="bg-light p-3 rounded mb-3">
                        <strong>{{ $req->user->name }}</strong><br>
                        <small class="text-muted">{{ $req->user->email }}</small>
                    </div>
                    @if($req->reason)
                    <div class="mb-3">
                        <label class="form-label"><strong>Alasan User:</strong></label>
                        <p class="text-muted">{{ $req->reason }}</p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label for="admin_notes_approve{{ $req->id }}" class="form-label">
                            Catatan Admin (opsional):
                        </label>
                        <textarea 
                            class="form-control" 
                            id="admin_notes_approve{{ $req->id }}" 
                            name="admin_notes" 
                            rows="3"
                            placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>
                        Ya, Approve & Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle me-2"></i>
                    Reject Permintaan Hapus Akun
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.deletion-requests.reject', $req->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Anda akan menolak permintaan hapus akun dari:</p>
                    <div class="bg-light p-3 rounded mb-3">
                        <strong>{{ $req->user->name }}</strong><br>
                        <small class="text-muted">{{ $req->user->email }}</small>
                    </div>
                    @if($req->reason)
                    <div class="mb-3">
                        <label class="form-label"><strong>Alasan User:</strong></label>
                        <p class="text-muted">{{ $req->reason }}</p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label for="admin_notes_reject{{ $req->id }}" class="form-label">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea 
                            class="form-control" 
                            id="admin_notes_reject{{ $req->id }}" 
                            name="admin_notes" 
                            rows="3"
                            required
                            placeholder="Jelaskan alasan penolakan (akan dikirim ke user)..."></textarea>
                        <small class="text-muted">User akan menerima notifikasi dengan alasan ini</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>
                        Ya, Reject Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail Modal (for approved/rejected) -->
<div class="modal fade" id="detailModal{{ $req->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Detail Permintaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">User</th>
                        <td>{{ $req->user->name ?? 'Deleted' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $req->user->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alasan User</th>
                        <td>{{ $req->reason ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($req->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Diproses oleh</th>
                        <td>{{ $req->approver->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Diproses</th>
                        <td>{{ $req->approved_at?->format('d M Y H:i') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Catatan Admin</th>
                        <td>{{ $req->admin_notes ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 1rem;
    font-weight: bold;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter buttons
    const filterButtons = document.querySelectorAll('[data-filter]');
    const tableRows = document.querySelectorAll('tbody tr[data-status]');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button - remove active and convert to outline
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                const filterType = btn.getAttribute('data-filter');
                
                // Reset ke outline version
                if (filterType === 'pending') {
                    btn.className = 'btn btn-sm btn-outline-warning';
                } else if (filterType === 'approved') {
                    btn.className = 'btn btn-sm btn-outline-success';
                } else if (filterType === 'rejected') {
                    btn.className = 'btn btn-sm btn-outline-danger';
                } else if (filterType === 'all') {
                    btn.className = 'btn btn-sm btn-outline-primary';
                }
            });
            
            // Set active button to solid version
            if (filter === 'pending') {
                this.className = 'btn btn-sm btn-warning active';
            } else if (filter === 'approved') {
                this.className = 'btn btn-sm btn-success active';
            } else if (filter === 'rejected') {
                this.className = 'btn btn-sm btn-danger active';
            } else if (filter === 'all') {
                this.className = 'btn btn-sm btn-primary active';
            }
            
            // Filter rows
            tableRows.forEach(row => {
                const status = row.getAttribute('data-status');
                if (filter === 'all' || status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection

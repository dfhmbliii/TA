@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="content-header">
    <h1>Kelola Users</h1>
    <p>Manajemen pengguna sistem SPK</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i>
                Daftar Users
            </h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fas fa-plus me-2"></i>
                Tambah User
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            <i class="fas fa-user me-2"></i>
                            Nama
                        </th>
                        <th>
                            <i class="fas fa-envelope me-2"></i>
                            Email
                        </th>
                        <th>
                            <i class="fas fa-calendar me-2"></i>
                            Dibuat
                        </th>
                        <th class="text-center">
                            <i class="fas fa-cogs me-2"></i>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ ucfirst($user->role) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $user->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                        <script>
                            function openEditModal(userId) {
                                const modal = document.querySelector(`#editUserModal${userId}`);
                                if (modal) {
                                    const bootstrapModal = new bootstrap.Modal(modal);
                                    bootstrapModal.show();
                                }
                            }

                            // Add loading state to all forms when submitting
                            document.querySelectorAll('form').forEach(form => {
                                form.addEventListener('submit', function() {
                                    const submitBtn = this.querySelector('button[type="submit"]');
                                    if (submitBtn) {
                                        submitBtn.disabled = true;
                                        const originalText = submitBtn.innerHTML;
                                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                                        
                                        // Re-enable after 10s if no redirect (timeout/error case)
                                        setTimeout(() => {
                                            if (submitBtn.disabled) {
                                                submitBtn.disabled = false;
                                                submitBtn.innerHTML = originalText;
                                            }
                                        }, 10000);
                                    }
                                });
                            });

                            // Show any error messages in modals if they exist
                            @if($errors->any())
                                @foreach($users as $user)
                                    if (window.location.hash === '#edit-user-{{$user->id}}') {
                                        openEditModal({{$user->id}});
                                    }
                                @endforeach
                            @endif
                        </script>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>
                    Tambah User Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nama Lengkap
                                </label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Masukkan nama lengkap">
                                <div class="form-text">Nama lengkap pengguna</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="email@example.com">
                                <div class="form-text">Alamat email yang valid</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Masukkan password">
                                <div class="form-text">Minimal 8 karakter</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    Konfirmasi Password
                                </label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi password">
                                <div class="form-text">Ulangi password yang sama</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
                        <div class="form-text">Only needed if changing password</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let editModal = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    });

    function editUser(id, name, email) {
        const form = document.getElementById('editUserForm');
        form.action = `/users/${id}`;
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        
        // Reset password fields
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirmation').value = '';
        
        editModal.show();
    }

    // Add loading state to all forms when submitting
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                
                // Re-enable after 10s if no redirect (timeout/error case)
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }, 10000);
            }
        });
    });

    // Show any error messages in modals if they exist
    @if($errors->any())
        @if(session('editUserId'))
            editUser({{ session('editUserId') }}, '{{ old('name') }}', '{{ old('email') }}');
        @endif
    @endif
</script>
@endsection
@extends('layouts.main')

@section('container')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold" style="color:rgb(81, 135, 190)">Users Management</h2>
                <p class="text-muted">Pengelolaan data pengguna sistem</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-person-plus me-2"></i>Tambah User
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg" style="border-radius: 8px;">
                <div class="card-header py-3" style="background-color: rgb(131, 184, 236);">
                    <h4 class="mb-0" style="color: #fff;">List of Users</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped w-100">
                            <thead class="table-light">
                                <tr class="custom-table-header">
                                    <th width="50">No</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role == 'admin' ? 'primary' : 'info' }}">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('adduser.destroy', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                                
                                                <!-- buat button untuk membuat modal edit -->
                                                <!-- <button type="button" class="btn btn-sm btn-warning ms-1" onclick="openEditModal({{ $user }})"><i class="bi bi-pencil-square"></i> Edit</button> -->
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(131, 184, 236);">
                    <h5 class="modal-title" id="addUserModalLabel" style="color: #fff;">{{ __('Add User') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('adduser.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- role -->
                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Role') }}</label>

                            <div class="col-md-6">
                                <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>

                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus me-2"></i>{{ __('Add User') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal (Original) -->
    <div id="editModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border:1px solid #ccc; z-index:1000;">
        <h3>Edit User</h3>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="editName" class="form-label">Name</label>
                <input type="text" id="editName" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="editEmail" class="form-label">Email</label>
                <input type="email" id="editEmail" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="editRole" class="form-label">Role</label>
                <select id="editRole" name="role" class="form-control" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>

    <div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;" onclick="closeEditModal()"></div>

    <script>
        // Show modal on validation errors
        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                var addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
                addUserModal.show();
            });
        @endif

        function openEditModal(user) {
            document.getElementById('editModal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';

            // Isi form dengan data pengguna
            document.getElementById('editName').value = user.username;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editRole').value = user.role;

            // Set form action URL
            document.getElementById('editForm').action = `/adduser/${user.id}`;
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
    </script>
</div>
@endsection
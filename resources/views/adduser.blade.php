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
                <button type="button" class="btn btn-primary" onclick="openAddUserModal()">
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

        function openAddUserModal() {
            Swal.fire({
                title: 'Add User',
                html: `
                    <form id="addUserForm" action="{{ route('adduser.store') }}" method="POST">
                        @csrf
                        <div class="mb-3 text-start">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" class="form-select" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Add User',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                customClass: {
                    container: 'my-swal'
                },
                didOpen: () => {
                    // Show validation errors if any
                    @if($errors->any())
                        let errorMessage = '';
                        @foreach($errors->all() as $error)
                            errorMessage += '{{ $error }}<br>';
                        @endforeach
                        Swal.showValidationMessage(errorMessage);
                    @endif
                },
                preConfirm: () => {
                    // Submit form
                    document.getElementById('addUserForm').submit();
                }
            });
        }
    </script>

    <style>
        .my-swal {
            z-index: 99999;
        }

        .my-swal .form-label {
            float: left;
            margin-bottom: 0.5rem;
        }

        .my-swal .form-control,
        .my-swal .form-select {
            margin-bottom: 1rem;
            text-align: left;
        }

        .my-swal .swal2-html-container {
            margin: 1em 1.6em 0.3em;
        }
    </style>
</div>
@endsection
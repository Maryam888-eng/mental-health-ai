<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <div class="bg-light min-vh-100 py-4">
        <div class="container-fluid px-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Admin User Management</h2>
                    <p class="text-muted mb-0">Manage doctors and patients from one dashboard.</p>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        + Add Doctor / Patient
                    </button>

                    <a href="{{ route('ai-providers.index') }}" class="btn btn-outline-secondary">
                        AI Providers
                    </a>
                </div>
            </div>

            <div id="alertBox" class="alert d-none"></div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="text-muted small">Total Doctors</div>
                            <div class="fs-3 fw-bold">{{ $doctors->count() }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="text-muted small">Total Patients</div>
                            <div class="fs-3 fw-bold">{{ $patients->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#doctorsTab">
                        Doctors
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#patientsTab">
                        Patients
                    </button>
                </li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane fade show active" id="doctorsTab">
                    @include('admin.users.partials.table', ['users' => $doctors, 'type' => 'Doctors'])
                </div>

                <div class="tab-pane fade" id="patientsTab">
                    @include('admin.users.partials.table', ['users' => $patients, 'type' => 'Patients'])
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="addUserForm" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Doctor / Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="user">Patient</option>
                            <option value="doctor">Doctor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editUserForm" class="modal-content">
                @csrf
                @method('PATCH')

                <input type="hidden" id="editUserId">

                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" id="editName" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" id="editEmail" type="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="editRole" class="form-select" required>
                            <option value="user">Patient</option>
                            <option value="doctor">Doctor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input name="password" type="password" class="form-control" placeholder="Leave blank to keep old password">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        function showAlert(message, type = 'success') {
            $('#alertBox')
                .removeClass('d-none alert-success alert-danger')
                .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
                .text(message);
        }

        $('#addUserForm').on('submit', function (e) {
            e.preventDefault();

            $.post('{{ route('admin.users.store') }}', $(this).serialize())
                .done(function () {
                    location.reload();
                })
                .fail(function (xhr) {
                    showAlert(xhr.responseJSON?.message || 'Could not create user.', 'error');
                });
        });

        $(document).on('click', '.editUserBtn', function () {
            $('#editUserId').val($(this).data('id'));
            $('#editName').val($(this).data('name'));
            $('#editEmail').val($(this).data('email'));
            $('#editRole').val($(this).data('role'));

            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        });

        $('#editUserForm').on('submit', function (e) {
            e.preventDefault();

            const id = $('#editUserId').val();

            $.ajax({
                url: `/admin/users/${id}`,
                method: 'POST',
                data: $(this).serialize() + '&_method=PATCH',
                success: function () {
                    location.reload();
                },
                error: function (xhr) {
                    showAlert(xhr.responseJSON?.message || 'Could not update user.', 'error');
                }
            });
        });

        $(document).on('click', '.deleteUserBtn', function () {
            if (!confirm('Delete this user?')) {
                return;
            }

            const id = $(this).data('id');

            $.ajax({
                url: `/admin/users/${id}`,
                method: 'POST',
                data: {
                    _method: 'DELETE'
                },
                success: function () {
                    location.reload();
                },
                error: function (xhr) {
                    showAlert(xhr.responseJSON?.message || 'Could not delete user.', 'error');
                }
            });
        });
    </script>
</x-app-layout>
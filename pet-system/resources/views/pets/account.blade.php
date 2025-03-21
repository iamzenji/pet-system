@extends('layouts.app')

@section('content')
    {{-- DISPLAY DATA --}}
    <div class="container">
    {{-- Breadcrumb Navigation --}}
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <h2 class="fw-bold text-success">Registered Accounts</h2>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-success">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Registered Accounts</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive" style="border: 1px solid #ddd; border-radius: 10px; padding: 10px; border-collapse: separate; border-spacing: 0;">
            <table id="accounts-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered At</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
        </div>


        {{-- ADD MODAL ACCOUNT --}}
        <div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAccountModalLabel">Create Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createAccountForm">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Account Type</label>
                                <select class="form-control" id="role" name="role" required>
                                    @foreach(App\Models\Role::all() as $role)
                                        @if($role->id != 1)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDIT USER MODAL -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editUserForm">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="edit-user-id">

                            <div class="mb-3">
                                <label for="edit-user-name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit-user-name" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit-user-email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit-user-email" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit-user-role" class="form-label">Role</label>
                                <select class="form-select" id="edit-user-role">
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        let table = $('#accounts-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "/accounts/data",
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    text: '<i class="bi bi-plus-lg"></i> Add',
                    className: 'btn btn-success',
                    action: function (e, dt, node, config) {
                        $('#addAccountModal').modal('show');
                    }
                },
                {
                    extend: 'copy',
                    text: '<i class="bi bi-clipboard"></i> Copy',
                    className: 'btn btn-success'
                },
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    className: 'btn btn-success'
                },
                {
                    extend: 'csv',
                    text: '<i class="bi bi-file-earmark-text"></i> CSV',
                    className: 'btn btn-success'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    className: 'btn btn-success'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Print',
                    className: 'btn btn-success'
                }
            ],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'role', name: 'role', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-warning btn-sm edit-user"
                                    data-id="${row.id}"
                                    data-name="${row.name}"
                                    data-email="${row.email}"
                                    data-role="${row.role}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editUserModal">
                                    <i class="fas fa-pencil-alt"></i>
                            </button>

                            <button class="btn btn-danger btn-sm delete-user"
                                    data-id="${row.id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        `;
                    }
                }
            ]
        });

        $('#createAccountForm').on('submit', function (e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: "{{ route('accounts.register') }}",
                data: formData,
                success: function (response) {
                    Swal.fire('Success!', response.success, 'success');
                    $('#addAccountModal').modal('hide');
                    $('#createAccountForm')[0].reset();
                    $('#accounts-table').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = Object.values(errors).join('<br>');
                    Swal.fire('Error!', errorMessage, 'error');
                }
            });
        });

        // Open Edit Modal
        $(document).on('click', '.edit-user', function() {
            let userId    = $(this).data('id');
            let userName  = $(this).data('name');
            let userEmail = $(this).data('email');
            let userRole  = $(this).data('role');

            $('#edit-user-id').val(userId);
            $('#edit-user-name').val(userName);
            $('#edit-user-email').val(userEmail);

            let roleDropdown = $('#edit-user-role');
            roleDropdown.empty();

            $.get('{{ url("/roles") }}', function(response) {
                response.roles.forEach(role => {
                    roleDropdown.append(`<option value="${role.id}" ${userRole === role.name ? 'selected' : ''}>${role.name}</option>`);
                });
            });
        });



        // Handle Update Form Submission
        $('#editUserForm').submit(function(event) {
            event.preventDefault();12

            let userId = $('#edit-user-id').val();
            let userName = $('#edit-user-name').val();
            let userEmail = $('#edit-user-email').val();
            let userRole = $('#edit-user-role').val();  // Ensure this is the role ID

            $.ajax({
            url: '{{ url("accounts/update") }}/' + userId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: userName,
                email: userEmail,
                role: userRole // Ensure this is the correct role ID
            },
            success: function(response) {
                $('#editUserModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: response.success
                });
                table.ajax.reload();
            },
            error: function(xhr) {
                console.log(xhr.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: xhr.responseJSON ? xhr.responseJSON.message : 'An unknown error occurred'
                });
            }
        });

        });

        // Handle Delete Button Click
        $(document).on('click', '.delete-user', function() {
            let userId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("accounts/delete") }}/' + userId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.success
                            });
                            table.ajax.reload();
                        }
                    });
                }
            });
        });

        function loadAccounts() {
            $.ajax({
                url: "{{ route('account') }}",
                type: "GET",
                success: function (data) {
                    $('#accountList').html(data);
                }
            });
        }
    });
</script>
@endpush
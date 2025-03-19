@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Breadcrumb Navigation --}}
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h2 class="fw-bold text-success">Roles Management</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-success">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Roles Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="table-responsive" style="border: 1px solid #ddd; border-radius: 10px; padding: 10px; border-collapse: separate; border-spacing: 0;">
        <table id="rolesTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>


<!-- Add Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="roleForm">
                    @csrf
                    <input type="hidden" id="roleId">
                    <div class="mb-3">
                        <label for="name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <span class="text-danger error-message"></span>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm">
                    @csrf
                    <input type="hidden" id="editRoleId">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="editName" name="editName" required>
                        <span class="text-danger edit-error-message"></span>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Delete Role Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this role?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    var domSetup = "<'row'<'col-sm-12 col-md-8'B><'col-sm-12 col-md-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

    const A_LENGTH_MENU = [[10, 25, 50, 100, -1], ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']];
    const TABLE_BUTTONS = [
        {
            text: '<i class="bi bi-plus-lg"></i> Add',
            className: 'btn btn-success',
            action: function () {
                $('#roleForm')[0].reset();
                $('#modalTitle').text('Add Role');
                $('#roleModal').modal('show');
            }
        },
        { extend: 'colvis', className: 'btn btn-success', text: '<i class="fas fa-columns"></i> Column Visibility' },
        { extend: 'pageLength', className: 'btn btn-success' }
    ];

    var specific_table = [

        { extend: 'copy', text: '<i class="bi bi-clipboard"></i> Copy', className: 'btn btn-success' },
        { extend: 'excel', text: '<i class="bi bi-file-earmark-excel"></i> Excel', className: 'btn btn-success' },
        { extend: 'csv', text: '<i class="bi bi-file-earmark-text"></i> CSV', className: 'btn btn-success' },
        { extend: 'pdf', text: '<i class="bi bi-file-earmark-pdf"></i> PDF', className: 'btn btn-success' },
        { extend: 'print', text: '<i class="bi bi-printer"></i> Print', className: 'btn btn-success' }
    ];

    if ($.fn.dataTable.isDataTable('#rolesTable')) {
        $('#rolesTable').DataTable().destroy();
    }

    let table = $('#rolesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('roles.data') }}",
        columns: [
            { data: 'name', name: 'name' },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm edit-role" data-id="${row.id}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <span style="margin-right: 5px;"></span>
                            <button class="btn btn-danger btn-sm delete" data-id="${row.id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        dom: domSetup,
        responsive: true,
        lengthMenu: A_LENGTH_MENU,
        buttons: [...TABLE_BUTTONS, ...specific_table]
    });

    // ADD ROLE
    $('#roleForm').on('submit', function (e) {
        e.preventDefault();
            let name = $('#name').val();
            $.ajax({
                url: "{{ route('roles.store') }}",
                method: "POST",
                data: { name: name },
                success: function () {
                    $('#roleModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire('Success', 'Role added successfully!', 'success');
                },
                error: function (xhr) {
                    $('.error-message').text(xhr.responseJSON.errors.name[0]);
                }
            });
        });

    // EDIT ROLE
    $(document).on('click', '.edit-role', function () {
        let roleId = $(this).data('id');

        $.ajax({
            url: "{{ route('roles.data') }}",
            method: "GET",
            success: function (response) {
                let role = response.data.find(r => r.id == roleId);
                if (role) {
                    $('#editRoleId').val(role.id);
                    $('#editName').val(role.name);
                    $('#editModalTitle').text('Edit Role');
                    $('#editRoleModal').modal('show');
                }
            },
            error: function () {
                Swal.fire('Error!', 'Unable to fetch role details.', 'error');
            }
        });
    });
    // UPDATE ROLE
    $('#editRoleForm').on('submit', function (e) {
        e.preventDefault();
        let roleId = $('#editRoleId').val();
        let name = $('#editName').val();

        $.ajax({
            url: "{{ route('roles.update', '') }}/" + roleId,
            method: "PUT",
            data: { name: name },
            success: function () {
                $('#editRoleModal').modal('hide');
                table.ajax.reload();
                Swal.fire('Updated!', 'Role has been updated.', 'success');
            },
            error: function (xhr) {
                $('.edit-error-message').text(xhr.responseJSON.errors.name[0]);
            }
        });
    });
     // DELETE ROLE
    let deleteId;
        $(document).on('click', '.delete', function () {
                deleteId = $(this).data('id');
                $('#deleteConfirmModal').modal('show');
            });
        $('#confirmDelete').on('click', function () {
                $.ajax({
                    url: "{{ route('roles.delete', '') }}/" + deleteId,
                    method: "DELETE",
                    success: function () {
                        $('#deleteConfirmModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Deleted!', 'Role has been deleted.', 'success');
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            });
        });
</script>
@endpush

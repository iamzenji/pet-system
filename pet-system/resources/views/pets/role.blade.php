@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Roles Management</h2>
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
                                <button class="btn btn-warning btn-sm edit-role" data-id="${row.id}"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn btn-danger btn-sm delete" data-id="${row.id}"><i class="bi bi-trash"></i></button>
                            </div>
                        `;
                    }
                }
            ],
            dom: '<"top"fB>rt<"bottom"lp><"clear">',
            buttons: [
                {
                    text: '<i class="bi bi-plus-lg"></i> Add',
                    className: 'btn btn-success',
                    action: function () {
                        $('#roleForm')[0].reset();
                        $('#modalTitle').text('Add Role');
                        $('#roleModal').modal('show');
                    }
                }
            ]
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

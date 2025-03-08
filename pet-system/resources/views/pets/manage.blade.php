@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage Pet Types & Breeds</h2>

    <table id="typeTable" class="table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Breed</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Add Type Modal -->
<div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Type & Breed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addTypeForm">
                    @csrf
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <input type="text" id="type" name="type" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="breed" class="form-label">Breed</label>
                        <input type="text" id="breed" name="breed" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Type Modal -->
<div class="modal fade" id="editTypeModal" tabindex="-1" aria-labelledby="editTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Type & Breed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editTypeForm">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Type</label>
                        <input type="text" id="edit_type" name="type" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_breed" class="form-label">Breed</label>
                        <input type="text" id="edit_breed" name="breed" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    var domSetup = "<'row'<'col-sm-12 col-md-8'B><'col-sm-12 col-md-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";
    const lengthMenu = [[10, 25, 50, -1], ['10 rows', '25 rows', '50 rows', 'Show all']];

    let table = $('#typeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/types/list",
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        },
        columns: [
            { data: 'type' },
            { data: 'breed' },
            {
                data: null,
                orderable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}" data-type="${row.type}" data-breed="${row.breed}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    `;
                }
            }
        ],
        dom: domSetup,
        lengthMenu: lengthMenu,
        responsive: true,
        buttons: [
            {
                text: '<i class="bi bi-plus-lg"></i> Add',
                className: 'btn btn-secondary',
                action: function () {
                    $('#addTypeModal').modal('show');
                }
            }
        ]
    });

    $('#addTypeForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: "/types",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                $('#addTypeModal').modal('hide');
                table.ajax.reload();
                Swal.fire('Success!', 'Type added successfully!', 'success');
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        let id    = $(this).data('id');
        let type  = $(this).data('type');
        let breed = $(this).data('breed');

        $('#edit_id').val(id);
        $('#edit_type').val(type);
        $('#edit_breed').val(breed);

        $('#editTypeModal').modal('show');
    });

    $('#editTypeForm').submit(function (e) {
        e.preventDefault();
        let id = $('#edit_id').val();

        $.ajax({
            url: `/types/${id}`,
            type: "PUT",
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                $('#editTypeModal').modal('hide');
                table.ajax.reload();
                Swal.fire('Updated!', 'Type updated successfully!', 'success');
            }
        });
    });

    $(document).on('click', '.delete-btn', function () {
        let typeId = $(this).data('id');
        Swal.fire({
            title: "Are you sure you want to delete this type?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/types/${typeId}`,
                    type: "DELETE",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function () {
                        table.ajax.reload();
                        Swal.fire('Deleted!', 'Type deleted successfully!', 'success');
                    }
                });
            }
        });
    });
});
</script>
@endpush

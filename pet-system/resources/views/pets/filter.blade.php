@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Breadcrumb Navigation --}}
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h2 class="fw-bold text-success">Pet Types Table</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-success">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pet Types Table</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="table-responsive" style="border: 1px solid #ddd; border-radius: 10px; padding: 10px; border-collapse: separate; border-spacing: 0;">
        <table id="petTypesTable" class="table table-striped">
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


<!-- Add Modal -->
<div class="modal fade" id="petTypeModal" tabindex="-1" aria-labelledby="petTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Pet Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="petTypeForm">
                    @csrf
                    <input type="hidden" id="petTypeId">
                    <div class="mb-3">
                        <label for="name" class="form-label">Pet Type Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <span class="text-danger error-message"></span>
                    </div>
                    <button type="submit" class="btn btn-primary" id="savePetType">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPetTypeModal" tabindex="-1" aria-labelledby="editPetTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Edit Pet Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPetTypeForm">
                    @csrf
                    <input type="hidden" id="editPetTypeId">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Pet Type Name</label>
                        <input type="text" class="form-control" id="editName" name="editName" required>
                        <span class="text-danger error-message"></span>
                    </div>
                    <button type="submit" class="btn btn-primary" id="updatePetType">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this pet type?</p>
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
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var domSetup = "<'row'<'col-sm-12 col-md-8'B><'col-sm-12 col-md-4'f>>" + 
                   "<'row'<'col-sm-12'tr>>" + 
                   "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

    const A_LENGTH_MENU = [[10, 25, 50, 100, -1], ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']];
    const TABLE_BUTTONS = [
        { extend: 'colvis', className: 'btn btn-success', text: '<i class="fas fa-columns"></i> Column Visibility' },
        { extend: 'pageLength', className: 'btn btn-success' }
    ];

    var specific_table = [
        {
            text: '<i class="bi bi-plus-lg"></i> Add Pet Type',
            className: 'btn btn-success',
            action: function () {
                $('#petTypeForm')[0].reset();
                $('#modalTitle').text('Add Pet Type');
                $('#petTypeModal').modal('show');
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
    ];

    var BUTTONS = $.merge(specific_table, TABLE_BUTTONS);

    let table = $('#petTypesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('pet.types.data') }}",
        responsive: true,
        columns: [
            { data: 'name', name: 'name' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        dom: domSetup,
        buttons: BUTTONS,
        lengthMenu: A_LENGTH_MENU
    });

    // ADD TYPE
    $('#petTypeForm').on('submit', function (e) {
        e.preventDefault();
        let name = $('#name').val();
        $.ajax({
            url: "{{ route('pet.types.store') }}",
            method: "POST",
            data: { name: name },
            success: function (response) {
                $('#petTypeModal').modal('hide');
                table.ajax.reload();
                Swal.fire('Success', 'Pet type added successfully!', 'success');
            },
            error: function (xhr) {
                let error = xhr.responseJSON.errors.name[0];
                $('.error-message').text(error);
            }
        });
    });

    // EDIT TYPE
    $(document).on('click', '.edit', function () {
        let petTypeId = $(this).data('id');

        $.ajax({
            url: "{{ url('/pet-types') }}/" + petTypeId + "/edit",
            method: "GET",
            success: function (response) {
                $('#editPetTypeId').val(response.id);
                $('#editName').val(response.name);
                $('#editPetTypeModal').modal('show');
            },
            error: function () {
                Swal.fire('Error!', 'Failed to fetch pet type details.', 'error');
            }
        });
    });

    // UPDATE TYPE
    $('#editPetTypeForm').on('submit', function (e) {
        e.preventDefault();
        let petTypeId = $('#editPetTypeId').val();
        let name = $('#editName').val();

        $.ajax({
            url: "{{ url('/pet-types/update') }}/" + petTypeId,
            method: "PUT",
            data: { name: name },
            success: function () {
                $('#editPetTypeModal').modal('hide');
                table.ajax.reload();
                Swal.fire('Updated!', 'Pet type updated successfully!', 'success');
            },
            error: function (xhr) {
                let error = xhr.responseJSON.errors.name[0];
                $('.error-message').text(error);
            }
        });
    });

    // DELETE TYPE
    let deleteId;
    $(document).on('click', '.delete', function () {
        deleteId = $(this).data('id');
        $('#deleteConfirmModal').modal('show');
    });

    $('#confirmDelete').on('click', function () {
        $.ajax({
            url: "{{ route('pet.types.delete', '') }}/" + deleteId,
            method: "DELETE",
            success: function () {
                $('#deleteConfirmModal').modal('hide');
                table.ajax.reload();
                Swal.fire('Deleted!', 'Pet type has been deleted.', 'success');
            },
            error: function () {
                Swal.fire('Error!', 'Something went wrong.', 'error');
            }
        });
    });
});

</script>
@endpush

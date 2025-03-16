@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Pet Types Table</h2>
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

        let table = $('#petTypesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('pet.types.data') }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            dom: '<"top"fB>rt<"bottom"lp><"clear">',
            buttons: [
                {
                    text: 'Add Type',
                    className: 'btn btn-secondary',
                    action: function () {
                        $('#petTypeForm')[0].reset();
                        $('#modalTitle').text('Add Pet Type');
                        $('#petTypeModal').modal('show');
                    }
                }
            ]
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

@extends('layouts.app')
@section('content')

<!-- External Scripts & Styles -->
@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
@endpush

<div class="container mt-4">
    <h1 class="text-center">Pet List</h1>

    <!-- Pets Table -->
    <div class="table-responsive">
        <table id="petTable"  class="table table-striped">
            <thead>
                <tr>
                    <th>Pet Type</th>
                    <th>Breed</th>
                    <th>Gender</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Age</th>
                    <th>Weight</th>
                    <th>Image</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
    {{-- Add modal --}}
    <div class="modal " id="addPetModal" tabindex="-1" aria-labelledby="addPetModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create a New Pet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="petForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-select" required>
                                        <option value="">Select Type</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Breed</label>
                                    <select name="breed" class="form-select" required>
                                        <option value="">Select Breed</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gender</label><br>
                                    <input type="radio" name="gender" value="Male" id="male" required>
                                    <label for="male">Male</label><br>
                                    <input type="radio" name="gender" value="Female" id="female" required>
                                    <label for="female">Female</label>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Color</label>
                                    <input type="text" name="color" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Size</label>
                                    <input type="text" name="size" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Age</label>
                                    <input type="number" name="age" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Weight</label>
                                    <input type="number" step="0.01" name="weight" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="d-grid mt-3">
                            <button type="button" class="btn btn-primary" id="confirmSubmit" >
                                Save Pet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPetForm">
                        <input type="hidden" id="editPetId" name="id">
                        <div class="row">
                            <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="">Select Type</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Breed</label>
                                <select name="breed" class="form-select" required>
                                    <option value="">Select Breed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gender</label><br>
                                <input type="radio" name="gender" value="Male" id="male" required>
                                <label for="male">Male</label><br>
                                <input type="radio" name="gender" value="Female" id="female" required>
                                <label for="female">Female</label>
                            </div>
                                <div class="mb-3">
                                    <label for="editColor" class="form-label">Color</label>
                                    <input type="text" class="form-control" id="editColor" name="color">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editSize" class="form-label">Size</label>
                                    <input type="text" class="form-control" id="editSize" name="size">
                                </div>
                                <div class="mb-3">
                                    <label for="editAge" class="form-label">Age</label>
                                    <input type="number" class="form-control" id="editAge" name="age">
                                </div>
                                <div class="mb-3">
                                    <label for="editWeight" class="form-label">Weight</label>
                                    <input type="text" class="form-control" id="editWeight" name="weight">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <label class="form-label">Current Image</label>
                            <br>
                        </div>
                        <div class="mb-3">
                            <label for="editImage" class="form-label">Upload New Image</label>
                            <input type="file" class="form-control" id="editImage" name="image">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Pet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this pet?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger confirm-delete" data-id="${row.id}">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Pet Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Selected Image">
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    var   domSetup      = "<'row'<'col-sm-12 col-md-8'B><'col-sm-12 col-md-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";
    const A_LENGTH_MENU = [[10, 25, 50, 100, -1], ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']];
    let   table         = $('#petTable').DataTable({
    processing: true,
    serverSide: true,
    ajax      : {
        url    : "/pet-list",
        type   : 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    },
    columns: [
        { data: 'type' },
        { data: 'breed' },
        { data: 'gender' },
        { data: 'color' },
        { data: 'size' },
        { data: 'age' },
        { data: 'weight' },
        {
            data      : 'image',
            orderable : false,
            searchable: false,
            render    : function (data, type, row) {
                if (data) {
                    return `<img src="/storage/${data}" width="50" height="50" class="img-thumbnail"
                                    data-bs-toggle = "modal" data-bs-target = "#imageModal"
                                    onclick        = "showImage('/storage/${data}')">`;
                } else {
                    return "No Image";
                }
            }
        },
        {
            data      : null,
            orderable : false,
            searchable: false,
            render    : function (data, type, row) {
                return `
                    <div    class          = "d-flex justify-content-between">
                    <button class          = "btn btn-warning btn-sm edit-btn"
                            data-id        = "${row.id}"
                            data-type      = "${row.type}"
                            data-breed     = "${row.breed}"
                            data-gender    = "${row.gender}"
                            data-color     = "${row.color}"
                            data-size      = "${row.size}"
                            data-age       = "${row.age}"
                            data-weight    = "${row.weight}"
                            data-image     = "${row.image}"
                            data-bs-toggle = "modal"
                            data-bs-target = "#editModal">
                    <i      class          = "bi bi-pencil"></i> Edit
                        </button>

                        <button class          = "btn btn-danger btn-sm delete-btn"
                                data-id        = "${row.id}"
                                data-bs-toggle = "modal"
                                data-bs-target = "#deleteModal">
                        <i      class          = "bi bi-trash"></i> Delete
                        </button>

                    </div>
                `;
            }
        }
    ],
    dom        : domSetup,
    aLengthMenu: A_LENGTH_MENU,
    responsive : true,
    colReorder : true,
    autoWidth  : false,
    bSort      : true,
    paging     : true,
    info       : true,
    ordering   : true,
    searching  : true,
    buttons    : [
        {
            text     : '<i class="bi bi-plus-lg"></i> Add',
            className: 'btn btn-secondary',
            action   : function (e, dt, node, config) {
                $('#addPetModal').modal('show');
            }
        },
        {
            extend   : 'copy',
            text     : '<i class="bi bi-clipboard"></i> Copy',
            className: 'btn btn-secondary'
        },
        {
            extend   : 'excel',
            text     : '<i class="bi bi-file-earmark-excel"></i> Excel',
            className: 'btn btn-secondary'
        },
        {
            extend   : 'csv',
            text     : '<i class="bi bi-file-earmark-text"></i> CSV',
            className: 'btn btn-secondary'
        },
        {
            extend   : 'pdf',
            text     : '<i class="bi bi-file-earmark-pdf"></i> PDF',
            className: 'btn btn-secondary'
        },
        // {
        //     extend   : 'print',
        //     text     : '<i class="bi bi-printer"></i> Print',
        //     className: 'btn btn-secondary'
        // }
    ]
});

// FETCH SELECT TYPE AND BREED
$.ajax({
        url: '{{ route('types.fetch') }}',
        type: 'GET',
        success: function(data) {

            let typeSelect = $('select[name="type"]');
            typeSelect.empty();
            typeSelect.append('<option value="">Select Type</option>');

            $.each(data, function(index, type) {
                typeSelect.append('<option value="' + type.type + '">' + type.type + '</option>');
            });
        },
        error: function() {
            alert('Error fetching types data');
        }
    });

    $('select[name="type"]').on('change', function() {
        let selectedType = $(this).val();
        if (selectedType) {

            $.ajax({
                url: '{{ route('types.fetchBreeds') }}',
                type: 'GET',
                data: { type: selectedType },
                success: function(data) {

                    let breedSelect = $('select[name="breed"]');
                    breedSelect.empty();
                    breedSelect.append('<option value="">Select Breed</option>');

                    $.each(data, function(index, breed) {
                        breedSelect.append('<option value="' + breed + '">' + breed + '</option>');
                    });
                },
                error: function() {
                    alert('Error fetching breeds data');
                }
            });
        } else {
            $('select[name="breed"]').empty();
            $('select[name="breed"]').append('<option value="">Select Breed</option>');
        }
    });

// ADD PET
$('#confirmSubmit').click(function () {
    let formData = new FormData($('#petForm')[0]);
    $('#addPetModal').modal('hide');

    try {
        $.ajax({
            url        : "{{ route('pets.store') }}",
            type       : "POST",
            data       : formData,
            processData: false,
            contentType: false,
            success    : function (response) {
                $('#confirmationModal').modal('hide');
                $('#petForm')[0].reset();

                table.row.add(response.data).draw(false);
                Swal.fire({
                    title            : 'Success!',
                    text             : 'Pet added successfully!',
                    icon             : 'success',
                    confirmButtonText: 'OK'
                });
            },
            error: function (xhr, status, error) {
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                Swal.fire("Error!", `Something went wrong. Error: ${errorMessage}`, "error");
            }
        });
    } catch (e) {
        console.error("Error occurred: ", e);
        Swal.fire("Error!", "An unexpected error occurred while processing the request.", "error");
    }
});

// EDIT
$(document).on("submit", "#editPetForm", function (e) {
    e.preventDefault();

    let petId    = $("#editPetId").val();
    let formData = new FormData(this);
    formData.append("_method", "PUT");

    $.ajax({
        url        : `/pets/${petId}`,
        type       : "POST",
        data       : formData,
        processData: false,
        contentType: false,
        headers    : {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    icon : "success",
                    title: "Updated!",
                    text : "Pet details have been updated successfully.",
                });

                $("#editModal").modal("hide")
                $("#petTable").DataTable().ajax.reload();
            } else {
                Swal.fire("Error!", "Failed to update pet details.", "error");
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            Swal.fire("Error!", "Something went wrong.", "error");
        },
    });
});

  // FETCH DATA
$(document).on('click', '.edit-btn', function () {
    let petData = $(this).data();

    $('#editPetId').val(petData.id);
    $('#editColor').val(petData.color);
    $('#editSize').val(petData.size);
    $('#editAge').val(petData.age);
    $('#editWeight').val(petData.weight);
    $('#editImage').val("");

    if (petData.gender === 'Male') {
        $('#male').prop('checked', true);
    } else if (petData.gender === 'Female') {
        $('#female').prop('checked', true);
    }

    $('select[name="type"]').val(petData.type).trigger('change');

    $('select[name="breed"]').val(petData.breed);
});

// SELECT TYPE FETCH
$('select[name="type"]').on('change', function() {
    let selectedType = $(this).val();
    if (selectedType) {
        $.ajax({
            url: '{{ route('types.fetchBreeds') }}',
            type: 'GET',
            data: { type: selectedType },
            success: function(data) {
                let breedSelect = $('select[name="breed"]');
                breedSelect.empty();
                breedSelect.append('<option value="">Select Breed</option>');
                $.each(data, function(index, breed) {
                    breedSelect.append('<option value="' + breed + '">' + breed + '</option>');
                });
            },
            error: function() {
                alert('Error fetching breeds data');
            }
        });
    } else {
        $('select[name="breed"]').empty().append('<option value="">Select Breed</option>');
    }
});

// DELETE
$(document).on('click', '.delete-btn', function () {
    let petId = $(this).data('id');
    $('.confirm-delete').data('id', petId);
});

$(document).on('click', '.confirm-delete', function () {
    let petId = $(this).data('id');

    $.ajax({
        url    : `/pets/${petId}`,
        type   : 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $('#deleteModal').modal('hide');
            Swal.fire("Deleted!", "Pet has been deleted.", "success");
            $('#petTable').DataTable().ajax.reload();
        },
        error: function (xhr, status, error) {
            let errorMessage = xhr.status + ': ' + xhr.statusText;
            Swal.fire("Error!", `Something went wrong. Error: ${errorMessage}`, "error");
        }
    });
});

return table;
});

</script>
@endpush

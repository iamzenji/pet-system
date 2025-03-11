@extends('layouts.app')
@section('content')


    {{-- DISPLAY DATA --}}
    <div class="container mt-4">
        <h1 class="text-center">Pet List</h1>
        <div class="table-responsive">
            <table id="petTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Pet Type</th>
                        <th>Breed</th>
                        <th>Gender</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Age</th>
                        <th>Weight</th>
                        <th>Temperament</th>
                        <th>Health Status</th>
                        <th>Spayed/Neutered</th>
                        <th>Vaccination Status</th>
                        <th>Good With</th>
                        <th>Adoption Status</th>
                        <th>Image</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ADD MODAL --}}
<div class="modal" id="addPetModal" tabindex="-1" aria-labelledby="addPetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <i class="fa-solid fa-dog"></i> Create a New Pet
                </h5>
                <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="petForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            @php
                                $types = DB::table('types')->select('type', 'breed')->get()->groupBy('type');
                            @endphp
                            <div class="mb-3">
                                <label class="form-label"><b>Type</b></label>
                                <select name="type" id="typeSelect" class="form-select" required>
                                    <option value="">Select Type</option>
                                    @foreach($types as $type => $breeds)
                                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Breed</b></label>
                                <select name="breed" id="breedSelect" class="form-select" required>
                                    <option value="">Select Breed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Gender</b></label><br>
                                <input type="radio" name="gender" value="Male" required> Male
                                <input type="radio" name="gender" value="Female" required> Female
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Color</b></label>
                                <input type="text" name="color" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Health Status</b></label>
                                <select name="health_status" class="form-select" required>
                                    <option value="Healthy">Healthy</option>
                                    <option value="Under Treatment">Under Treatment</option>
                                    <option value="Special Needs">Special Needs</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Spayed/Neutered</b></label>
                                <select name="spayed_neutered" class="form-select" required>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Image</b></label>
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><b>Size</b></label>
                                <input type="text" name="size" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Age</b></label>
                                <input type="number" name="age" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Weight</b></label>
                                <input type="number" step="0.01" name="weight" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Vaccination Status</b></label>
                                <select name="vaccination_status" class="form-select" required>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Good With</b></label>
                                <select name="good_with" class="form-select" required>
                                    <option value="Kids">Kids</option>
                                    <option value="Other Pets">Other Pets</option>
                                    <option value="Seniors">Seniors</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><b>Adoption Status</b></label>
                                <select name="adoption_status" class="form-select" required>
                                    <option value="Available">Available</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Adopted">Adopted</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid mt-3">
                        <button type="button" class="btn btn-primary" id="confirmSubmit">Save Pet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-dog"></i> Edit Pet
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(30%) sepia(89%) saturate(7489%) hue-rotate(360deg) brightness(92%) contrast(125%);">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editPetForm">
                        <input type="hidden" id="editPetId" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Type</label>
                                    <select id="editType" name="type" class="form-select" required>
                                        <option value="">Select Type</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Breed</label>
                                    <select id="editBreed" name="breed" class="form-select" required>
                                        <option value="">Select Breed</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Gender</label><br>
                                    <input type="radio" name="gender" value="Male" id="editMale" required>
                                    <label for="editMale">Male</label><br>
                                    <input type="radio" name="gender" value="Female" id="editFemale" required>
                                    <label for="editFemale">Female</label>
                                </div>
                                <div class="mb-3">
                                    <label for="editColor" class="form-label fw-bold">Color</label>
                                    <input type="text" class="form-control" id="editColor" name="color">
                                </div>
                                <div class="mb-3">
                                    <label for="editSize" class="form-label fw-bold">Size</label>
                                    <input type="text" class="form-control" id="editSize" name="size">
                                </div>
                                <div class="mb-3">
                                    <label for="editAge" class="form-label fw-bold">Age</label>
                                    <input type="number" class="form-control" id="editAge" name="age">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editWeight" class="form-label fw-bold">Weight</label>
                                    <input type="text" class="form-control" id="editWeight" name="weight">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Health Status</label>
                                    <select id="editHealthStatus" name="health_status" class="form-select">
                                        <option value="Healthy">Healthy</option>
                                        <option value="Under Treatment">Under Treatment</option>
                                        <option value="Special Needs">Special Needs</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Spayed/Neutered</label>
                                    <select id="editSpayedNeutered" name="spayed_neutered" class="form-select">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Vaccination Status</label>
                                    <select id="editVaccinationStatus" name="vaccination_status" class="form-select">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Good With</label>
                                    <select id="editGoodWith" name="good_with" class="form-select">
                                        <option value="Kids">Kids</option>
                                        <option value="Other Pets">Other Pets</option>
                                        <option value="Seniors">Seniors</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Adoption Status</label>
                                    <select id="editAdoptionStatus" name="adoption_status" class="form-select">
                                        <option value="Available">Available</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Adopted">Adopted</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <label class="form-label fw-bold">Current Image</label>
                            <br>
                            <img id="editCurrentImage" src="#" alt="Pet Image" class="img-fluid rounded" style="max-height: 150px;">
                        </div>
                        <div class="mb-3">
                            <label for="editImage" class="form-label fw-bold">Upload New Image</label>
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


    {{-- DELETE MODAL --}}
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

    {{-- Image modal - needed to fix --}}
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
                { data: 'temperament' },
                { data: 'health_status' },
                { data: 'spayed_neutered' },
                { data: 'vaccination_status' },
                { data: 'good_with' },
                { data: 'adoption_status' },
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
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-warning btn-sm edit-btn"
                                        data-id="${row.id}"
                                        data-type="${row.type}"
                                        data-breed="${row.breed}"
                                        data-gender="${row.gender}"
                                        data-color="${row.color}"
                                        data-size="${row.size}"
                                        data-age="${row.age}"
                                        data-weight="${row.weight}"
                                        data-temperament="${row.temperament}"
                                        data-health_status="${row.health_status}"
                                        data-spayed_neutered="${row.spayed_neutered}"
                                        data-vaccination_status="${row.vaccination_status}"
                                        data-good_with="${row.good_with}"
                                        data-adoption_status="${row.adoption_status}"
                                        data-image="${row.image}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>

                                <button class="btn btn-danger btn-sm delete-btn"
                                        data-id="${row.id}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                    <i class="bi bi-trash"></i> Delete
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
                    action   : function () {
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
                {
                    extend   : 'print',
                    text     : '<i class="bi bi-printer"></i> Print',
                    className: 'btn btn-secondary'
                }
            ]
        });

    // ADD PET
    $('#confirmSubmit').click(function () {
    let formData = new FormData($('#petForm')[0]);

    $.ajax({
        url: "{{ route('pets.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $('#addPetModal').modal('hide');
            $('#petForm')[0].reset();
            table.row.add(response.data).draw(false);

            Swal.fire({
                title: 'Success!',
                text: 'Pet added successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        },
        error: function (xhr) {
            console.error(xhr.responseJSON);
            Swal.fire("Error!", `Something went wrong: ${xhr.responseJSON.message}`, "error");
        }
    });
});


        // EDIT PET
        $(document).on('click', '.edit-btn', function () {
            let petData = $(this).data();

            $('#editPetId').val(petData.id);
            $('#editColor').val(petData.color);
            $('#editSize').val(petData.size);
            $('#editAge').val(petData.age);
            $('#editWeight').val(petData.weight);
            $('#editTemperament').val(petData.temperament);
            $('#editHealthStatus').val(petData.health_status);
            $('#editSpayedNeutered').val(petData.spayed_neutered);
            $('#editVaccinationStatus').val(petData.vaccination_status);
            $('#editGoodWith').val(petData.good_with);
            $('#editAdoptionStatus').val(petData.adoption_status);
            $('#editImage').val("");

            if (petData.gender === 'Male') {
                $('#male').prop('checked', true);
            } else if (petData.gender === 'Female') {
                $('#female').prop('checked', true);
            }

            $('select[name="type"]').val(petData.type).trigger('change');
            $('select[name="breed"]').val(petData.breed);
        });

        // DELETE PET
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
                success: function () {
                    $('#deleteModal').modal('hide');
                    Swal.fire("Deleted!", "Pet has been deleted.", "success");
                    $('#petTable').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    let errorMessage = xhr.status + ': ' + xhr.statusText;
                    Swal.fire("Error!", `Something went wrong. Error: ${errorMessage}`, "error");
                }
            });
        });

        return table;
    });

    document.addEventListener("DOMContentLoaded", function() {
        const typeSelect = document.getElementById("typeSelect");
        const breedSelect = document.getElementById("breedSelect");
        const breedsData = @json($types);

        typeSelect.addEventListener("change", function() {
            const selectedType = this.value;
            breedSelect.innerHTML = '<option value="">Select Breed</option>';

            if (breedsData[selectedType]) {
                breedsData[selectedType].forEach(function(breed) {
                    const option = document.createElement("option");
                    option.value = breed.breed;
                    option.textContent = breed.breed;
                    breedSelect.appendChild(option);
                });
            }
        });
    });
</script>

@endpush

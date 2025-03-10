// MANAGE PET TYPES
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
                        <button class="btn btn-danger btn-sm delete-type" data-id="${row.id}">
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

    $(document).on('click', '.delete-type', function () {
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

// ACCOUNT
$(document).ready(function () {
    let table = $('#accounts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/accounts/data",
        dom: 'Bfrtip',
        buttons: [
            {
                text: '<i class="bi bi-plus-lg"></i> Add',
                className: 'btn btn-secondary',
                action: function (e, dt, node, config) {
                    $('#addAccountModal').modal('show');
                }
            },
            {
                extend: 'copy',
                text: '<i class="bi bi-clipboard"></i> Copy',
                className: 'btn btn-secondary'
            },
            {
                extend: 'excel',
                text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                className: 'btn btn-secondary'
            },
            {
                extend: 'csv',
                text: '<i class="bi bi-file-earmark-text"></i> CSV',
                className: 'btn btn-secondary'
            },
            {
                extend: 'pdf',
                text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                className: 'btn btn-secondary'
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer"></i> Print',
                className: 'btn btn-secondary'
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
                            Edit
                        </button>

                        <button class="btn btn-danger btn-sm delete-user"
                                data-id="${row.id}">
                            Delete
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

// PETS
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
        {
            extend   : 'print',
            text     : '<i class="bi bi-printer"></i> Print',
            className: 'btn btn-secondary'
        }
    ]
});

// FETCH SELECT TYPE AND BREED
$.ajax({
        url: "/types/fetch",
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
                url: "/types/fetch-breeds",
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
            url: "/types/fetch-breeds",
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
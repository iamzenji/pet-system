import './bootstrap';
// $(document).ready(function () {
//     var   domSetup      = "<'row'<'col-sm-12 col-md-8'B><'col-sm-12 col-md-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";
//     const A_LENGTH_MENU = [[10, 25, 50, 100, -1], ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']];
//     let   table         = $('#petTable').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax      : {
//             url    : "/pet-list",
//             type   : 'GET',
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//         },
//         columns: [
//             { data: 'type' },
//             { data: 'breed' },
//             { data: 'gender' },
//             { data: 'color' },
//             { data: 'size' },
//             { data: 'age' },
//             { data: 'weight' },
//             {
//                 data      : 'image',
//                 orderable : false,
//                 searchable: false,
//                 render    : function (data, type, row) {
//                     if (data) {
//                         return `<img src="/storage/${data}" width="50" height="50" class="img-thumbnail"
//                                         data-bs-toggle = "modal" data-bs-target = "#imageModal"
//                                         onclick        = "showImage('/storage/${data}')">`;
//                     } else {
//                         return "No Image";
//                     }
//                 }
//             },
//             {
//                 data      : null,
//                 orderable : false,
//                 searchable: false,
//                 render    : function (data, type, row) {
//                     return `
//                         <div    class          = "d-flex justify-content-between">
//                         <button class          = "btn btn-warning btn-sm edit-btn"
//                                 data-id        = "${row.id}"
//                                 data-type      = "${row.type}"
//                                 data-breed     = "${row.breed}"
//                                 data-gender    = "${row.gender}"
//                                 data-color     = "${row.color}"
//                                 data-size      = "${row.size}"
//                                 data-age       = "${row.age}"
//                                 data-weight    = "${row.weight}"
//                                 data-image     = "${row.image}"
//                                 data-bs-toggle = "modal"
//                                 data-bs-target = "#editModal">
//                         <i      class          = "bi bi-pencil"></i> Edit
//                             </button>

//                             <button class          = "btn btn-danger btn-sm delete-btn"
//                                     data-id        = "${row.id}"
//                                     data-bs-toggle = "modal"
//                                     data-bs-target = "#deleteModal">
//                             <i      class          = "bi bi-trash"></i> Delete
//                             </button>

//                         </div>
//                     `;
//                 }
//             }
//         ],
//         dom        : domSetup,
//         aLengthMenu: A_LENGTH_MENU,
//         responsive : true,
//         colReorder : true,
//         autoWidth  : false,
//         bSort      : true,
//         paging     : true,
//         info       : true,
//         ordering   : true,
//         searching  : true,
//         buttons    : [
//             {
//                 text     : '<i class="bi bi-plus-lg"></i> Add',
//                 className: 'btn btn-secondary',
//                 action   : function (e, dt, node, config) {
//                     $('#addPetModal').modal('show');
//                 }
//             },
//             {
//                 extend   : 'copy',
//                 text     : '<i class="bi bi-clipboard"></i> Copy',
//                 className: 'btn btn-secondary'
//             },
//             {
//                 extend   : 'excel',
//                 text     : '<i class="bi bi-file-earmark-excel"></i> Excel',
//                 className: 'btn btn-secondary'
//             },
//             {
//                 extend   : 'csv',
//                 text     : '<i class="bi bi-file-earmark-text"></i> CSV',
//                 className: 'btn btn-secondary'
//             },
//             {
//                 extend   : 'pdf',
//                 text     : '<i class="bi bi-file-earmark-pdf"></i> PDF',
//                 className: 'btn btn-secondary'
//             },
//             {
//                 extend   : 'print',
//                 text     : '<i class="bi bi-printer"></i> Print',
//                 className: 'btn btn-secondary'
//             }
//         ]

//     });

//     function setDeleteAction(petId) {
//         let form        = document.getElementById('deleteForm');
//             form.action = "/pets/" + petId;
//     }
//       //delete without reload

//     $(document).on("submit", "#editPetForm", function (e) {
//         e.preventDefault();

//         let petId    = $("#editPetId").val();
//         let formData = new FormData(this);
//         formData.append("_method", "PUT");

//         $.ajax({
//             url        : `/pets/${petId}`,
//             type       : "POST",
//             data       : formData,
//             processData: false,
//             contentType: false,
//             headers    : {
//                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//             },
//             success: function (response) {
//                 if (response.success) {
//                     Swal.fire({
//                         icon : "success",
//                         title: "Updated!",
//                         text : "Pet details have been updated successfully.",
//                     });

//                     $("#editModal").modal("hide")
//                     $("#petTable").DataTable().ajax.reload();
//                 } else {
//                     Swal.fire("Error!", "Failed to update pet details.", "error");
//                 }
//             },
//             error: function (xhr) {
//                 console.error(xhr.responseText);
//                 Swal.fire("Error!", "Something went wrong.", "error");
//             },
//         });
//     });

//     $('#confirmSubmit').click(function () {
//         let formData = new FormData($('#petForm')[0]);
//         $('#addPetModal').modal('hide');
//         $.ajax({
//             url        : "{{ route('pets.store') }}",
//             type       : "POST",
//             data       : formData,
//             processData: false,
//             contentType: false,
//             success    : function (response) {

//                 $('#confirmationModal').modal('hide');
//                 $('#petForm')[0].reset();

//                 table.row.add(response.data).draw(false);
//                 Swal.fire({
//                     title            : 'Success!',
//                     text             : 'Pet added successfully!',
//                     icon             : 'success',
//                     confirmButtonText: 'OK'
//                 });

//             },
//             error: function (xhr) {
//                   //  apply error message  try catch status
//                 console.error(xhr.responseText);
//             }
//         });
//     });

//       // fetch data
//     $(document).on("click", ".edit-btn", function () {
//         $("#editPetId").val(this.getAttribute('data-id'));
//         $("#editType").val(this.getAttribute('data-type'));
//         $("#editBreed").val(this.getAttribute('data-breed'));
//         $("#editGender").val(this.getAttribute('data-gender'));
//         $("#editColor").val(this.getAttribute('data-color'));
//         $("#editSize").val(this.getAttribute('data-size'));
//         $("#editAge").val(this.getAttribute('data-age'));
//         $("#editWeight").val(this.getAttribute('data-weight'));

//         let image = this.getAttribute('data-image');
//         if (image) {
//             $("#editPetImage").attr("src", "/storage/" + image);
//         } else {
//             $("#editPetImage").attr("src", "/default-placeholder.jpg");
//         }
//         $("#editModal").modal("show");
//     });

//     $(document).on('click', '.delete-btn', function () {
//         let petId = $(this).data('id');
//         $('.confirm-delete').data('id', petId);
//     });

//     $(document).on('click', '.confirm-delete', function () {
//         let petId = $(this).data('id');

//         $.ajax({
//             url    : `/pets/${petId}`,
//             type   : 'DELETE',
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             success: function (response) {
//                 $('#deleteModal').modal('hide');
//                 Swal.fire("Deleted!", "Pet has been deleted.", "success");
//                 $('#petTable').DataTable().ajax.reload();
//             }
//               // apply error message  try catch status
//         });
//     });

//     return table;
// });




// $(document).ready(function () {
//     var domSetup = "<'row'<'col-sm-12 col-md-8'B><'col-sm-12 col-md-4'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";
//     const lengthMenu = [[10, 25, 50, -1], ['10 rows', '25 rows', '50 rows', 'Show all']];

//     let table = $('#typeTable').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url: "/types/list",
//             type: "GET",
//             headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
//         },
//         columns: [
//             { data: 'type' },
//             { data: 'breed' },
//             {
//                 data: null,
//                 orderable: false,
//                 render: function (data, type, row) {
//                     return `
//                         <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}" data-type="${row.type}" data-breed="${row.breed}">
//                             <i class="bi bi-pencil"></i> Edit
//                         </button>
//                         <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
//                             <i class="bi bi-trash"></i> Delete
//                         </button>
//                     `;
//                 }
//             }
//         ],
//         dom: domSetup,
//         lengthMenu: lengthMenu,
//         responsive: true,
//         buttons: [
//             {
//                 text: '<i class="bi bi-plus-lg"></i> Add',
//                 className: 'btn btn-secondary',
//                 action: function () {
//                     $('#addTypeModal').modal('show');
//                 }
//             }
//         ]
//     });

//     // ADD TYPE
//     $('#addTypeForm').submit(function (e) {
//         e.preventDefault();

//         try {
//             $.ajax({
//                 url: "/types",
//                 type: "POST",
//                 data: $(this).serialize(),
//                 success: function (response) {
//                     $('#addTypeModal').modal('hide');
//                     table.ajax.reload();
//                     Swal.fire('Success!', 'Type added successfully!', 'success');
//                 },
//                 error: function (xhr, status, error) {
//                     let errorMessage = xhr.status + ': ' + xhr.statusText;
//                     Swal.fire("Error!", `Something went wrong. Error: ${errorMessage}`, "error");
//                 }
//             });
//         } catch (e) {
//             console.error("Error occurred: ", e);
//             Swal.fire("Error!", "An unexpected error occurred while processing the request.", "error");
//         }
//     });

//     $(document).on('click', '.edit-btn', function () {
//         let id    = $(this).data('id');
//         let type  = $(this).data('type');
//         let breed = $(this).data('breed');

//         $('#edit_id').val(id);
//         $('#edit_type').val(type);
//         $('#edit_breed').val(breed);

//         $('#editTypeModal').modal('show');
//     });

//     // EDIT TYPE
//     $('#editTypeForm').submit(function (e) {
//         e.preventDefault();
//         let id = $('#edit_id').val();
//         try {
//             $.ajax({
//                 url: `/types/${id}`,
//                 type: "PUT",
//                 data: $(this).serialize(),
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//                 success: function (response) {
//                     $('#editTypeModal').modal('hide');
//                     table.ajax.reload();
//                     Swal.fire('Updated!', 'Type updated successfully!', 'success');
//                 },
//                 error: function (xhr, status, error) {
//                     let errorMessage = xhr.status + ': ' + xhr.statusText;
//                     Swal.fire("Error!", `Something went wrong. Error: ${errorMessage}`, "error");
//                 }
//             });
//         } catch (e) {
//             console.error("Error occurred: ", e);
//             Swal.fire("Error!", "An unexpected error occurred while processing the request.", "error");
//         }
//     });


//     $(document).on('click', '.delete-btn', function () {
//         let typeId = $(this).data('id');
//         try {
//             Swal.fire({
//                 title: "Are you sure you want to delete this type?",
//                 icon: "warning",
//                 showCancelButton: true,
//                 confirmButtonColor: "#d33",
//                 cancelButtonColor: "#3085d6",
//                 confirmButtonText: "Yes"
//             }).then((result) => {
//                 if (result.isConfirmed) {
//                     $.ajax({
//                         url: `/types/${typeId}`,
//                         type: "DELETE",
//                         headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
//                         success: function () {
//                             table.ajax.reload();
//                             Swal.fire('Deleted!', 'Type deleted successfully!', 'success');
//                         },
//                         error: function (xhr, status, error) {
//                             let errorMessage = xhr.status + ': ' + xhr.statusText;
//                             Swal.fire("Error!", `Something went wrong. Error: ${errorMessage}`, "error");
//                         }
//                     });
//                 }
//             });
//         } catch (e) {
//             console.error("Error occurred: ", e);
//             Swal.fire("Error!", "An unexpected error occurred while processing the request.", "error");
//         }
//     });
// });
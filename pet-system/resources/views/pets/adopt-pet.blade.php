@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Breadcrumb Navigation --}}
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h2 class="fw-bold text-success">Adoption Requests</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-success">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Adoption Requests</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="table-responsive" style="border: 1px solid #ddd; border-radius: 10px; padding: 10px; border-collapse: separate; border-spacing: 0;">
        <table id="adoptPetTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Pet ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Reason</th>
                    <th>Experience</th>
                    <th style="width: 120px" >Status</th>
                    <th style="width: 120px" >Adopted Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // DISPLAY DATA
    let table = $('#adoptPetTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('adoptions.list') }}",
        dom: "<'row'<'col-sm-12 col-md-8'B><'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        lengthMenu: [[10, 25, 50, 100, -1], ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']],
        buttons: [
            { extend: 'colvis', className: 'btn btn-success', text: '<i class="fas fa-columns"></i> Column Visibility' },
            { extend: 'pageLength', className: 'btn btn-success' },
            { extend: 'copy', className: 'btn btn-success', text: '<i class="fa fa-copy"></i> Copy' },
            { extend: 'excel', className: 'btn btn-success', text: '<i class="fa fa-file-excel"></i> Excel' },
            { extend: 'csv', className: 'btn btn-success', text: '<i class="fa fa-file-csv"></i> CSV' },
            { extend: 'pdf', className: 'btn btn-success', text: '<i class="fa fa-file-pdf"></i> PDF' },
            { extend: 'print', className: 'btn btn-success', text: '<i class="fa fa-print"></i> Print' }
        ],
        columns: [
            { data: 'pet_details', name: 'pet_details', title: 'Pet #' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'contact', name: 'contact' },
            { data: 'address', name: 'address' },
            { data: 'reason', name: 'reason' },
            { data: 'experience', name: 'experience' },
            { data: 'status', name: 'status', render: function (data, type, row) {
                return `
                    <select class="form-select update-status" data-id="${row.id}">
                        <option value="Pending" ${data === 'Pending' ? 'selected' : ''}>Pending</option>
                        <option value="Approved" ${data === 'Approved' ? 'selected' : ''}>Approved</option>
                        <option value="Rejected" ${data === 'Rejected' ? 'selected' : ''}>Rejected</option>
                    </select>`;
            }},
            { data: 'adopted_date', name: 'adopted_date', render: function (data, type, row) {
                return `<input type="date" class="form-control update-date" data-id="${row.id}" value="${data || ''}">`;
            }},
            {
                data: null,
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>`;
                }
            }
        ]
    });

    // Handle status update
    $(document).on('change', '.update-status', function () {
        let id = $(this).data('id');
        let status = $(this).val();

        $.ajax({
            url: `/adoptions/${id}/status`,
            type: 'PATCH',
            data: { status: status, _token: "{{ csrf_token() }}" },
            success: function (response) {
                Swal.fire("Success!", "Status updated successfully.", "success");
                table.ajax.reload();
            },
            error: function () {
                Swal.fire("Error!", "Failed to update status.", "error");
            }
        });
    });

    // Handle adopted date update
    $(document).on('change', '.update-date', function () {
        let id = $(this).data('id');
        let adoptedDate = $(this).val();

        $.ajax({
            url: `/adoptions/${id}/adopted-date`,
            type: 'PATCH',
            data: { adopted_date: adoptedDate, _token: "{{ csrf_token() }}" },
            success: function (response) {
                Swal.fire("Success!", "Adopted date updated successfully.", "success");
                table.ajax.reload();
            },
            error: function () {
                Swal.fire("Error!", "Failed to update adopted date.", "error");
            }
        });
    });

    // Delete adoption request
    $(document).on('click', '.delete-btn', function () {
        let id = $(this).data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/adoptions/${id}`,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function () {
                        Swal.fire("Deleted!", "Adoption request has been deleted.", "success");
                        table.ajax.reload();
                    }
                });
            }
        });
    });
});

</script>
@endpush
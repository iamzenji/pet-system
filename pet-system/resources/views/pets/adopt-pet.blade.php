@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Adoption Requests</h2>

    <table id="adoptPetTable" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pet ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Reason</th>
                <th>Experience</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>

<!-- View Adoption Modal -->
<div class="modal fade" id="viewAdoptionModal" tabindex="-1" aria-labelledby="viewAdoptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAdoptionModalLabel">Adoption Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="modalName"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Contact:</strong> <span id="modalContact"></span></p>
                <p><strong>Address:</strong> <span id="modalAddress"></span></p>
                <p><strong>Reason:</strong> <span id="modalReason"></span></p>
                <p><strong>Experience:</strong> <span id="modalExperience"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let table = $('#adoptPetTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('adoptions.list') }}",
        dom: "<'row'<'col-md-6'B><'col-md-6'f>>" +
             "<'row'<'col-md-12'tr>>" +
             "<'row'<'col-md-5'i><'col-md-7'p>>",
        lengthMenu: [[10, 25, 50, -1], ['10 rows', '25 rows', '50 rows', 'Show all']],
        buttons: [
            { extend: 'copy', className: 'btn btn-secondary', text: '<i class="fa fa-copy"></i> Copy' },
            { extend: 'excel', className: 'btn btn-secondary', text: '<i class="fa fa-file-excel"></i> Excel' },
            { extend: 'csv', className: 'btn btn-secondary', text: '<i class="fa fa-file-csv"></i> CSV' },
            { extend: 'pdf', className: 'btn btn-secondary', text: '<i class="fa fa-file-pdf"></i> PDF' },
            { extend: 'print', className: 'btn btn-secondary', text: '<i class="fa fa-print"></i> Print' }
        ],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'pet_id', name: 'pet_id' },
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
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
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

    // View details in modal
    $(document).on('click', '.view-btn', function () {
        let data = $(this).data();
        $('#modalName').text(data.name);
        $('#modalEmail').text(data.email);
        $('#modalContact').text(data.contact);
        $('#modalAddress').text(data.address);
        $('#modalReason').text(data.reason);
        $('#modalExperience').text(data.experience);
        $('#modalStatus').text(data.status);
        $('#viewAdoptionModal').modal('show');
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

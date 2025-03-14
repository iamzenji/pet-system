@extends('layouts.app')
@section('content')

{{-- DISPLAY DATA --}}
<div class="container">
    <h2>Manage Pet Types & Breeds</h2>
    <div class="table-responsive" style="border: 1px solid #ddd; border-radius: 10px; padding: 10px; border-collapse: separate; border-spacing: 0;">
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


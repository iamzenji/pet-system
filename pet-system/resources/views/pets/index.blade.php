@extends('layouts.app')
@section('content')


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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Adoption</title>
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4 fw-bold text-success">Available Pets for Adoption</h1>

        {{-- FILTER --}}
        <div class="d-flex justify-content-center gap-2 mb-4">
            <button class="btn btn-success active filter-btn rounded-pill px-4" data-filter="all">All</button>

            @php
                $petTypes = \App\Models\Types::all(); // Fetch pet types directly in the view
            @endphp

            @foreach($petTypes as $type)
                <button class="btn btn-outline-success filter-btn rounded-pill px-4" data-filter="{{ strtolower($type->name) }}">
                    {{ ucfirst($type->name) }}
                </button>
            @endforeach
        </div>

        {{-- PET LIST CARD --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
            @foreach($pets as $pet)
                @if($pet->adoption_status === 'Available')
                    <div class="col pet-card" data-category="{{ strtolower($pet->type) }}">
                        <div class="card shadow h-100 d-flex flex-column border-0  overflow-hidden">
                            {{-- Image Section --}}
                            <div class="position-relative">
                                <img src="{{ $pet->image ? asset('storage/' . $pet->image) : asset('images/default-pet.jpg') }}"
                                    class="card-img-top img-fluid w-100 rounded-top"
                                    style="height: 230px; object-fit: cover;">
                                <span class="position-absolute top-0 start-0 bg-success text-white px-3 py-1 rounded-bottom small">
                                    Available
                                </span>
                            </div>

                            {{-- Card Body --}}
                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="text-success fw-bold mb-1">
                                    {{ strtoupper(substr($pet->type, 0, 1)) }}{{ strtoupper(substr($pet->breed, 0, 1)) }}{{ strtoupper(substr($pet->gender, 0, 1)) }}-{{ strtoupper(substr($pet->color, 0, 1)) }}{{ strtoupper(substr($pet->size, 0, 1)) }}{{ $pet->age }}-{{ $pet->id }}
                                </h6>
                                <h6 class="text-secondary fw-semibold mb-2">{{ ucfirst($pet->type) }} - {{ $pet->breed }}</h6>

                                {{-- Pet Details --}}
                                @php
                                    $colorMap = [
                                        'golden retriever' => 'goldenrod',
                                        'black' => 'black',
                                        'white' => 'white',
                                        'brown' => 'saddlebrown',
                                        'gray' => 'gray',
                                        'golden' => 'gold',
                                        'red' => 'red',
                                        'cream brown' => 'saddlebrown'
                                    ];
                                    $petColor = strtolower($pet->color);
                                    $backgroundColor = $colorMap[$petColor] ?? 'gray';

                                    $textColor = (in_array($backgroundColor, ['white', 'gold', 'goldenrod'])) ? 'black' : 'white';

                                    $textShadow = ($textColor === 'black') ? 'text-shadow: 1px 1px 2px rgba(0,0,0,0.5);' : '';

                                    $boxShadow = 'box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);';

                                    $gender = strtolower($pet->gender);
                                    $genderIcon = $gender === 'male' ? 'bi-gender-male' : 'bi-gender-female';
                                    $genderColor = $gender === 'male' ? 'text-primary' : '';
                                    $genderStyle = $gender === 'female' ? 'color: pink;' : '';
                                @endphp

                                <ul class="list-unstyled text-muted small flex-grow-1">
                                    <li>
                                        <strong>Gender:</strong>
                                        {{ ucfirst($pet->gender) }}
                                        <i class="bi {{ $genderIcon }} {{ $genderColor }}" style="{{ $genderStyle }}"></i>
                                    </li>
                                    <li>
                                        <strong>Color:</strong>
                                        <small class="d-inline px-3 py-1 rounded-4"
                                            style="background-color: {{ $backgroundColor }};
                                                color: {{ $textColor }};
                                                {{ $textShadow }}
                                                {{ $boxShadow }}">
                                            {{ ucfirst($pet->color) }}
                                        </small>
                                    </li>
                                    <li><strong>Size:</strong> {{ ucfirst($pet->size) }}</li>
                                    <li><strong>Age:</strong> {{ $pet->age }} years</li>
                                </ul>

                                {{-- Adopt Button --}}
                                <button class="btn btn-success w-100 fw-bold mt-auto rounded-pill adopt-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#adoptModal"
                                    data-id="{{ $pet->id }}"
                                    data-type="{{ $pet->type }}"
                                    data-breed="{{ $pet->breed }}"
                                    data-gender="{{ ucfirst($pet->gender) }}"
                                    data-color="{{ ucfirst($pet->color) }}"
                                    data-size="{{ ucfirst($pet->size) }}"
                                    data-age="{{ $pet->age }}"
                                    data-weight="{{ $pet->weight }}"
                                    data-image="{{ $pet->image ? asset('storage/' . $pet->image) : asset('images/default-pet.jpg') }}"
                                    data-temperament="{{ $pet->temperament }}"
                                    data-health_status="{{ $pet->health_status }}"
                                    data-spayed_neutered="{{ $pet->spayed_neutered }}"
                                    data-vaccination_status="{{ $pet->vaccination_status }}"
                                    data-good_with="{{ $pet->good_with }}"
                                    data-adoption_status="{{ $pet->adoption_status }}">
                                    🐶 Adopt Now
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ADOPT PET INFO MODAL --}}
    <div class="modal fade" id="adoptModal" tabindex="-1" aria-labelledby="adoptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title fw-bold text-success" id="adoptModalLabel">🐾Adopt a Pet</h5>
                    <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(29%) sepia(88%) saturate(1782%) hue-rotate(345deg) brightness(90%) contrast(97%);"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 text-center">
                            <img id="modal-image" src="" class="img-fluid rounded shadow" alt="Pet Image">
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>Pet #:</strong> <span id="modal-id"></span></p>
                                    <p><strong>Type:</strong> <span id="modal-type"></span></p>
                                    <p><strong>Breed:</strong> <span id="modal-breed"></span></p>
                                    <p><strong>Gender:</strong> <span id="modal-gender"></span></p>
                                    <p><strong>Color:</strong> <span id="modal-color"></span></p>
                                    <p><strong>Size:</strong> <span id="modal-size"></span></p>
                                    <p><strong>Age:</strong> <span id="modal-age"></span> years</p>
                                    <p><strong>Weight:</strong> <span id="modal-weight"></span> kg</p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Temperament:</strong> <span id="modal-temperament"></span></p>
                                    <p><strong>Health Status:</strong> <span id="modal-health_status"></span></p>
                                    <p><strong>Spayed/Neutered:</strong> <span id="modal-spayed_neutered"></span></p>
                                    <p><strong>Vaccination Status:</strong> <span id="modal-vaccination_status"></span></p>
                                    <p><strong>Good With:</strong> <span id="modal-good_with"></span></p>
                                    <p><strong>Adoption Status:</strong> <span id="modal-adoption_status"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success">Proceed with Adoption</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ADOPT FORM MODAL --}}
    <div class="modal fade" id="adoptionFormModal" tabindex="-1" aria-labelledby="adoptionFormModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title text-success fw-bold" id="adoptionFormModalLabel">🐾 Adoption Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: invert(29%) sepia(88%) saturate(1782%) hue-rotate(345deg) brightness(90%) contrast(97%);"></button>
                </div>
                <div class="modal-body">
                    <form id="adoptionForm" action="{{ route('adoption.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="pet-id" name="pet_id" value="{{ $pet->id }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="adopter-name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="adopter-name" required pattern="^[A-Za-z\s]+$"
                                    oninvalid="this.setCustomValidity('Please enter a valid name')"
                                    oninput="this.setCustomValidity('')">
                            </div>
                            <div class="col-md-6">
                                <label for="adopter-email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="adopter-email" required
                                    oninvalid="this.setCustomValidity('Please enter a valid email address')"
                                    oninput="this.setCustomValidity('')">
                            </div>
                            <div class="col-md-6">
                                <label for="adopter-contact" class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="adopter-contact" required pattern="^\d{10,15}$"
                                    oninvalid="this.setCustomValidity('Please enter a valid contact number (10-15 digits)')"
                                    oninput="this.setCustomValidity('')">
                            </div>
                            <div class="col-md-6">
                                <label for="adopter-address" class="form-label fw-semibold">Home Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="adopter-address" required
                                    oninvalid="this.setCustomValidity('Please enter your home address')"
                                    oninput="this.setCustomValidity('')">
                            </div>
                            <div class="col-12">
                                <label for="adopter-reason" class="form-label fw-semibold">Reason for Adoption <span class="text-danger">*</span></label>
                                <textarea class="form-control w-100" id="adopter-reason" rows="3" required
                                    oninvalid="this.setCustomValidity('Please provide a reason for adoption')"
                                    oninput="this.setCustomValidity('')"></textarea>
                            </div>
                            <div class="col-12">
                                <label for="adopter-experience" class="form-label fw-semibold">Previous Pet Ownership Experience <span class="text-danger">*</span></label>
                                <textarea class="form-control w-100" id="adopter-experience" rows="3" required
                                    oninvalid="this.setCustomValidity('Please describe your previous pet ownership experience (if none, enter N/A)')"
                                    oninput="this.setCustomValidity('')"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="adopter-agreement" required>
                                    <label class="form-check-label fw-semibold text-danger" for="adopter-agreement">
                                        <i class="bi bi-exclamation-circle-fill text-danger"></i> I agree to take full responsibility for the pet's well-being.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="submitAdoption">Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://fastly.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ADOPT PET
    document.addEventListener("DOMContentLoaded", function () {
    const adoptButtons = document.querySelectorAll(".adopt-btn");

    adoptButtons.forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("modal-id").textContent = this.getAttribute("data-id");
            document.getElementById("modal-image").src = this.getAttribute("data-image");
            document.getElementById("modal-type").textContent = this.getAttribute("data-type");
            document.getElementById("modal-breed").textContent = this.getAttribute("data-breed");
            document.getElementById("modal-gender").textContent = this.getAttribute("data-gender");
            document.getElementById("modal-color").textContent = this.getAttribute("data-color");
            document.getElementById("modal-size").textContent = this.getAttribute("data-size");
            document.getElementById("modal-age").textContent = this.getAttribute("data-age");
            document.getElementById("modal-weight").textContent = this.getAttribute("data-weight");
            document.getElementById("modal-temperament").textContent = this.getAttribute("data-temperament");
            document.getElementById("modal-health_status").textContent = this.getAttribute("data-health_status");
            document.getElementById("modal-spayed_neutered").textContent = this.getAttribute("data-spayed_neutered");
            document.getElementById("modal-vaccination_status").textContent = this.getAttribute("data-vaccination_status");
            document.getElementById("modal-good_with").textContent = this.getAttribute("data-good_with");
            document.getElementById("modal-adoption_status").textContent = this.getAttribute("data-adoption_status");

            let petIdInput = document.getElementById("pet-id");
            if (petIdInput) {
                petIdInput.value = this.getAttribute("data-id");
            } else {
                console.error("Hidden input #pet-id not found.");
            }
        });
    });
});
    // FILTER
    document.addEventListener("DOMContentLoaded", function () {
        const filterButtons = document.querySelectorAll(".filter-btn");
        const petCards = document.querySelectorAll(".pet-card");

        filterButtons.forEach(button => {
            button.addEventListener("click", function () {
                let filter = this.getAttribute("data-filter");

                filterButtons.forEach(btn => btn.classList.remove("btn-primary", "active"));
                this.classList.add("btn-primary", "active");

                // Show/hide cards
                petCards.forEach(card => {
                    if (filter === "all" || card.getAttribute("data-category") === filter) {
                        card.style.display = "block";
                    } else {
                        card.style.display = "none";
                    }
                });
            });
        });
    });

    // ADOPT FORM MODAL
    document.addEventListener("DOMContentLoaded", function () {
        const proceedAdoptionBtn = document.querySelector("#adoptModal .btn-success");
        proceedAdoptionBtn.addEventListener("click", function () {
            let adoptModalEl = document.getElementById('adoptModal');
            let adoptModal = bootstrap.Modal.getInstance(adoptModalEl);

            adoptModal.hide();

            adoptModalEl.addEventListener('hidden.bs.modal', function () {
                let adoptionFormModal = new bootstrap.Modal(document.getElementById('adoptionFormModal'));
                adoptionFormModal.show();
            }, { once: true });
        });
    });

    // AJAX INPUT DATA
    $(document).ready(function () {
    $("#adoptionForm").submit(function (event) {
        event.preventDefault();

        let formData = {
            pet_id: $("#pet-id").val(),
            name: $("#adopter-name").val(),
            email: $("#adopter-email").val(),
            contact: $("#adopter-contact").val(),
            address: $("#adopter-address").val(),
            reason: $("#adopter-reason").val(),
            experience: $("#adopter-experience").val(),
            _token: "{{ csrf_token() }}"
        };

        $.ajax({
            url: "{{ route('adoption.store') }}",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                Swal.fire({
                    title: "Success!",
                    text: response.message,
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(() => {
                    $("#adoptionForm")[0].reset();
                    $("#adoptionFormModal").modal("hide");
                });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = "<ul>";
                    $.each(errors, function (key, value) {
                        errorMsg += "<li>" + value[0] + "</li>";
                    });
                    errorMsg += "</ul>";

                    Swal.fire({
                        title: "Validation Error!",
                        html: errorMsg,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: "Something went wrong. Please try again.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            }
        });
    });
});
</script>
</body>
</html>




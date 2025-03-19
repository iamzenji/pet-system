@extends('layouts.public')

@section('content')
    @include('layouts.header-public')

    <!-- Hero Section -->
    <section class="hero-section text-center py-5 bg-light">
        <div class="container">
            <h1 class="fw-bold">Support Furry Friends</h1>
            <p class="text-muted">Your kindness helps provide shelter, food, and medical care to animals in need.</p>
            <button type="button" class="btn btn-success btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#donationModal">
                Donate Now
            </button>
        </div>
    </section>

    <!-- Ways to Support -->
    <section class="container py-5">
        <div class="row text-center">
            <div class="col-md-4">
                <i class="fas fa-home fa-3x text-success"></i>
                <h4 class="mt-3">Adopt a Pet</h4>
                <p>Give a rescued pet a loving home.</p>
                <a href="{{ route('adoption.pets') }}" class="btn btn-outline-success">View Pets</a>
            </div>
            <div class="col-md-4">
                <i class="fas fa-hands-helping fa-3x text-success"></i>
                <h4 class="mt-3">Volunteer</h4>
                <p>Help at shelters, events, or transport animals.</p>
                <a href="#volunteer" class="btn btn-outline-success">Sign Up</a>
            </div>
            <div class="col-md-4">
                <i class="fas fa-donate fa-3x text-success"></i>
                <h4 class="mt-3">Donate</h4>
                <p>Support us with a one-time or monthly donation.</p>
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#donationModal">
                    Donate Now
                </button>
            </div>
        </div>
    </section>

    <!-- Impact Stories -->
    <section class="bg-light py-5 text-center">
        <div class="container">
            <h2>How Your Support Makes a Difference</h2>
            <div class="row mt-4">
                <div class="col-md-6">
                    <img src="{{ asset('storage/images/rescue.jpg') }}" class="img-fluid rounded" alt="Before and After Pet Rescue">
                </div>
                <div class="col-md-6 text-start">
    <p>Meet Fier, a brave 3-year-old dog who overcame a difficult past to find his forever home. Rescued from tough conditions, he was given the care and love he needed to heal and thrive.</p>
    <p>Thanks to generous supporters like you, Fier received medical treatment, proper nutrition, and a warm shelter before being adopted by a loving family.</p>
    <p>Your support helps transform the lives of countless animals like Fier, giving them a second chance at happiness.</p>
</div>

            </div>
        </div>
    </section>

    <!-- Donation Modal -->
    <div class="modal fade" id="donationModal" tabindex="-1" aria-labelledby="donationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="donationModalLabel">Make a Donation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Your generosity helps us rescue, care for, and rehome animals in need.</p>
                    <form>
                        <div class="mb-3">
                            <label for="donationAmount" class="form-label">Choose Donation Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="donationAmount" placeholder="Enter amount" min="1">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="donorName" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="donorName" placeholder="Name">
                        </div>
                        <div class="mb-3">
                            <label for="donorEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="donorEmail" placeholder="Email">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Donate Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

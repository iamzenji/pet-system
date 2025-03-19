@extends('layouts.public')

@section('content')
    @include('layouts.header-public')

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <!-- Image Section -->
                <div class="col-md-6 text-center hero-image">
                    <img src="{{ asset('storage/images/pet.jpg') }}" alt="Furry Friends Adoption Center">
                </div>
                <!-- Content Section -->
                <div class="col-md-6 hero-content">
                    <h1>Welcome to Furry Friends</h1>
                    <p class="text-success fw-bold">Finding Loving Homes for Every Animal</p>
                    <p>
                        At Furry Friends, we believe every pet deserves a second chance. Our mission is to rescue, rehabilitate, and rehome abandoned and stray animals, giving them the love and care they need to thrive.
                    </p>
                    <p>
                        With the help of our dedicated team and compassionate community, we've successfully placed countless animals into caring homes. Whether you're looking to adopt, foster, or support our cause, there are many ways to get involved.
                    </p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-paw list-icon"></i> Rescuing and caring for homeless animals</li>
                        <li><i class="fas fa-paw list-icon"></i> Providing medical care and rehabilitation</li>
                        <li><i class="fas fa-paw list-icon"></i> Connecting pets with loving families</li>
                    </ul>
                    <a href="{{ route('adoption.pets') }}" class="read-more">Meet Our Pets →</a>
                    <a href="{{ route('support') }}" class="read-more ms-3">Support Our Mission →</a>
                </div>
            </div>
        </div>
    </section>
@endsection

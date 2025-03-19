@extends('layouts.public')

@section('content')
    @include('layouts.header-public')

    <!-- Hero Section -->
    <section class="hero-section text-center py-5 bg-light">
        <div class="container">
            <h1 class="fw-bold">Contact Us</h1>
            <p class="text-muted">We’d love to hear from you! Reach out for inquiries, adoptions, or volunteering.</p>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="container py-5">
        <div class="row">
            <div class="col-md-6">
                <h4>Get in Touch</h4>
                <p>Have questions about adopting, volunteering, or supporting Furry Friends? Send us a message!</p>

                <form action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Send Message</button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="col-md-6">
                <h4>Our Contact Details</h4>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt text-success"></i> 123 Paw Haven Street, San Fernando, Pampanga, Philippines</li>
                    <li><i class="fas fa-envelope text-success"></i> support@furryfriends.com</li>
                    <li><i class="fas fa-phone text-success"></i> +63 900 123 4567</li>
                </ul>

                <h4>Follow Us</h4>
                <p>Stay updated on our latest rescues and adoption events.</p>
                <a href="#" class="btn btn-outline-success me-2"><i class="fab fa-facebook-f"></i> Facebook</a>
                <a href="#" class="btn btn-outline-success"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
        </div>

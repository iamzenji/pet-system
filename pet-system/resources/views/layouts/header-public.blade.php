<div class="top-bar">
    <i class="fas fa-phone-alt"></i> +63 900 123 4567 |
    <i class="fas fa-envelope"></i> support@furryfriends.com
</div>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold text-success" href="{{ url('/') }}">
            <i class="fas fa-paw text-success"></i> FURRY FRIENDS
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('about') }}">About Us</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('adoption.pets') }}">Adopt a Pet</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('support') }}">Donate</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('contact') }}">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('login') }}">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

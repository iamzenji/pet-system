<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">  <i class="fas fa-paw text-white"></i>
            FURRY FRIENDS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('list') ? 'active' : '' }}" href="{{ route('adoption.pets') }}">Adopt Pet</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pets.index') ? 'active' : '' }}" href="{{ route('pets.index') }}">Pets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pets.analytics') ? 'active' : '' }}" href="{{ route('pets.analytics') }}">
                        Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('adoptions.index') ? 'active' : '' }}" href="{{ route('adoptions.index') }}">
                        Adoption Requests
                    </a>
                </li>
                @php
                    $userRoleId = Auth::check() ? Auth::user()->roles->first()->id : null;
                @endphp

                <li class="nav-item" @if($userRoleId == 3 || $userRoleId == 4) style="display: none;" @endif>
                    <a class="nav-link {{ request()->routeIs('account') ? 'active' : '' }}" href="{{ route('account') }}">Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pets.manage') ? 'active' : '' }}" href="{{ route('pets.manage') }}">
                        Manage Breeds
                    </a>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }} <!-- Display user name -->
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                    <a class="dropdown-item" href="{{ route('filter.display') }}">
                        <i class="bi bi-funnel"></i> Add Filter
                    </a>
                </li>
            <a class="dropdown-item" href="{{ route('roles.display') }}">
                <i class="bi bi-person-plus"></i> Add Role
            </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
        @endauth
            </ul>
        </div>
    </div>
</nav>

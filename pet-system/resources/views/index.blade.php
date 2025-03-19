@extends('layouts.public')

@section('content')
    @include('layouts.header-public')

    <div class="hero-section">
        <h1>Making Your Pet Happier</h1>
        <p>People love pets as their child. Get better products to keep them happy and healthy.</p>
        <a href="{{ route('adoption.pets') }}" class="btn btn-custom">ADOPT NOW</a>
    </div>
@endsection

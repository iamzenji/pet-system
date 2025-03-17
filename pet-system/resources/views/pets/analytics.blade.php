@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Facades\DB;

    $totalPets = DB::table('pets')->count();
    $totalUsers = DB::table('users')->count();
@endphp

<div class="container mt-5">
    {{-- Breadcrumb Navigation --}}
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h2 class="fw-bold text-success">Analytics Dashboard</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-success">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Analytics Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <h3 id="totalPets">{{ $totalPets }}</h3>
                    <p class="mb-0">Total Pets</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-secondary shadow-sm">
                <div class="card-body text-center">
                    <h3 id="totalUsers">{{ $totalUsers }}</h3>
                    <p class="mb-0">Total Users</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div id="petChart" style="height: 400px; box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.1); border: 1px solid #ddd; border-radius: 10px; padding: 10px;"></div>
            <div id="backButton" class="text-center mt-3" style="display: none;">
                <button class="btn btn-primary">Back to Pet Types</button>
            </div>
        </div>
        <div class="col-md-6">
            <div id="userChart" style="height: 400px; box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.1); border: 1px solid #ddd; border-radius: 10px; padding: 10px;"></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="module">
    document.addEventListener("DOMContentLoaded", function () {
        let petChartDom = document.getElementById('petChart');
        let petChart = echarts.init(petChartDom);
        let userChartDom = document.getElementById('userChart');
        let userChart = echarts.init(userChartDom);
        let backButton = document.getElementById('backButton');
        let currentChartType = 'pets';

        function loadPetTypeChart() {
            fetch("{{ route('pets.analytics.data') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.totalPets !== undefined) {
                        document.getElementById('totalPets').innerText = data.totalPets;
                    }

                    let options = {
                        title: { text: 'Number of Pets by Type', left: 'center' },
                        tooltip: { trigger: 'item' },
                        legend: { orient: 'vertical', left: 'left' },
                        series: [{
                            name: 'Pets',
                            type: 'pie',
                            radius: '50%',
                            data: data.labels.map((label, index) => ({
                                value: data.data[index],
                                name: label
                            })),
                            label: {
                                show: true,
                                position: 'outside',
                                formatter: '{b}: {c} ({d}%)',
                                fontSize: 12
                            },
                            labelLine: { show: true }
                        }]
                    };
                    petChart.setOption(options);
                    currentChartType = 'pets';
                    backButton.style.display = 'none';

                    petChart.off('click');
                    petChart.on('click', function (params) {
                        loadBreedChart(params.name);
                    });
                })
                .catch(error => console.error('Error loading pet type data:', error));
        }

        // Function to load breed-specific chart
        function loadBreedChart(petType) {
            fetch(`{{ url('/pets/analytics/breeds') }}/${petType}`)
                .then(response => response.json())
                .then(data => {
                    let options = {
                        title: { text: `Breeds of ${data.type}`, left: 'center' },
                        tooltip: { trigger: 'item' },
                        legend: { orient: 'vertical', left: 'left' },
                        series: [{
                            name: 'Breeds',
                            type: 'pie',
                            radius: '50%',
                            data: data.labels.map((label, index) => ({
                                value: data.data[index],
                                name: label
                            })),
                            label: {
                                show: true,
                                position: 'outside',
                                formatter: '{b}: {c} ({d}%)',
                                fontSize: 12
                            },
                            labelLine: { show: true }
                        }]
                    };

                    petChart.setOption(options);
                    currentChartType = 'breeds';
                    backButton.style.display = 'block';

                    petChart.off('click');
                })
                .catch(error => console.error('Error loading breed data:', error));
        }

        // Back button event
        backButton.addEventListener('click', function () {
            if (currentChartType === 'breeds') {
                loadPetTypeChart();
            }
        });

        // Function to update user analytics
        function loadUserAnalytics() {
            fetch("{{ route('users.analytics.data') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.totalUsers !== undefined) {
                        document.getElementById('totalUsers').innerText = data.totalUsers;
                    }

                    let options = {
                        title: { text: 'Number of Users by Role', left: 'center' },
                        tooltip: { trigger: 'item' },
                        legend: { orient: 'vertical', left: 'left' },
                        series: [{
                            name: 'Users',
                            type: 'pie',
                            radius: '50%',
                            data: data.labels.map((label, index) => ({
                                value: data.data[index],
                                name: label
                            })),
                            label: {
                                show: true,
                                position: 'outside',
                                formatter: '{b}: {c} ({d}%)',
                                fontSize: 12
                            },
                            labelLine: { show: true }
                        }]
                    };

                    userChart.setOption(options);
                })
                .catch(error => console.error('Error loading user analytics data:', error));
        }

        document.getElementById('totalPets').innerText = "{{ $totalPets }}" || 'Loading...';
        document.getElementById('totalUsers').innerText = "{{ $totalUsers }}" || 'Loading...';

        loadPetTypeChart();
        loadUserAnalytics();
    });
</script>
@endpush

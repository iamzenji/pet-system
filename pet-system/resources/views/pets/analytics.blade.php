@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-success">Analytics</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-success">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div id="petChart" class="chart-container"></div>
            <div id="backButton" class="text-center mt-3" style="display: none;">
                <button class="btn btn-success">Back to Pet Types</button>
            </div>
        </div>
        <div class="col-md-6">
            <div id="userChart" class="chart-container"></div>
        </div>
    </div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-start mb-2">
            <select id="yearSelect" class="form-select w-auto">
                @php $currentYear = date('Y'); @endphp
                @for ($year = $currentYear - 5; $year <= $currentYear; $year++)
                    <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>

        <!-- Chart Container -->
        <div id="adoptionBarChart" class="chart-container"></div>
    </div>
</div>



</div>


<style>
    .clickable-card {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    background: #ffffff;
    border-radius: 5px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    text-align: center;
    min-height: 200px;
    height: 250px;
    width: 100%;
}

.clickable-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.15);
    background-color: #f8f9fa;
}

.icon-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 60px;
    height: 60px;
    background: rgba(15, 111, 213, 0.1);
    border-radius: 5%;
}

.chart-container {
    height: 400px;
    box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    width: 100%;
}

</style>
@endsection

<script type="module">
    document.addEventListener("DOMContentLoaded", function () {
    let petChart = echarts.init(document.getElementById('petChart'));
    let userChart = echarts.init(document.getElementById('userChart'));
    let backButton = document.getElementById('backButton');
    let currentChartType = 'pets';
    let adoptionBarChart = echarts.init(document.getElementById('adoptionBarChart'));

    let currentYear = new Date().getFullYear();

    function loadPetTypeChart() {
        fetch("{{ route('pets.analytics.data') }}")
            .then(response => response.json())
            .then(data => {
                petChart.setOption({
                    title: { text: 'Number of Pets by Type', left: 'center' },
                    tooltip: { trigger: 'item' },
                    // legend: { orient: 'vertical', left: 'left' },
                    series: [{
                        name: 'Pets',
                        type: 'pie',
                        radius: '50%',
                        data: data.labels.map((label, index) => ({
                            value: data.data[index], name: label
                        })),
                        label: { show: true, formatter: '{b}: {c} ({d}%)' }
                    }]
                });
                currentChartType = 'pets';
                backButton.style.display = 'none';

                petChart.off('click');
                petChart.on('click', function (params) {
                    loadBreedChart(params.name);
                });
            })
            .catch(error => console.error('Error loading pet type data:', error));
    }

    function loadBreedChart(petType) {
        fetch(`{{ url('/pets/analytics/breeds') }}/${petType}`)
            .then(response => response.json())
            .then(data => {
                petChart.setOption({
                    title: { text: `Breeds of ${data.type}`, left: 'center' },
                    tooltip: { trigger: 'item' },
                    // legend: { orient: 'vertical', left: 'left' },
                    series: [{
                        name: 'Breeds',
                        type: 'pie',
                        radius: '50%',
                        data: data.labels.map((label, index) => ({
                            value: data.data[index], name: label
                        })),
                        label: { show: true, formatter: '{b}: {c} ({d}%)' }
                    }]
                });
                currentChartType = 'breeds';
                backButton.style.display = 'block';

                petChart.off('click');
            })
            .catch(error => console.error('Error loading breed data:', error));
    }

    backButton.addEventListener('click', function () {
        if (currentChartType === 'breeds') {
            loadPetTypeChart();
        }
    });

    function loadUserAnalytics() {
        fetch("{{ route('users.analytics.data') }}")
            .then(response => response.json())
            .then(data => {
                userChart.setOption({
                    title: { text: 'Number of Users by Role', left: 'center' },
                    tooltip: { trigger: 'item' },
                    // legend: { orient: 'vertical', left: 'left' },
                    series: [{
                        name: 'Users',
                        type: 'pie',
                        radius: '50%',
                        data: data.labels.map((label, index) => ({
                            value: data.data[index], name: label
                        })),
                        label: { show: true, formatter: '{b}: {c} ({d}%)' }
                    }]
                });
            })
            .catch(error => console.error('Error loading user analytics data:', error));
    }

    function loadAdoptionBarChart(year) {
        fetch(`{{ url('/adoptions/chart-data') }}/${year}`)
            .then(response => response.json())
            .then(data => {
                adoptionBarChart.setOption({
                    title: { text: `Pet Adoptions by Month (${year})`, left: 'center' },
                    tooltip: { trigger: 'axis' },
                    xAxis: { type: 'category', data: data.months },
                    yAxis: { type: 'value' },
                    series: [{ name: 'Adoptions', type: 'bar', data: data.adoptions }]
                });
            })
            .catch(error => console.error('Error loading adoption data:', error));
    }

    document.getElementById('yearSelect').addEventListener('change', function () {
        let selectedYear = this.value || currentYear;
        loadAdoptionBarChart(selectedYear);
    });

    document.getElementById('yearSelect').value = currentYear;

    loadAdoptionBarChart(currentYear);

    loadPetTypeChart();
    loadUserAnalytics();
});

</script>
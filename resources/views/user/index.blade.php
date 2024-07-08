@extends('layout.master')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <!-- Total Warga Card -->
                    <div class="col-md-4">
                        <div class="card info-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon bg-primary text-white rounded-circle p-4 mt-4">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h5 class="card-title">Total Warga</h5>
                                        <h6>{{ $totalWarga }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Total Warga Card -->

                    <!-- Total RT Card -->
                    <div class="col-md-4">
                        <div class="card info-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon bg-info text-white rounded-circle p-4 mt-4">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h5 class="card-title">Total RT</h5>
                                        <h6>{{ $totalRT }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Total RT Card -->

                    <!-- Total RW Card -->
                    <div class="col-md-4">
                        <div class="card info-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon bg-info text-white rounded-circle p-4 mt-4">
                                        <i class="bi bi-house"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h5 class="card-title">Total RW</h5>
                                        <h6>{{ $totalRW }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Total RW Card -->
                </div>

                <!-- Filter and Chart Card -->
                <div class="card mt-5">
                    <div class="card-body">
                        <!-- Year Filter -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <form action="{{ route('user.index') }}" method="GET">
                                    <div class="form-group">
                                        <label for="year" style="margin-top: 30px;">Pilih Tahun:</label>
                                        <select name="year" id="year" class="form-control">
                                            @php
                                            $currentYear = date('Y');
                                            $endYear = $currentYear + 5;
                                            @endphp
                                            @for ($y = $currentYear; $y <= $endYear; $y++)
                                                <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </form>
                            </div>
                        </div><!-- End Year Filter -->

                        <!-- Combined Positive and Negative Houses Chart -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="card-title text-center">Jumlah Rumah Positif dan Negatif Berdasarkan RW</h5>
                                <canvas id="combinedHousesChart" width="400" height="200"></canvas>
                            </div>
                        </div><!-- End Combined Positive and Negative Houses Chart -->
                    </div>
                </div><!-- End Filter and Chart Card -->

            </div><!-- End Left side columns -->
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('combinedHousesChart').getContext('2d');
        var combinedChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($positiveHousesByRW->pluck('RW')),
                datasets: [{
                        label: 'Jumlah Positif',
                        data: @json($positiveHousesByRW->pluck('positive_count')),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Jumlah Negatif',
                        data: @json($negativeHousesByRW->pluck('negative_count')),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'RW'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

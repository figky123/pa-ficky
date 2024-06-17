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
                    <!-- Jumlah Warga Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card info-card inspection-card animate__animated animate__fadeInUp" style="background-color: #f8f9fa; border-color: #e9ecef;">
                            <div class="card-body">
                                <h5 class="card-title">Total Warga</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #ffc107;">
                                        <i class="bi bi-person-fill" style="color: white; font-size: 24px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 style="color: #343a40;">{{ $totalWarga }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Jumlah Warga Card -->
                    <!-- Jumlah Laporan Warga Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card info-card inspection-card animate__animated animate__fadeInUp" style="background-color: #f8f9fa; border-color: #e9ecef;">
                            <div class="card-body">
                                <h5 class="card-title">Total Laporan Warga</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #ffc107;">
                                        <i class="bi bi-file-earmark-text" style="color: white; font-size: 24px;"></i> <!-- Ikon Laporan -->
                                    </div>
                                    <div class="ps-3">
                                        <h6 style="color: #343a40;">{{ $jumlahlaporan }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Jumlah Laporan Warga Card -->
                    <!-- Inspection Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card info-card inspection-card animate__animated animate__fadeInUp">
                            <div class="card-body">
                                <h5 class="card-title">Total Laporan Warga yang Sudah Ditindak Jumantik</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-check-circle"></i> <!-- Ikon Rumah -->
                                    </div>
                                    <div class="ps-3">
                                        <h6 class="total-value">{{ $total }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Total Rumah Yang Sudah Diperiksa Card -->

                    <!-- Total Rumah yang Belum Diperiksa Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card info-card inspection-card animate__animated animate__fadeInUp" style="background-color: #f8f9fa; border-color: #e9ecef;">
                            <div class="card-body">
                                <h5 class="card-title">Total Laporan Warga yang Belum Ditindak Jumantik</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #dc3545;">
                                        <i class="bi bi-hourglass-bottom" style="color: white; font-size: 24px;"></i> <!-- Ganti dengan ikon lain -->
                                    </div>
                                    <div class="ps-3">
                                        <h6 style="color: #343a40;">{{ $totalRumahBelumDiperiksa }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Total Rumah yang Belum Diperiksa Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card info-card inspection-card animate__animated animate__fadeInUp" style="background-color: #f8d7da; border-color: #f5c6cb;">
                            <div class="card-body">
                                <h5 class="card-title">Total Rumah Positif Jentik</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #dc3545;">
                                        <i class="bi bi-plus-circle" style="color: white; font-size: 24px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 style="color: #dc3545;">{{ $jumlahStatusJentikPositif }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card info-card inspection-card animate__animated animate__fadeInUp" style="background-color: #d4edda; border-color: #c3e6cb;">
                            <div class="card-body">
                                <h5 class="card-title">Total Rumah Negatif Jentik</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #28a745;">
                                        <i class="bi bi-dash-circle" style="color: white; font-size: 24px;"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 style="color: #28a745;">{{ $jumlahStatusJentikNegatif }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total Laporan Jumantik yang Sudah Ditindak Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card info-card inspection-card animate__animated animate__fadeInUp">
                            <div class="card-body">
                                <h5 class="card-title">Total Laporan Jumantik yang Sudah Ditindak Puskesmas</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-check-circle"></i> <!-- Ikon Tindakan -->
                                    </div>
                                    <div class="ps-3">
                                        <h6 class="total-value">{{ $totalSudahDitindak }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Total Laporan Jumantik yang Sudah Ditindak Card -->

                    <!-- Total Laporan yang Belum Ditindak Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card info-card inspection-card animate__animated animate__fadeInUp" style="background-color: #f8f9fa; border-color: #e9ecef;">
                            <div class="card-body">
                                <h5 class="card-title">Total Laporan Jumantik Yang Belum Ditindak Puskesmas</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center animate__animated animate__rotate" style="background-color: #dc3545;">
                                        <i class="bi bi-hourglass-bottom" style="color: white; font-size: 24px;"></i> <!-- Ikon Hourglass -->
                                    </div>
                                    <div class="ps-3">
                                        <h6 style="color: #343a40;">{{ $totalBelumDitindak }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Total Laporan yang Belum Ditindak Card -->
                </div>
                <!-- Filter Tahun -->
                <div class="col-12 mb-4">
                    <label for="yearFilter">Pilih Tahun:</label>
                    <select id="yearFilter" class="form-control">
                        @for ($i = date('Y'); $i <= date('Y') + 5; $i++) <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                    </select>
                </div>
                <!-- Chart Section -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Laporan Perkembangan Kasus Jentik Nyamuk</h5>
                            <canvas id="monthlyReportsChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <!-- End Chart Section -->
            </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Array of month names in Indonesian
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Function to update chart
        function updateChart(year) {
            $.ajax({
                url: "{{ route('user.index') }}", // Update this URL to your route
                method: 'GET',
                data: {
                    year: year
                },
                success: function(response) {
                    const chartData = response.chartData;
                    const labels = monthNames;
                    const counts = labels.map((month, index) => {
                        const dataPoint = chartData.find(item => item.month === (index + 1));
                        return dataPoint ? dataPoint.count : 0; // Use count if data is available, otherwise 0
                    });

                    // Array of predefined colors for bars
                    const barColors = [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ];

                    // Create chart
                    const ctx = document.getElementById('monthlyReportsChart').getContext('2d');
                    if (window.myChart) {
                        window.myChart.destroy();
                    }
                    window.myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Kasus',
                                data: counts,
                                backgroundColor: barColors,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: true,
                                        color: 'rgba(0, 0, 0, 0.1)'
                                    },
                                    ticks: {
                                        stepSize: 1 // Use whole number steps for y-axis ticks
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Grafik Jumlah Laporan Warga',
                                    font: {
                                        size: 16
                                    }
                                },
                                legend: {
                                    display: false
                                },
                                animation: {
                                    duration: 1500,
                                    easing: 'easeInOutQuart'
                                }
                            }
                        }
                    });
                }
            });
        }

        // Initial load
        const initialYear = new Date().getFullYear();
        updateChart(initialYear);

        // Update chart when year is changed
        $('#yearFilter').on('change', function() {
            const selectedYear = $(this).val();
            updateChart(selectedYear);
        });
    });
</script>
@endsection
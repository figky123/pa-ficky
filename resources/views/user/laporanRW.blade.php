@extends('layout.master')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Include SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Laporan Jumantik II</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                 <!-- Information Alert -->
                 <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Informasi Laporan:</strong> Laporan ini berisikan hasil perhitungan pemeriksaan jentik yang dilakukan oleh warga pada siklus ke-4 berdasarkan RT.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"></h5>

                        <!-- Month and Year Filter Form -->
                        <form method="GET" action="{{ route('laporan.rw') }}">
                            <div class="form-group">
                                <label for="month">Month:</label>
                                <select name="month" id="month" class="form-control">
                                    @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year">Year:</label>
                                <select name="year" id="year" class="form-control">
                                    @foreach(range(Carbon\Carbon::now()->year, Carbon\Carbon::now()->year + 10) as $year)
                                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mb-3 mt-3">Filter</button>
                        </form>

                        <!-- Table with stripped rows -->
                        <div class="table-responsive mt-4">
                            <table class="table datatable" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>RT</th>
                                        <th>Jumlah Rumah Diperiksa</th>
                                        <th>Jumlah Rumah Positif Jentik</th>
                                        <th>ABJ</th>
                                        <th>Kategori</th>
                                    </tr>
                                </thead>
                                <tbody id="table-wargas">
                                    @foreach ($summaryData as $item)
                                    <tr>
                                        <td>{{ $item->RT }}</td>
                                        <td>{{ $item->jumlahrumahdiperiksa }}</td>
                                        <td>{{ $item->jumlahrumahpositif }}</td>
                                        <td>{{ $item->ABJ }}%</td>
                                        <td class="kategori">{{ $item->Kategori }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
@endsection
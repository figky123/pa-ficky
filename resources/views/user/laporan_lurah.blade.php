@extends('layout.master')

<!-- jQuery and jQuery UI -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Bootstrap CSS and JS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Simple Datatables CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Laporan Lurah</h1>
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
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Laporan Lurah</h5>

                        <!-- Month and Year Filter Form -->
                        <form method="GET" action="{{ route('laporan.lurah') }}">
                            <div class="form-group">
                                <label for="month">Bulan:</label>
                                <select name="month" id="month" class="form-control">
                                    @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year">Tahun:</label>
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
                        <div class="table-responsive">
                            <table class="table datatable" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>RW</th>
                                        <th>Jumlah Rumah Diperiksa</th>
                                        <th>Jumlah Rumah Positif Jentik</th>
                                        <th>ABJ</th>
                                        <th>Kategori</th>
                                    </tr>
                                </thead>
                                <tbody id="table-wargas">
                                    @foreach ($summaryData as $item)
                                    <tr>
                                        <td>{{ $item->RW }}</td>
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

                        <!-- Button to generate PDF -->
                        <form method="GET" action="{{ route('laporan.lurah') }}" class="text-right mt-3">
                            <input type="hidden" name="month" value="{{ $selectedMonth }}">
                            <input type="hidden" name="year" value="{{ $selectedYear }}">
                            <button type="submit" name="pdf" value="1" class="btn btn-danger"><i class="bi bi-file-earmark-pdf"></i> Cetak PDF</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the datatable
        const dataTable = new simpleDatatables.DataTable("#dataTable");

        // Check for "Risiko Tinggi" category and collect the data
        const rows = document.querySelectorAll('#dataTable tbody tr');
        let risikoTinggiData = [];
        rows.forEach(row => {
            const kategori = row.querySelector('.kategori').innerText;
            const rw = row.cells[0].innerText;
            if (kategori === "Risiko Tinggi") {
                risikoTinggiData.push(`RW ${rw}`);
            }
        });

        // If there are any "Risiko Tinggi" entries, show them in a SweetAlert2
        if (risikoTinggiData.length > 0) {
            Swal.fire({
                title: 'Peringatan!',
                html: `RW berikut ini memiliki risiko tinggi:<br><br>${risikoTinggiData.join('<br>')}<br><br>Segera lakukan penanganan!`,
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
    });
</script>
@endsection
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
        <h1>Laporan RT</h1>
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
                        <h5 class="card-title">Laporan RT</h5>
                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table datatable" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Nama Kepala Keluarga</th>
                                        <th>RT</th>
                                        <th>RW</th>
                                        <th>Waktu Pemeriksaan</th>
                                        <th>Alamat</th>
                                        <th>Status Jentik</th>
                                    </tr>
                                </thead>
                                <tbody id="table-wargas">
                                    @foreach($pemeriksaans as $pemeriksaan)
                                    <tr>
                                        <td>{{ $pemeriksaan->user->name }}</td>
                                        <td>{{ $pemeriksaan->user->RT }}</td>
                                        <td>{{ $pemeriksaan->user->RW }}</td>
                                        <td>{{ $pemeriksaan->formatted_tgl_pemeriksaan }}</td>
                                        <td>{{ $pemeriksaan->user->alamat }}</td>
                                        <td>{{ $pemeriksaan->status_jentik }}</td>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the datatable
        const dataTable = new simpleDatatables.DataTable("#dataTable", {
            searchable: true,
            fixedHeight: true,
        });
    });
</script>
@endsection
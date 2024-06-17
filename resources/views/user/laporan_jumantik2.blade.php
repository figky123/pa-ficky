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

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Summary Data Jumantik</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Summary</li>
                <li class="breadcrumb-item active">Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Laporan Jumantik 2</h5>
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Nama Petugas</th>
                                        <th>Siklus</th>
                                        <th>Jumlah Rumah Warga yang Diperiksa</th>
                                        <th>Jumlah Rumah Positif Jentik</th>
                                        <th>ABJ (Angka Bebas Jentik)</th>
                                        <th>Kategori</th>
                                        <th>Detail</th> <!-- New Column for Detail Icon -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($summaryData as $item)
                                    <tr>
                                        <td>{{ $item['nama_petugas'] }}</td>
                                        <td>{{ $item['siklus'] }}</td>
                                        <td>{{ $item['uniqueBuildingsCount'] }}</td>
                                        <td>{{ $item['positiveLarvaeCount'] }}</td>
                                        <td>{{ number_format($item['abj'], 2) }}%</td>
                                        <td>{{ $item['kategori'] }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal{{ $loop->index }}"><i class="fas fa-info-circle"></i> Detail</button>
                                        </td>
                                    </tr>
                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detailModal{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $loop->index }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="detailModalLabel{{ $loop->index }}">Detail Data</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Nama Petugas:</strong> {{ $item['nama_petugas'] }}</p>
                                                    <p><strong>Siklus:</strong> {{ $item['siklus'] }}</p>
                                                    <p><strong>RT yang Diperiksa:</strong> {{ $item['uniqueRTs'] }}</p>
                                                    <p><strong>RW yang Diperiksa:</strong> {{ $item['uniqueRWs'] }}</p>
                                                    <p><strong>Rumah Warga yang Diperiksa:</strong>
                                                        @foreach ($item['laporan'] as $key => $laporan)
                                                        {{ $laporan->user->name }}
                                                        @if ($key < count($item['laporan']) - 1), @endif @endforeach </p>
                                                            <p><strong>Jumlah Rumah Positif Jentik:</strong> {{ $item['positiveLarvaeCount'] }}</p>
                                                            <p><strong>Rumah Warga Positif Jentik:</strong> {{ $item['positiveLarvaeNames'] }}</p>
                                                            <p><strong>ABJ (Angka Bebas Jentik):</strong> {{ number_format($item['abj'], 2) }}%</p>
                                                            <p><strong>Kategori:</strong> {{ $item['kategori'] }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                </tbody>
                            </table>
                            <a href="{{ route('generate-pdf') }}" class="btn btn-primary">Cetak PDF</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
@extends('layout.lurah')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Data Tables</h1>
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
            <h5 class="card-title">Data Warga</h5>
            <!-- Table with stripped rows -->
            <table class="table datatable">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>No KK</th>
                  <th>No Hp</th>
                  <th>Alamat</th>
                  <th>RT</th>
                  <th>RW</th>
                </tr>
              </thead>
              <tbody id="table-wargas">
              @foreach($wargas as $warga)
                <tr>
                  <td>{{ $warga-> nama_warga }}</td>
                  <td>{{ $warga-> email }}</td>
                  <td>{{ $warga-> no_kk }}</td>
                  <td>{{ $warga-> no_hp_warga }}</td>
                  <td>{{ $warga-> alamat }}</td>
                  <td>{{ $warga-> RT }}</td>
                  <td>{{ $warga-> RW }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <!-- End Table with stripped rows -->
          </div>
        </div>
      </div>
    </div>
  </section>

</main><!-- End #main -->
@endsection
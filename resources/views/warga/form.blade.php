@extends('layout.warga')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Form Pengaduan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="warga/login">Home</a></li>
        <li class="breadcrumb-item active">Form Pengaduan</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <section class="section">
    <div class="row">
      <div class="col-lg-10 mx-auto">
         <div class="card">
          <div class="card-body">
            <h5 class="card-title">Form</h5>

            <!-- Horizontal Form -->
            @section('content')
            <form class="user" method="post" action="/laporan/store" enctype="multipart/form-data">
                    @csrf
              <div class="row mb-3">
                <label for="inputDate" class="col-sm-2 col-form-label">Tanggal Laporan</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" id="date">
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputlaporan" class="col-sm-2 col-form-label">Keterangan Laporan</label>
                <div class="col-sm-10">
                  <input type="laporan" class="form-control" id="ket_laporan">
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
              </div>
            </form><!-- End Horizontal Form -->

          </div>
        </div>
        @endsection
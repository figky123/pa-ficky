@extends('layout.master')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Data Warga</h1>
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
            @if(\Session::has('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <script>
              Swal.fire({
                icon: 'success',
                title: 'Data Berhasil Diubah',
                showConfirmButton: false,
                timer: 1500
              });
            </script>
            @endif
            <!-- Table with stripped rows -->
            <div class="table-responsive">
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
                    @if(Auth::check() && Auth::user()->hasRole('Admin'))
                    <th>Aksi</th> 
                    @endif<!-- Kolom untuk tombol edit -->
                  </tr>
                </thead>
                <tbody id="table-wargas">
                  @foreach($users as $user)
                  @if($user->role == 'Warga')
                  <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->no_kk }}</td>
                    <td>{{ $user->no_hp_user }}</td>
                    <td>{{ $user->alamat }}</td>
                    <td>{{ $user->RT }}</td>
                    <td>{{ $user->RW }}</td>
                    <td>
                    @if(Auth::check() && Auth::user()->hasRole('Admin'))
                      <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> <!-- Icon from Bootstrap Icons -->
                      </a>
                      @endif
                    </td>
                  </tr>
                  @endif
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
@extends('layout.master')

<!-- Include CSS and JS -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">

<!-- Include JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>

<!-- SweetAlert2 for displaying alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable" id="dataTable">
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
                    <th>Status</th>
                    <th>Aksi</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $user)
                  <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->no_kk }}</td>
                    <td>{{ $user->no_hp_user }}</td>
                    <td>{{ $user->alamat }}</td>
                    <td>{{ $user->RT }}</td>
                    <td>{{ $user->RW }}</td>
                    <td>
                      @if($user->status_akun == 'not verified')
                      <a href="{{ route('user.verify', $user->id) }}" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Verify
                      </button>
                      @else
                      <span class="badge badge-success">
                        <i class="bi bi-check-circle"></i> Verified
                      </span>
                      @endif
                    </td>
                    @if(Auth::check() && Auth::user()->hasRole('Admin'))
                    <td>
                      <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> <!-- Icon from Bootstrap Icons -->
                      </a>
                    </td>
                    @endif
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

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Select all elements with class 'verify-button'
    const verifyButtons = document.querySelectorAll('.verify-button');

    // Loop through each verify button and attach click event listener
    verifyButtons.forEach(button => {
      button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default button behavior

        const userId = this.getAttribute('data-id'); // Get user ID from data-id attribute

        // Confirm verification action using SweetAlert
        Swal.fire({
          title: 'Verifikasi Akun',
          text: 'Apakah Anda yakin ingin memverifikasi akun ini?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, verifikasi!'
        }).then((result) => {
          if (result.isConfirmed) {
            // Perform AJAX request to verify user account
            $.ajax({
              url: '{{ route("user.verify") }}',
              method: 'POST',
              data: {
                _token: '{{ csrf_token() }}',
                id: userId
              },
              success: function(response) {
                if (response.status) {
                  // Show success message using SweetAlert
                  Swal.fire('Berhasil!', response.message, 'success').then(() => {
                    location.reload(); // Reload page after successful verification
                  });
                } else {
                  // Show error message using SweetAlert
                  Swal.fire('Gagal!', response.message, 'error');
                }
              },
              error: function(xhr, status, error) {
                // Handle AJAX errors if any
                console.error('AJAX Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan saat memverifikasi akun.', 'error');
              }
            });
          }
        });
      });
    });
  });
</script>
@endsection
@endsection
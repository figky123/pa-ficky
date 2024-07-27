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
                    <th>Foto KK</th>
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
                      @if($user->foto_kk)
                      <button type="button" class="btn btn-primary view-image-btn" data-toggle="modal" data-target="#imageModal" data-image="{{ asset('storage/foto_kk/' . $user->foto_kk) }}" data-created-at="{{ $user->created_at->format('Y-m-d H:i:s') }}">
                        View
                      </button>
                      @else
                      Tidak ada Foto
                      @endif
                    </td>
                    @if(Auth::check() && Auth::user()->hasRole('Admin'))
                    <td>
                      @if($user->status_akun == 'not_verified')
                      <a href="{{ route('user.verify', $user->id) }}" data-id="{{$user->id}}" class="btn verify-button btn-danger">
                        <i class="bi bi-check-circle"></i> Verify
                      </a>
                      <a href="{{ route('user.reject', $user->id) }}" data-id="{{$user->id}}" class="btn mt-3 reject-button btn-warning">
                        <i class="bi bi-x-circle"></i> Reject
                      </a>
                      @elseif($user->status_akun == 'verified')
                      <span class="badge badge-success">
                        <i class="bi bi-check-circle"></i> Verified
                      </span>
                      @elseif($user->status_akun == 'rejected')
                      <span class="badge badge-danger">
                        <i class="bi bi-x-circle"></i> Rejected
                      </span>
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> <!-- Icon from Bootstrap Icons -->
                      </a>
                      @if($user->status_akun == 'rejected')
                      <a href="{{ route('user.delete') }}" data-id="{{$user->id}}" class="btn mt-3 delete-button btn-danger">
                        <i class="bi bi-trash"></i>
                      </a>
                      @endif
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
<!-- Modal View Image -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Foto KK</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img id="modalImage" src="" alt="Foto KK" class="img-fluid">
        <p id="createdAtText"></p>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Select all elements with class 'verify-button'
    const verifyButtons = document.querySelectorAll('.verify-button');
    const rejectButtons = document.querySelectorAll('.reject-button');
    const deleteButtons = document.querySelectorAll('.delete-button');

    $('.view-image-btn').on('click', function() {
      var imageUrl = $(this).data('image');
      var createdAt = $(this).data('created-at');
      $('#modalImage').attr('src', imageUrl);
      $('#createdAtText').text('Foto KK: ' + new Date(createdAt).toLocaleString());
    });

    // Loop through each verify button and attach click event listener
    verifyButtons.forEach(button => {
      button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default button behavior
        const userId = this.getAttribute('data-id'); // Get user ID from data-id attribute
        console.log(this.get)
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
          console.log(userId)
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
              }
            });
          }
        });
      });
    });

    rejectButtons.forEach(button => {
      button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default button behavior
        const userId = this.getAttribute('data-id'); // Get user ID from data-id attribute

        // Confirm rejection action using SweetAlert
        Swal.fire({
          title: 'Tolak Akun',
          text: 'Apakah Anda yakin ingin menolak akun ini?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, tolak!'
        }).then((result) => {
          if (result.isConfirmed) {
            // Perform AJAX request to reject user account
            $.ajax({
              url: '{{ route("user.reject") }}',
              method: 'POST',
              data: {
                _token: '{{ csrf_token() }}',
                id: userId
              },
              success: function(response) {
                if (response.status) {
                  // Show success message using SweetAlert
                  Swal.fire('Berhasil!', response.message, 'success').then(() => {
                    location.reload(); // Reload page after successful rejection
                  });
                } else {
                  // Show error message using SweetAlert
                  Swal.fire('Gagal!', response.message, 'error');
                }
              },
              error: function(xhr, status, error) {
                // Handle AJAX errors if any
                console.error('AJAX Error:', error);
              }
            });
          }
        });
      });
    });

    deleteButtons.forEach(button => {
      button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default button behavior
        const userId = this.getAttribute('data-id'); // Get user ID from data-id attribute

        // Confirm delete action using SweetAlert
        Swal.fire({
          title: 'Hapus Akun',
          text: 'Apakah Anda yakin ingin menghapus akun ini?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
          if (result.isConfirmed) {
            // Perform AJAX request to delete user account
            $.ajax({
              url: '{{ route("user.delete") }}',
              method: 'POST',
              data: {
                _token: '{{ csrf_token() }}',
                id: userId
              },
              success: function(response) {
                if (response.status) {
                  // Show success message using SweetAlert
                  Swal.fire('Berhasil!', response.message, 'success').then(() => {
                    location.reload(); // Reload page after successful delete
                  });
                } else {
                  // Show error message using SweetAlert
                  Swal.fire('Gagal!', response.message, 'error');
                }
              },
              error: function(xhr, status, error) {
                // Handle AJAX errors if any
                console.error('AJAX Error:', error);
              }
            });
          }
        });
      });
    });
  });
</script>
@endsection
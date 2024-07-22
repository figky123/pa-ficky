@extends('layout.master')

<!-- Include CSS -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">

<!-- Include JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Data Petugas</h1>
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
            <h5 class="card-title">Data Petugas</h5>

            <!-- Button to Open the Modal -->
            @if(Auth::check() && Auth::user()->hasRole('Admin'))
            <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addUserModal">
              Tambah Data
            </button>
            @endif

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable" id="dataTable">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No Hp</th>
                    <th>Role</th>
                    @if(Auth::check() && Auth::user()->hasRole('Admin'))
                    <th>Status Akun</th>
                    <th>Aksi</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $user)
                  <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->no_hp_user }}</td>
                    <td>{{ $user->role }}</td>
                    @if(Auth::check() && Auth::user()->hasRole('Admin'))
                    <td>
                      @if($user->status_akun == 'not_verified')
                      <a href="{{ route('user.verify', $user->id) }}" data-id="{{$user->id}}" class="btn verify-button btn-danger">
                        <i class="bi bi-x-circle"></i> Verify
                      </a>
                      @else
                      <span class="badge badge-success">
                        <i class="bi bi-check-circle"></i> Verified
                      </span>
                      @endif
                    </td>
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

<!-- Modal for Adding User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Tambah Data Petugas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addUserForm" method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
          </div>
          <div class="form-group">
            <label for="no_kk">No KK</label>
            <input type="text" class="form-control" id="no_kk" name="no_kk" required>
          </div>
          <div class="form-group">
            <label for="no_hp_user">No Hp</label>
            <input type="text" class="form-control" id="no_hp_user" name="no_hp_user" required>
          </div>
          <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" required>
          </div>
          <div class="form-group">
            <label for="RT">RT</label>
            <input type="text" class="form-control" id="RT" name="RT" required>
          </div>
          <div class="form-group">
            <label for="RW">RW</label>
            <input type="text" class="form-control" id="RW" name="RW" required>
          </div>
          <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role" required>
              <option value="">Pilih Role</option>
              <option value="Puskesmas">Puskesmas</option>
              <option value="RT">RT</option>
              <option value="RW">RW</option>
            </select>
          </div>
          <div class="form-group">
            <label for="foto_kk">Foto KK</label>
            <input type="file" class="form-control-file" id="foto_kk" name="foto_kk" accept="image/*" required>
            <small id="fotoKkHelp" class="form-text text-muted">Upload foto KK (format: jpeg, png, jpg, gif, svg | max: 2048 KB).</small>
            <div id="fotoKkWarning" class="text-danger mt-2" style="display: none;">Format file tidak valid atau melebihi ukuran maksimum (2048 KB).</div>
          </div>
          <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Include Script for DataTable and Modal -->

<!-- Include Script for DataTable, Modal, and Form Validation -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    const dataTable = new simpleDatatables.DataTable('#dataTable');

    // Handle verify button click
    const verifyButtons = document.querySelectorAll('.verify-button');
    verifyButtons.forEach(button => {
      button.addEventListener('click', function(event) {
        event.preventDefault();
        const userId = this.getAttribute('data-id');
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
            $.ajax({
              url: '{{ route("user.verify") }}',
              method: 'POST',
              data: {
                _token: '{{ csrf_token() }}',
                id: userId
              },
              success: function(response) {
                if (response.status) {
                  Swal.fire({
                    title: 'Berhasil!',
                    text: response.message,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                  }).then(() => {
                    location.reload();
                  });
                } else {
                  Swal.fire({
                    title: 'Gagal!',
                    text: response.message,
                    icon: 'error',
                    showConfirmButton: true
                  });
                }
              },
              error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                Swal.fire({
                  title: 'Error!',
                  text: 'Terjadi kesalahan saat memverifikasi akun.',
                  icon: 'error',
                  showConfirmButton: true
                });
              }
            });
          }
        });
      });
    });

    // Handle file input change event for foto_kk
    $('#foto_kk').on('change', function(event) {
      const file = event.target.files[0];
      if (file) {
        const fileType = file.type;
        const fileSize = file.size;
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];

        if (!validTypes.includes(fileType) || fileSize > 2048000) {
          $('#fotoKkWarning').show();
          $(this).val(''); // Clear the input
        } else {
          $('#fotoKkWarning').hide();
        }
      }
    });

    // Modal tambah data
    $('#addUserForm').on('submit', function(event) {
      event.preventDefault();
      var formData = new FormData(this);
      var url = $(this).attr('action');

      $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          console.log('Data berhasil ditambahkan');
          $('#addUserModal').modal('hide'); // Menutup modal setelah menambahkan data
          Swal.fire({
            title: 'Data berhasil ditambahkan',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            location.reload(); // Memperbarui tampilan dengan data yang baru ditambahkan
          });
        },
        error: function(response) {
          console.error('Terjadi kesalahan:', response);
          let errorMessage = 'Terjadi kesalahan saat menambahkan data.';
          if (response.responseJSON && response.responseJSON.errors) {
            errorMessage = '<ul>';
            $.each(response.responseJSON.errors, function(key, errors) {
              $.each(errors, function(index, error) {
                errorMessage += '<li>' + error + '</li>';
              });
            });
            errorMessage += '</ul>';
          }
          Swal.fire({
            title: 'Gagal menambahkan data',
            html: errorMessage,
            icon: 'error',
            showConfirmButton: true
          });
        }
      });
    });
  });
</script>
@endsection
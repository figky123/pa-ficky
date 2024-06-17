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
    <h1>Data Laporan Warga</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item">Tables</li>
        <li class="breadcrumb-item active">Data</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <section class="section">
    @if(Auth::check() && Auth::user()->hasRole('Warga'))
    <button id="btnTambahData" class="btn btn-primary" data-toggle="modal" data-target="#tambahDataModal">Tambah Data</button>
    @endif
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Data Laporan Warga</h5>
            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Nama User</th>
                    <th>Alamat</th>
                    <th>RT</th>
                    <th>RW</th>
                    <th>Tanggal Laporan</th>
                    <th>Status Laporan</th>
                    <th>Keterangan Laporan</th>
                    <th>Bukti Laporan</th>
                    
                  </tr>
                </thead>
                <tbody id="table-users">
                  @php
                  $loggedInUser = Auth::user();
                  $userLaporans = App\Models\Laporan::query();

                  if ($loggedInUser->hasRole('Warga')) {
                  $userLaporans->where('id_user', $loggedInUser->id);
                  }

                  $userLaporans = $userLaporans->get();
                  @endphp

                  @foreach($userLaporans as $laporan)
                  <tr>
                    <td>{{ $laporan->user ? ucwords($laporan->user->name) : 'N/A' }}</td>
                    <td>{{ $laporan->user ? $laporan->user->alamat : 'N/A' }}</td>
                    <td>{{ $laporan->user ? $laporan->user->RT : 'N/A' }}</td>
                    <td>{{ $laporan->user ? $laporan->user->RW : 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($laporan->tgl_laporan)->translatedFormat('d F Y') }}</td>
                    <td>
                      @if(in_array(Auth::user()->role, ['Puskesmas', 'Lurah', 'Jumantik','Admin']))
                      <!-- Tampilkan dropdown select untuk kolom tindakan yang dapat diubah oleh puskesmas, lurah, atau jumantik -->
                      <select class="form-control status-dropdown" data-id="{{ $laporan->id }}">
                        <option value="proses" {{ $laporan->status_laporan == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="tindaklanjut jumantik" {{ $laporan->status_laporan == 'tindaklanjut jumantik' ? 'selected' : '' }}>Tindaklanjut Jumantik</option>
                        <option value="tindaklanjut puskesmas" {{ $laporan->status_laporan == 'tindaklanjut puskesmas' ? 'selected' : '' }}>Tindaklanjut Puskesmas</option>
                        <option value="selesai" {{ $laporan->status_laporan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ $laporan->status_laporan == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                      </select>
                      @else
                      <!-- Tampilkan teks statis untuk kolom tindakan yang tidak dapat diubah oleh non-puskesmas, non-lurah, atau non-jumantik -->
                      {{ $laporan->status_laporan }}
                      @endif
                    </td>
                    <td>{{ ucwords($laporan->ket_laporan) }}</td>
                    <td>
                      @if($laporan->bukti_laporan)
                      <button type="button" class="btn btn-primary view-image-btn" data-toggle="modal" data-target="#imageModal" data-image="{{ asset('storage/bukti_laporan/' . $laporan->bukti_laporan) }}" data-created-at="{{ $laporan->created_at->format('Y-m-d H:i:s') }}">
                        View
                      </button>
                      @else
                      Tidak ada bukti
                      @endif
                    </td>
                  </tr>
                  @endforeach
                  @if($userLaporans->isEmpty())
                  <tr>
                    <td colspan="7">Tidak ada data</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main><!-- End #main -->
<!-- Modal Tambah Data -->
<div class="modal fade" id="tambahDataModal" tabindex="-1" role="dialog" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formTambahDataForm" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <input type="hidden" id="id_user" name="id_user" class="form-control" value="{{ Auth::user()->id }}" readonly>
          </div>
          <div class="form-group">
            <label for="tgl_laporan">Tanggal Laporan</label>
            <input type="date" id="tgl_laporan" name="tgl_laporan" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="ket_laporan">Keterangan Laporan</label>
            <textarea id="ket_laporan" name="ket_laporan" class="form-control" required></textarea>
          </div>
          <div class="form-group">
            <label for="bukti_laporan">Bukti Laporan</label>
            <input type="file" id="bukti_laporan" name="bukti_laporan" class="form-control-file" accept="image/*" required>
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal View Image -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Bukti Laporan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img id="modalImage" src="" alt="Bukti Laporan" class="img-fluid">
        <p id="createdAtText"></p>
      </div>
    </div>
  </div>
</div>
<script>
  function formatDate(dateTimeStr) {
    const dateTime = new Date(dateTimeStr);
    const day = String(dateTime.getDate()).padStart(2, '0');
    const month = String(dateTime.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    const year = dateTime.getFullYear();
    const hours = String(dateTime.getHours()).padStart(2, '0');
    const minutes = String(dateTime.getMinutes()).padStart(2, '0');
    return `${day}/${month}/${year} ${hours}:${minutes}`;
  }


  //Handling form data change
  $(document).ready(function() {
    $('#btnTambahData').on('click', function() {
      $('#tambahDataModal').modal('show');
    });

    $('#formTambahDataForm').on('submit', function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        type: 'POST',
        url: "{{ route('laporan.store') }}",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          if (response.message === 'Data berhasil disimpan') {
            $('#tambahDataModal').modal('hide');
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: 'Data berhasil ditambah!',
              showConfirmButton: false,
              timer: 2000,
              customClass: {
                popup: 'animated tada'
              }
            }).then((result) => {
              if (result.dismiss === Swal.DismissReason.timer) {
                location.reload(); // Refresh the page after the alert closes
              }
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: response.message
            });
          }
        },
        error: function(response) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan. Silakan coba lagi.'
          });
        }
      });
    });

    // Handling the image modal
    $('.view-image-btn').on('click', function() {
      var imageUrl = $(this).data('image');
      var createdAt = $(this).data('created-at');
      $('#modalImage').attr('src', imageUrl);
      $('#createdAtText').text('Created at: ' + formatDate(createdAt));
    });

    // Handling the status change
    $('.status-dropdown').on('change', function() {
      var laporanId = $(this).data('id');
      var newStatus = $(this).val();

      $.ajax({
        type: 'POST',
        url: "{{ route('laporan.updateStatus', ':id') }}".replace(':id', laporanId),
        data: {
          _token: '{{ csrf_token() }}',
          status_laporan: newStatus
        },
        success: function(response) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: response.message
          });
        },
        error: function(response) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan. Silakan coba lagi.'
          });
        }
      });
    });
  });
</script>
@endsection
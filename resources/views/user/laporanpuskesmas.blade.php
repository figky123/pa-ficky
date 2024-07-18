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
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Laporan Puskesmas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <!-- Tambah Data Button -->
        @if(Auth::check() && Auth::user()->hasRole('Puskesmas'))
        <button id="btnTambahData" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahDataModal">Tambah Data</button>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Laporan Puskesmas</h5>
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Nama Petugas</th>
                                        <th>RW</th>
                                        <th>Tanggal Tindakan</th>
                                        <th>Keterangan</th>
                                        <th>Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tindakans as $tindakan)
                                    <tr>
                                        <td>{{ $tindakan->nama_petugas }}</td>
                                        <td>{{ $tindakan->RW }}</td>
                                        <td>{{ \Carbon\Carbon::parse($tindakan->tgl_tindakan)->translatedFormat('j F Y') }}</td>
                                        <td>{{ $tindakan->ket_tindakan }}</td>
                                        <td>
                                            @if($tindakan->bukti_tindakan)
                                            <button type="button" class="btn btn-primary view-image-btn" data-toggle="modal" data-target="#imageModal" data-image="{{ asset('storage/bukti_tindakan/' . $tindakan->bukti_tindakan) }}" data-created-at="{{ $tindakan->created_at->format('Y-m-d H:i:s') }}">
                                                View
                                            </button>
                                            @else
                                            Tidak ada bukti
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!-- End Table with stripped rows -->
                        <!-- Keterangan di atas tabel -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Bukti tindakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" width="50%" height="50%" src="" alt="Bukti tindakan" class="img-fluid">
                <p id="createdAtText" class="mt-3"></p>
            </div>
        </div>
    </div>
</div>
<!-- Tambah Data Modal -->
<div class="modal fade" id="tambahDataModal" tabindex="-1" role="dialog" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data tindakan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Form Tambah Data -->
            <div class="modal-body">
                <form id="formTambahData" method="post" action="{{ route('tindakan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nama_petugas">Nama Petugas</label>
                        <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" placeholder="Masukkan Nama Petugas" required>
                    </div>
                    <div class="form-group">
                        <label for="RW">RW</label>
                        <input type="text" class="form-control" id="RW" name="RW" placeholder="Masukkan RW" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_tindakan">Tanggal tindakan</label>
                        <input type="date" id="tgl_tindakan" name="tgl_tindakan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="ket_tindakan">Keterangan tindakan</label>
                        <input type="text" id="ket_tindakan" name="ket_tindakan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="bukti_tindakan">Bukti tindakan</label>
                        <input type="file" name="bukti_tindakan" id="bukti_tindakan" class="form-control">
                    </div>
                    <!-- Add other fields as necessary -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Tambah Data</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
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
    $(document).ready(function() {
        $('.view-image-btn').on('click', function() {
            var imageUrl = $(this).data('image');
            var createdAt = $(this).data('created-at');
            $('#modalImage').attr('src', imageUrl);
            $('#createdAtText').text('Waktu tindakan: ' + new Date(createdAt).toLocaleString());
        });

        // Modal tambah data
        $('#formTambahData').on('submit', function(event) {
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
                    $('#tambahDataModal').modal('hide'); // Menutup modal setelah menambahkan data
                    Swal.fire({
                        icon: 'success',
                        title: 'Data berhasil ditambahkan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload(); // Memperbarui tampilan dengan data yang baru ditambahkan
                    });
                },
                error: function(response) {
                    console.error('Terjadi kesalahan:', response);
                    var errorMessage = 'Terjadi kesalahan saat menambahkan data.';
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
                        icon: 'error',
                        title: 'Gagal menambahkan data',
                        html: errorMessage,
                        showConfirmButton: true
                    });
                }
            });
        });
    });
</script>
@endsection
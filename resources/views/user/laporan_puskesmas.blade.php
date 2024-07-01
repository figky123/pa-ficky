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
        <h1>Data Laporan Puskesmas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        @if(Auth::check() && Auth::user()->hasRole('Puskesmas'))
        <button id="btnTambahData" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahDataModal">Tambah Data</button>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Laporan Puskesmas</h5>

                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Nama Petugas</th>
                                        <th>Nama Warga</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Bukti</th>
                                        <th>Tindakan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="table-users">
                                    @php
                                    $loggedInUser = Auth::user();
                                    $userTindakans = App\Models\Tindakan::query();
                                    $userTindakans = $userTindakans->get();
                                    @endphp

                                    @foreach($userTindakans as $tindakan)
                                    <tr>
                                        <td>{{ $tindakan->user->name }}</td>
                                        <td>{{ $tindakan->pemeriksaan->laporan->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($tindakan->tgl_tindakan)->translatedFormat('d F Y') }}</td>
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
                                        <td>{{ $tindakan->aksi_tindakan }}</td>
                                        <td>{{ $tindakan->status_tindakan }}</td>
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

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Bukti Tindakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" width="50%" height="50%" src="" alt="Bukti Tindakan" class="img-fluid">
                <p id="createdAtText" class="mt-3"></p>
            </div>
        </div>
    </div>
</div>
<!-- Tambah Data Modal -->
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
                <form id="formTambahData" method="post" action="{{ route('tindakan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" id="id_user" name="id_user" class="form-control" value="{{ Auth::user()->id }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama Petugas</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ Auth::user()->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="id_pemeriksaan">Pemeriksaan<span class="text-danger">*</span></label>
                        <select class="form-control" id="id_pemeriksaan" name="id_pemeriksaan" required>
                            @foreach(App\Models\Pemeriksaan::all() as $pemeriksaan)
                            <option value="{{ $pemeriksaan->id }}">{{ $pemeriksaan->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tgl_tindakan">Tanggal Tindakan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tgl_tindakan" name="tgl_tindakan" required>
                    </div>
                    <div class="form-group">
                        <label for="ket_tindakan">Keterangan Tindakan <span class="text-danger">*</span></label>
                        <textarea id="ket_tindakan" name="ket_tindakan" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="bukti_tindakan">Bukti Tindakan <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="bukti_tindakan" name="bukti_tindakan" required>
                    </div>
                    <div class="form-group">
                        <label for="aksi_tindakan">Tindakan</label>
                        <select class="form-control" id="aksi_tindakan" name="aksi_tindakan" required>
                            <option value="fogging">Fogging</option>
                            <option value="penyuluhan">Penyuluhan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status_tindakan">Status Tindakan</label>
                        <input type="text" id="status_tindakan" name="status_tindakan" class="form-control" value="sudah ditindak" readonly>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </div>
                </form>
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
            $('#createdAtText').text('Waktu Tindakan: ' + new Date(createdAt).toLocaleString());
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
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan',
                        text: 'Silakan coba lagi',
                    });
                }
            });
        });
    });
</script>
@endsection
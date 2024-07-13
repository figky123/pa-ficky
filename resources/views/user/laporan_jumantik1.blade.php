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
        <h1>Laporan Jumantik</h1>
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
        @if(Auth::check() && Auth::user()->hasRole('Warga'))
        <button id="btnTambahData" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahDataModal">Tambah Data</button>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Laporan Jumantik</h5>
                        @php
                        $user = Auth::user(); // Get the currently logged-in user
                        @endphp
                        <!-- Keterangan kolom di atas tabel -->
                        @if ($user->role === 'Lurah' || $user->role === 'Puskesmas') <!-- Check if the user's role is 'Lurah' or 'Puskesmas' -->
                        <div class="alert alert-info" role="alert">
                            <strong>Keterangan Kolom Kontainer:</strong>
                            <ul>
                                <li>(✔️): Ditemukan jentik di kontainer</li>
                                <li>(❌): Tidak ditemukan jentik di kontainer</li>
                                <li>(Tidak ada): Kontainer tidak ada di lokasi</li>
                            </ul>
                        </div>
                        @php
                        $positif = $pemeriksaans->filter(function ($pemeriksaan) {
                        $jumlah = $pemeriksaan->bak_mandi + $pemeriksaan->ember + $pemeriksaan->vas_bunga + $pemeriksaan->lainnya_dalam + $pemeriksaan->ban_bekas + $pemeriksaan->kaleng_bekas;
                        return $jumlah > 0;
                        })->count();

                        $negatif = $pemeriksaans->count() - $positif;
                        @endphp

                        @if ($positif > 0)
                        <div class="alert alert-danger" role="alert">
                            Terdapat {{ $positif }} lokasi dengan status jentik positif.
                        </div>
                        @endif

                        @if ($negatif > 0)
                        <div class="alert alert-success" role="alert">
                            Terdapat {{ $negatif }} lokasi dengan status jentik negatif.
                        </div>
                        @endif

                        @if ($positif == 0 && $negatif == 0)
                        <div class="alert alert-info" role="alert">
                            Tidak ada data jentik yang tersedia.
                        </div>
                        @endif
                        @endif
                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Nama Kepala Keluarga</th>

                                        <th>Siklus</th>
                                        <th>Alamat</th>
                                        <th>RT</th>
                                        <th>RW</th>
                                        <th>Tanggal Pemeriksaan</th>
                                        <th>Bak Air/Mandi</th>
                                        <th>Ember</th>
                                        <th>Vas Bunga</th>
                                        <th>dll(dalam rumah)</th>
                                        <th>Ban Bekas</th>
                                        <th>Kaleng Bekas</th>
                                        <th>dll(luar rumah)</th>
                                        <th>Status Jentik</th>
                                        <th>Bukti Pemeriksaan</th>
                                        <th>Keterangan Pemeriksaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pemeriksaans as $pemeriksaan)
                                    <tr>
                                        <td>{{ $pemeriksaan->user->name }}</td>

                                        <td>{{ $pemeriksaan->siklus }}</td>
                                        <td>{{ $pemeriksaan->user->alamat }}</td>
                                        <td>{{ $pemeriksaan->user->RT }}</td>
                                        <td>{{ $pemeriksaan->user->RW }}</td>
                                        <td>{{ \Carbon\Carbon::parse($pemeriksaan->tgl_pemeriksaan)->translatedFormat('j F Y') }}</td>
                                        <td>{!! $pemeriksaan->bak_mandi == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : ($pemeriksaan->bak_mandi == 0 ? '<i class="fas fa-minus-circle" style="color: green;"></i>' : 'Tidak ada') !!}</td>
                                        <td>{!! $pemeriksaan->ember == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : ($pemeriksaan->ember == 0 ? '<i class="fas fa-minus-circle" style="color: green;"></i>' : 'Tidak ada') !!}</td>
                                        <td>{!! $pemeriksaan->vas_bunga == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : ($pemeriksaan->vas_bunga == 0 ? '<i class="fas fa-minus-circle" style="color: green;"></i>' : 'Tidak ada') !!}</td>
                                        <td>{!! $pemeriksaan->lainnya_dalam == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : ($pemeriksaan->lainnya_dalam == 0 ? '<i class="fas fa-minus-circle" style="color: green;"></i>' : 'Tidak ada') !!}</td>
                                        <td>{!! $pemeriksaan->ban_bekas == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : ($pemeriksaan->ban_bekas == 0 ? '<i class="fas fa-minus-circle" style="color: green;"></i>' : 'Tidak ada') !!}</td>
                                        <td>{!! $pemeriksaan->kaleng_bekas == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : ($pemeriksaan->kaleng_bekas == 0 ? '<i class="fas fa-minus-circle" style="color: green;"></i>' : 'Tidak ada') !!}</td>
                                        <td>{!! $pemeriksaan->lainnya_luar == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : ($pemeriksaan->lainnya_luar == 0 ? '<i class="fas fa-minus-circle" style="color: green;"></i>' : 'Tidak ada') !!}</td>

                                        <td>{{ $pemeriksaan->status_jentik }}</td>
                                        <td>
                                            @if($pemeriksaan->bukti_pemeriksaan)
                                            <button type="button" class="btn btn-primary view-image-btn" data-toggle="modal" data-target="#imageModal" data-image="{{ asset('storage/bukti_pemeriksaan/' . $pemeriksaan->bukti_pemeriksaan) }}" data-created-at="{{ $pemeriksaan->created_at->format('Y-m-d H:i:s') }}">
                                                View
                                            </button>
                                            @else
                                            Tidak ada bukti
                                            @endif
                                        </td>
                                        <td>{{ $pemeriksaan->ket_pemeriksaan }}</td>
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
                <h5 class="modal-title" id="imageModalLabel">Bukti Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" width="50%" height="50%" src="" alt="Bukti Pemeriksaan" class="img-fluid">
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
                <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data Pemeriksaan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Keterangan Simbol:</h5>
                    <ul>
                        <li>✔️: Ditemukan jentik di lokasi ini.</li>
                        <li>❌: Tidak ditemukan jentik di lokasi ini.</li>
                        <li>Tidak ada: Barang atau objek tersebut tidak ada di rumah.</li>
                    </ul>
                </div>
            </div>
            <!-- Form Tambah Data -->
            <div class="modal-body">
                <form id="formTambahData" method="post" action="{{ route('pemeriksaan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" id="id_user" name="id_user" class="form-control" value="{{ Auth::user()->id}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama Kepala Keluarga</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ Auth::user()->name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="siklus">Siklus</label>
                        <input type="text" class="form-control" id="siklus" name="siklus" placeholder="Masukkan Siklus" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_pemeriksaan">Tanggal Pemeriksaan</label>
                        <input type="date" id="tgl_pemeriksaan" name="tgl_pemeriksaan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="bak_mandi">Bak Mandi</label>
                        <select name="bak_mandi" id="bak_mandi" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="-1">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ember">Ember</label>
                        <select name="ember" id="ember" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="-1">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="vas_bunga">Vas Bunga</label>
                        <select name="vas_bunga" id="vas_bunga" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="-1">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="lainnya_dalam">Lainnya Dalam</label>
                        <select name="lainnya_dalam" id="lainnya_dalam" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="-1">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ban_bekas">Ban Bekas</label>
                        <select name="ban_bekas" id="ban_bekas" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="-1">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kaleng_bekas">Kaleng_Bekas</label>
                        <select name="kaleng_bekas" id="kaleng_bekas" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="-1">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="lainnya_luar">Lainnya Luar</label>
                        <select name="lainnya_luar" id="lainnya_luar" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="-1">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bukti_pemeriksaan">Bukti Pemeriksaan</label>
                        <input type="file" name="bukti_pemeriksaan" id="bukti_pemeriksaan" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="ket_pemeriksaan">Keterangan Pemeriksaan</label>
                        <textarea name="ket_pemeriksaan" id="ket_pemeriksaan" class="form-control"></textarea>
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
            $('#createdAtText').text('Waktu Pemeriksaan: ' + new Date(createdAt).toLocaleString());
        });
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
                    $('#tambahDataModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Data berhasil ditambahkan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan:', error);
                    if (xhr.status === 400) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Duplikasi Data',
                            text: xhr.responseJSON.error,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan',
                            text: 'Silakan coba lagi.',
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
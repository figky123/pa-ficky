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
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Status Pemeriksaan Telah Diperbarui',
        text: '{{ session('
        success ') }}',
        showConfirmButton: false,
        timer: 1500
    });
</script>
@endif
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Laporan Warga</h1>
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
                        <h5 class="card-title"></h5>
                        @php
                        $user = Auth::user(); // Get the currently logged-in user
                        @endphp
                        <!-- Keterangan kolom di atas tabel -->
                        @if ($user->role === 'RT') <!-- Check if the user's role is 'RT' -->
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
                        @if ($user->role === 'Warga') <!-- Check if the user's role is 'warga' -->
                        @foreach ($pemeriksaans as $pemeriksaan)
                        @if ($pemeriksaan->status_pemeriksaan === 'ditolak')
                        <div class="alert alert-warning" role="alert">
                            Data pemeriksaan anda ada yang ditolak. Silahkan datang ke RT setempat untuk validasi.
                        </div>
                        @break <!-- Exit loop once message is shown, assuming one message is enough -->
                        @endif
                        @endforeach
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
                                        <th>Status Pemeriksaan</th>
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
                                        <td>{!! $pemeriksaan->bak_mandi == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : '<i class="fas fa-minus-circle" style="color: green;"></i>' !!}</td>
                                        <td>{!! $pemeriksaan->ember == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : '<i class="fas fa-minus-circle" style="color: green;"></i>' !!}</td>
                                        <td>{!! $pemeriksaan->vas_bunga == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : '<i class="fas fa-minus-circle" style="color: green;"></i>' !!}</td>
                                        <td>{!! $pemeriksaan->lainnya_dalam == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : '<i class="fas fa-minus-circle" style="color: green;"></i>' !!}</td>
                                        <td>{!! $pemeriksaan->ban_bekas == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : '<i class="fas fa-minus-circle" style="color: green;"></i>' !!}</td>
                                        <td>{!! $pemeriksaan->kaleng_bekas == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : '<i class="fas fa-minus-circle" style="color: green;"></i>' !!}</td>
                                        <td>{!! $pemeriksaan->lainnya_luar == 1 ? '<i class="fas fa-plus-circle" style="color: red;"></i>' : '<i class="fas fa-minus-circle" style="color: green;"></i>' !!}</td>
                                        <td>{{ $pemeriksaan->status_jentik }}</td>
                                        @if(auth()->user()->role === 'RT')
                                        <td>
                                            @if($pemeriksaan->status_pemeriksaan == 'proses')
                                            <div class="btn-group-vertical" role="group">
                                                <a href="{{ route('update_status', ['id' => $pemeriksaan->id, 'status' => 'diterima']) }}" class="btn btn-primary btn-sm verifikasi-btn" data-id="{{ $pemeriksaan->id }}" title="Klik untuk memverifikasi laporan">
                                                    Verifikasi
                                                </a>
                                                <a href="{{ route('update_status', ['id' => $pemeriksaan->id, 'status' => 'ditolak']) }}" class="btn btn-danger btn mt-3 btn-sm tolak-btn" data-id="{{ $pemeriksaan->id }}" title="Klik untuk menolak laporan">
                                                    Tolak
                                                </a>
                                            </div>
                                            @else
                                            @if ($pemeriksaan->status_pemeriksaan == 'diterima')
                                            <span class="badge badge-success">Diterima</span>
                                            @elseif ($pemeriksaan->status_pemeriksaan == 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                            @else
                                            <span class="badge badge-secondary">{{ $pemeriksaan->status_pemeriksaan }}</span>
                                            @endif
                                            @endif
                                        </td>
                                        @elseif(auth()->user()->role === 'Warga')
                                        <td>
                                            @if ($pemeriksaan->status_pemeriksaan == 'proses')
                                            <span class="badge badge-warning">Proses</span>
                                            @elseif ($pemeriksaan->status_pemeriksaan == 'diterima')
                                            <span class="badge badge-success">Diterima</span>
                                            @elseif ($pemeriksaan->status_pemeriksaan == 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                            @else
                                            {{ $pemeriksaan->status_pemeriksaan }}
                                            @endif
                                        </td>
                                        @endif
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
                        <select name="siklus" id="siklus" class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
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
                            <option value="0">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ember">Ember</label>
                        <select name="ember" id="ember" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="0">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="vas_bunga">Vas Bunga</label>
                        <select name="vas_bunga" id="vas_bunga" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="0">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="lainnya_dalam">Lainnya Dalam</label>
                        <select name="lainnya_dalam" id="lainnya_dalam" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="0">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ban_bekas">Ban Bekas</label>
                        <select name="ban_bekas" id="ban_bekas" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="0">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kaleng_bekas">Kaleng_Bekas</label>
                        <select name="kaleng_bekas" id="kaleng_bekas" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="0">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="lainnya_luar">Lainnya Luar</label>
                        <select name="lainnya_luar" id="lainnya_luar" class="form-control">
                            <option value="1">✔️</option>
                            <option value="0">❌</option>
                            <option value="0">Tidak ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="file" class="form-control-file" id="bukti_pemeriksaan" name="bukti_pemeriksaan" accept="image/*" required>
                        <small id="fotoKkHelp" class="form-text text-muted">Upload foto pemeriksaan (format: jpeg, png, jpg, gif, svg | max: 2048 KB).</small>
                        <div id="fotoKkWarning" class="text-danger mt-2" style="display: none;">Format file tidak valid atau melebihi ukuran maksimum (2048 KB).</div>
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
        // Handler for viewing image
        $('.view-image-btn').on('click', function() {
            var imageUrl = $(this).data('image');
            var createdAt = $(this).data('created-at');
            $('#modalImage').attr('src', imageUrl);
            $('#createdAtText').text('Waktu Pemeriksaan: ' + new Date(createdAt).toLocaleString());
        });

        // Handler for form submission with confirmation
        $('#formTambahData').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            var url = $(this).attr('action');

            // Show confirmation message
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah data pemeriksaan yang Anda inputkan sudah benar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, benar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
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
                                    title: 'Terjadi Kesalahan',
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
                }
            });
        });

        $('.verifikasi-btn, .tolak-btn').on('click', function(event) {
            event.preventDefault(); // Prevent default link action
            var href = $(this).attr('href'); // Get the URL from the href attribute
            var action = $(this).hasClass('verifikasi-btn') ? 'verifikasi' : 'tolak';
            var confirmationMessage = action === 'verifikasi' ?
                'Apakah Anda yakin laporan ini akan diverifikasi?' :
                'Apakah Anda yakin laporan ini akan ditolak?';

            Swal.fire({
                title: 'Konfirmasi',
                text: confirmationMessage,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: action === 'verifikasi' ? 'Ya, verifikasi!' : 'Ya, tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href; // Redirect to the URL
                }
            });
        });
    });
</script>
@endsection
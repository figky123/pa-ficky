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
        <h1>Data Laporan Jumantik 1</h1>
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
         @if(Auth::check() && Auth::user()->hasRole('Jumantik'))
                        <button id="btnTambahData" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahDataModal">Tambah Data</button>
                        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Laporan Jumantik 1</h5>
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
                        $jumlah = $pemeriksaan->kaleng_bekas + $pemeriksaan->pecahan_botol + $pemeriksaan->ban_bekas + $pemeriksaan->tempayan + $pemeriksaan->bak_mandi + $pemeriksaan->lain_lain;
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
                                        <th>Nama Warga</th>
                                        <th>Nama Petugas</th>
                                        <th>Siklus</th>
                                        <th>Alamat</th>
                                        <th>RT</th>
                                        <th>RW</th>
                                        <th>Tanggal Laporan</th>
                                        <th>Status Laporan</th>
                                        <th>Kaleng Bekas</th>
                                        <th>Pecahan Botol</th>
                                        <th>Ban Bekas</th>
                                        <th>Tempayan</th>
                                        <th>Bak Mandi</th>
                                        <th>Lainnya</th>
                                        <th>Status Jentik</th>
                                        <th>Bukti Pemeriksaan</th>
                                        <th>Keterangan Pemeriksaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $loggedInUser = Auth::user();
                                    $userPemeriksaans = App\Models\Pemeriksaan::query();

                                    if ($loggedInUser->hasRole('Jumantik')) {
                                    $userPemeriksaans->where('id_user', $loggedInUser->id);
                                    } elseif ($loggedInUser->hasRole('Warga')) {
                                    $userPemeriksaans->whereHas('laporan.user', function($query) use ($loggedInUser) {
                                    $query->where('id', $loggedInUser->id);
                                    });
                                    }

                                    $userPemeriksaans = $userPemeriksaans->get();
                                    @endphp

                                    @foreach($userPemeriksaans as $pemeriksaan)
                                    <tr>
                                        <td>{{ $pemeriksaan->laporan->user->name }}</td>
                                        <td>{{ $pemeriksaan->user->name }}</td>
                                        <td>{{ $pemeriksaan->siklus }}</td>
                                        <td>{{ $pemeriksaan->laporan->user->alamat }}</td>
                                        <td>{{ $pemeriksaan->laporan->user->RT }}</td>
                                        <td>{{ $pemeriksaan->laporan->user->RW }}</td>
                                        <td>{{ \Carbon\Carbon::parse($pemeriksaan->laporan->tgl_laporan)->translatedFormat('d F Y') }}</td>
                                        <td>{{ $pemeriksaan->laporan->status_laporan }}</td>
                                        <td>{{ $pemeriksaan->kaleng_bekas == 1 ? '✔️' : ($pemeriksaan->kaleng_bekas == 0 ? '❌' : ($pemeriksaan->kaleng_bekas == -1 ? 'Tidak ada' : '')) }}</td>
                                        <td>{{ $pemeriksaan->pecahan_botol == 1 ? '✔️' : ($pemeriksaan->pecahan_botol == 0 ? '❌' : ($pemeriksaan->pecahan_botol == -1 ? 'Tidak ada' : '')) }}</td>
                                        <td>{{ $pemeriksaan->ban_bekas == 1 ? '✔️' : ($pemeriksaan->ban_bekas == 0 ? '❌' : ($pemeriksaan->ban_bekas == -1 ? 'Tidak ada' : '')) }}</td>
                                        <td>{{ $pemeriksaan->tempayan == 1 ? '✔️' : ($pemeriksaan->tempayan == 0 ? '❌' : ($pemeriksaan->tempayan == -1 ? 'Tidak ada' : '')) }}</td>
                                        <td>{{ $pemeriksaan->bak_mandi == 1 ? '✔️' : ($pemeriksaan->bak_mandi == 0 ? '❌' : ($pemeriksaan->bak_mandi == -1 ? 'Tidak ada' : '')) }}</td>
                                        <td>{{ $pemeriksaan->lain_lain == 1 ? '✔️' : ($pemeriksaan->lain_lain == 0 ? '❌' : ($pemeriksaan->lain_lain == -1 ? 'Tidak ada' : '')) }}</td>
                                        <td>
                                            @php
                                            $kaleng_bekas_value = $pemeriksaan->kaleng_bekas == -1 ? 0 : $pemeriksaan->kaleng_bekas;
                                            $pecahan_botol_value = $pemeriksaan->pecahan_botol == -1 ? 0 : $pemeriksaan->pecahan_botol;
                                            $ban_bekas_value = $pemeriksaan->ban_bekas == -1 ? 0 : $pemeriksaan->ban_bekas;
                                            $tempayan_value = $pemeriksaan->tempayan == -1 ? 0 : $pemeriksaan->tempayan;
                                            $bak_mandi_value = $pemeriksaan->bak_mandi == -1 ? 0 : $pemeriksaan->bak_mandi;
                                            $lain_lain_value = $pemeriksaan->lain_lain == -1 ? 0 : $pemeriksaan->lain_lain;

                                            $jumlah = $kaleng_bekas_value + $pecahan_botol_value + $ban_bekas_value + $tempayan_value + $bak_mandi_value + $lain_lain_value;
                                            $status_jentik = $jumlah > 0 ? 'positif' : 'negatif';
                                            @endphp
                                            {{ $status_jentik }}
                                        </td>
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
                <!-- Form Tambah Data -->
                <div class="modal-body">
                <form id="formTambahData" method="post" action="{{ route('pemeriksaan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="id_laporan">Laporan<span class="text-danger">*</span></label>
                        <select class="form-control" id="id_laporan" name="id_laporan" required>
                            @php
                            $uniquePemeriksaans = App\Models\Pemeriksaan::all()->unique('laporan.user.name');
                            @endphp
                            @foreach($uniquePemeriksaans as $pemeriksaan)
                            <option value="{{ $pemeriksaan->id }}">{{ $pemeriksaan->laporan->user->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-group">
                            <input type="hidden" id="id_user" name="id_user" class="form-control" value="{{ Auth::user()->id}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Nama Petugas</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="siklus">Siklus</label>
                            <input type="text" class="form-control" id="siklus" name="siklus" placeholder="Masukkan Siklus" required>
                        </div>
                        <div class="form-group">
                            <label for="kaleng_bekas">Kaleng Bekas</label>
                            <select name="kaleng_bekas" id="kaleng_bekas" class="form-control">
                                <option value="1">✔️</option>
                                <option value="0">❌</option>
                                <option value="-1">Tidak ada</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pecahan_botol">Pecahan Botol</label>
                            <select name="pecahan_botol" id="pecahan_botol" class="form-control">
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
                            <label for="tempayan">Tempayan</label>
                            <select name="tempayan" id="tempayan" class="form-control">
                                <option value="1">✔️</option>
                                <option value="0">❌</option>
                                <option value="-1">Tidak ada</option>
                            </select>
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
                            <label for="lain_lain">Lainnya</label>
                            <select name="lain_lain" id="lain_lain" class="form-control">
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
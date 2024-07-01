@extends('layout.master')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Form Edit</h1>
        <nav>
            <ol class="breadcrumb">
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="background-color: #007bff; color: #fff; font-weight: bold;">Edit Warga</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.update', $user->id) }}" class="p-2">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                            </div>

                            <div class="form-group">
                                <label for="no_kk">Nomor KK</label>
                                <input type="text" class="form-control" id="no_kk" name="no_kk" value="{{ $user->no_kk }}">
                            </div>

                            <div class="form-group">
                                <label for="no_hp_user">Nomor HP</label>
                                <input type="text" class="form-control" id="no_hp_user" name="no_hp_user" value="{{ $user->no_hp_user }}">
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ $user->alamat }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="RT">RT</label>
                                <input type="text" class="form-control" id="RT" name="RT" value="{{ $user->RT }}">
                            </div>

                            <div class="form-group">
                                <label for="RW">RW</label>
                                <input type="text" class="form-control" id="RW" name="RW" value="{{ $user->RW }}">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
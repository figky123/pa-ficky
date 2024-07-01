<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Register - Registrasi Akun</title>
    <!-- Custom fonts for this template-->
    <link href="{{ asset('/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('/assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 35%, rgba(0, 212, 255, 1) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            height: 100%;
        }

        .card img {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }

        .form-control-user {
            border-radius: 10px;
            padding: 1rem;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control-user:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn-user {
            border-radius: 10px;
            background: #4e73df;
            color: white;
        }

        .btn-user:hover {
            background: #2e59d9;
        }

        .text-center h1 {
            color: #4e73df;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gradient-primary">
    @if(\Session::has('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Akun Berhasil Ditambahkan',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    @endif
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="row g-0">
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="{{ asset('/assets/img/admin.png') }}" alt="Register Image">
                </div>
                <div class="col-lg-6 d-flex align-items-center">
                    <div class="p-5 w-100">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Registrasi Akun!</h1>
                        </div>
                        <form class="user" method="post" enctype="multipart/form-data" action="/register">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="name" name="name" placeholder="Nama" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" id="no_kk" name="no_kk" placeholder="No KK" required>
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0 mt-3">
                                    <input type="text" class="form-control form-control-user" id="no_hp_user" name="no_hp_user" placeholder="No HP" required>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <input type="text" class="form-control form-control-user" id="alamat" name="alamat" placeholder="Alamat" required>
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0 mt-3">
                                    <input type="text" class="form-control form-control-user" id="RT" name="RT" placeholder="RT" required>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <input type="text" class="form-control form-control-user" id="RW" name="RW" placeholder="RW" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <div class="custom-select">
                                    <select name="role">
                                        <option value="" disabled selected>Pilih Role</option>
                                        <option value="Warga">Warga</option>
                                        <option value="Jumantik">Jumantik</option>
                                        <option value="Puskesmas">Puskesmas</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Register
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="login">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('/assets/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>User</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <!-- =======================================================
    * Template Name: NiceAdmin
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Updated: Apr 20 2024 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="{{ asset('assets/img/logo.png') }}" alt="">
                <span class="d-none d-lg-block">Simantik</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
                    </a><!-- End Profile Image Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ Auth::user()->name }}</h6>
                            <span>{{ Auth::user()->role }}</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                                <i class="bi bi-gear"></i>
                                <span>Account Settings</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                                <i class="bi bi-question-circle"></i>
                                <span>Need Help?</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->
            </ul>
        </nav><!-- End Icons Navigation -->
    </header><!-- End Header -->
    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            @if(Auth::user()->role == 'Jumantik')
            <li class="nav-item">
                <a class="nav-link collapsed" href="dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_warga">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Warga</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_jumantik1">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Jumantik 1</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_jumantik2">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Jumantik 2</span>
                </a>
            </li>
            @elseif(Auth::user()->role == 'Puskesmas')
            <li class="nav-item">
                <a class="nav-link collapsed" href="dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_warga">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Warga</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_jumantik1">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Jumantik 1</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_jumantik2">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Jumantik 2</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_puskesmas">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Puskesmas</span>
                </a>
            </li>
            @elseif(Auth::user()->role == 'Warga')
            <li class="nav-item">
                <a class="nav-link collapsed" href="dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_warga">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Warga</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_jumantik1">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Jumantik 1</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_puskesmas">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Puskesmas</span>
                </a>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link collapsed" href="dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="table_warga">
                    <i class="bi bi-layout-text-window-reverse"></i>
                    <span>Data Warga</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="table_petugas">
                    <i class="bi bi-layout-text-window-reverse"></i>
                    <span>Data Petugas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_warga">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Warga</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_jumantik1">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Jumantik 1</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_jumantik2">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Jumantik 2</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan_puskesmas">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Puskesmas</span>
                </a>
            </li>
            @endif
        </ul>
    </aside><!-- End Sidebar -->

    @yield('content')

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            © Copyright <strong><span>Simantik</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">Figky Mandala Putra</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
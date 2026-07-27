<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>OLGA SEHAT | BackOffice</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('template/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('template/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('template/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('template/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('template/plugins/summernote/summernote-bs4.min.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('aset/logo.png') }}" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- Right navbar links -->
    
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img src="{{ asset('aset/logo.png') }}" alt="OLga Sehat Logo" class="brand-image img-circle elevation-3" style="opacity: .5">
      <span class="brand-text font-weight-light">OLga Sehat</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('aset/user.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
           <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Dashboard -->
          <li class="nav-item">
            <a href="/dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- Gallery -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-image"></i>
              <p>
                Galeri
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/galeri/home-banner" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Home Banner</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/galeri/lapangan-banner" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Lapangan Banner</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/galeri/kesehatan-banner" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kesehatan Banner</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/galeri/venue-banner" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Venue Banner</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Program -->
          <li class="nav-item">
            <a href="/programs" class="nav-link">
              <i class="nav-icon fas fa-heartbeat"></i>
              <p>Program</p>
            </a>
          </li>

          <!-- Review -->
          <li class="nav-item">
            <a href="/review" class="nav-link">
              <i class="nav-icon fas fa-star"></i>
              <p>Review</p>
            </a>
          </li>

          <!-- Pemilik Fasilitas -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Verifikasi Mitra
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('mitra.datapemiliklapangan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pemilik Lapangan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/tempat-sehat" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pemilik Kesehatan</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Data Layanan Kesehatan -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-heartbeat"></i>
              <p>
                Layanan Kesehatan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('health.clinics.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Verifikasi Klinik</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('health.bookings.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Janji Temu Klinik</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Manajemen Venue -->
          <li class="nav-item">
            <a href="{{ route('admin.venue.list') }}" class="nav-link">
              <i class="nav-icon fas fa-map-marker-alt"></i>
              <p>
                Data Venue
              </p>
            </a>
          </li>

          <!-- Blog & News -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-newspaper"></i>
              <p>
                Aktivitas
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('activity-types.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Type Aktivitas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('activity-types.daftar') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daftar Aktivitas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('activities.verifikasi-pembayaran') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Verifikasi Pembayaran</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Keuangan -->
          <li class="nav-item">
            <a href="{{ route('admin.keuangan') }}" class="nav-link">
              <i class="nav-icon fas fa-wallet"></i>
              <p>Keuangan & Komisi</p>
            </a>
          </li>

          <!-- Blog & News -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-newspaper"></i>
              <p>
                Blog & News
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/category" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/newss" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Blog & News</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Galeri & Banner -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-images"></i>
              <p>
                Galeri & Banner
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('galeri.home-banner') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Home Banner</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('galeri.lapangan-banner') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Lapangan Banner</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('galeri.kesehatan-banner') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kesehatan Banner</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('galeri.venue-banner') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Venue Banner</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('galeri') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Semua Galeri</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Data Program -->
          <li class="nav-item">
            <a href="{{ route('programs') }}" class="nav-link">
              <i class="nav-icon fas fa-tasks"></i>
              <p>Data Program</p>
            </a>
          </li>

          <!-- Ulasan & Testimoni -->
          <li class="nav-item">
            <a href="{{ route('review.index') }}" class="nav-link">
              <i class="nav-icon fas fa-star"></i>
              <p>Ulasan & Testimoni</p>
            </a>
          </li>

          <!-- Extra -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-plus"></i>
              <p>
                Extra
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/social-media" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Social Media</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/contact" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Contact Us</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/about" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>About Us</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Manajemen User -->
          <li class="nav-item">
            <a href="{{ route('admin.users.list') }}" class="nav-link">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>Manajemen User</p>
            </a>
          </li>

          <!-- Accounts -->
          <li class="nav-item">
            <a href="/akun" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Data Akun System</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  @yield('content')

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2025 <a href="https://www.indoapps.id/">PT. INDO APPS SOLUSINDO</a>.</strong>
    <div class="float-right d-none d-sm-inline-block">
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('template/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('template/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('template/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('template/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('template/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('template/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('template/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('template/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('template/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('template/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('template/dist/js/demo.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('template/dist/js/pages/dashboard.js') }}"></script>
</body>
</html>

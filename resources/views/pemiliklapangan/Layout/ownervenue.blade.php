<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>OLGA SEHAT | Pemilik Lapangan</title>

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
  <style>
    .nav-sidebar .nav-link {
      position: relative;
    }
    .nav-sidebar .nav-link.active {
      background-color: rgba(255, 255, 255, 0.1) !important;
      color: #ffffff !important;
      font-weight: 600;
    }
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active:hover {
      background-color: rgba(255, 255, 255, 0.1) !important;
    }
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 4px;
      background-color: #007bff;
      z-index: 1;
    }
    .nav-sidebar .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.05) !important;
    }
    .owner-topbar .owner-avatar-sm,
    .owner-topbar .owner-avatar-lg {
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f1f5ff;
      color: #1c2a56;
      font-weight: 700;
      border-radius: 50%;
      overflow: hidden;
    }
    .owner-topbar .owner-avatar-sm {
      width: 36px;
      height: 36px;
      font-size: 0.9rem;
    }
    .owner-topbar .owner-avatar-lg {
      width: 64px;
      height: 64px;
      font-size: 1.25rem;
    }
    .owner-topbar .owner-avatar-sm img,
    .owner-topbar .owner-avatar-lg img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .owner-avatar-toggle {
      color: #1c2a56;
      font-weight: 600;
    }
    .owner-avatar-toggle i {
      font-size: 0.7rem;
    }
    .owner-dropdown {
      width: 240px;
      border-radius: 18px;
      padding: 20px 20px 12px;
    }
    .owner-profile-card h6 {
      font-weight: 700;
      color: #1c2a56;
    }
    .badge-role {
      background: rgba(40, 200, 120, 0.15);
      color: #1f9d67;
      border-radius: 999px;
      font-weight: 600;
      font-size: 0.75rem;
      padding: 0.25rem 0.75rem;
    }
    .owner-dropdown .dropdown-item {
      border-radius: 12px;
      padding: 0.55rem 0.75rem;
      font-weight: 600;
      color: #1c2a56;
    }
    .owner-dropdown .dropdown-item:hover {
      background: #f1f5ff;
      color: #1c2a56;
    }
  </style>
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
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown owner-topbar">
        <a class="nav-link d-flex align-items-center owner-avatar-toggle" data-toggle="dropdown" href="#">
          <div class="owner-avatar-sm mr-2">
            @if(Auth::user()->image ?? false)
              <img src="{{ asset(Auth::user()->image) }}" alt="{{ Auth::user()->name }}">
            @else
              <span>{{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}</span>
            @endif
          </div>
          <span class="owner-name d-none d-md-inline">{{ Auth::user()->name ?? 'Pemilik' }}</span>
          <i class="fas fa-chevron-down ml-2"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right owner-dropdown shadow-lg border-0">
          <div class="owner-profile-card text-center mb-3">
            <div class="owner-avatar-lg mx-auto mb-2">
              @if(Auth::user()->image ?? false)
                <img src="{{ asset(Auth::user()->image) }}" alt="{{ Auth::user()->name }}">
              @else
                <span>{{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}</span>
              @endif
            </div>
            <h6 class="mb-0">{{ Auth::user()->name ?? 'Pemilik' }}</h6>
            <span class="badge badge-role mt-1">{{ ucwords(str_replace('_', ' ', Auth::user()->role ?? 'Administrator')) }}</span>
          </div>
          <a href="{{ route('pemilik.pengaturan') }}" class="dropdown-item d-flex align-items-center">
            <span>Keamanan Akun</span>
            <i class="fas fa-info-circle ml-auto text-muted"></i>
          </a>
          <a href="{{ route('pemilik.pengaturan') }}" class="dropdown-item d-flex align-items-center">
            <span>Profil User</span>
            <i class="fas fa-info-circle ml-auto text-muted"></i>
          </a>
          <a href="{{ route('pemilik.pengaturan') }}" class="dropdown-item d-flex align-items-center">
            <span>Profil Bisnis</span>
            <i class="fas fa-info-circle ml-auto text-muted"></i>
          </a>
          <a href="{{ route('pemilik.pengaturan') }}" class="dropdown-item d-flex align-items-center">
            <span>Pengaturan Pengelola</span>
            <i class="fas fa-info-circle ml-auto text-muted"></i>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item text-danger">Sign Out</a>
        </div>
      </li>
    </ul>
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
            <a href="/pemiliklapangan/dashboard" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- Analytics -->
          <li class="nav-item">
            <a href="/analytics" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Analytics</p>
            </a>
          </li>

          <!-- Separator -->
          <li class="nav-header">MAIN MENU</li>

          <!-- Kelola Fasilitas -->
          <li class="nav-item">
            <a href="{{ route('fasilitas') }}" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>Kelola Fasilitas</p>
            </a>
          </li>

          <!-- Papan Jadwal -->
          <li class="nav-item">
            <a href="/papan" class="nav-link">
              <i class="nav-icon fas fa-calendar"></i>
              <p>Kelola Jadwal</p>
            </a>
          </li>

          <!-- Buat Komunitas -->
          <li class="nav-item">
            <a href="{{ route('pemilik.komunitas') }}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Komunitas</p>
            </a>
          </li>

          <!-- Membership -->
          <li class="nav-item">
            <a href="{{ route('pemilik.membership') }}" class="nav-link">
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>Membership</p>
            </a>
          </li>

          <!-- Event -->
          <li class="nav-item">
            <a href="{{ route('pemilik.event') }}" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Event</p>
            </a>
          </li>

          @php
            $isKeuangan = request()->is('keuangan*');
            $isKeuanganFasilitas = request()->is('keuangan/fasilitas');
            $isKeuanganKomunitas = request()->is('keuangan/komunitas');
            $isKeuanganMembership = request()->is('keuangan/membership');
            $isKeuanganEvent = request()->is('keuangan/event');
          @endphp
          <!-- Keuangan -->
          <li class="nav-item {{ $isKeuangan ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ $isKeuangan ? 'active' : '' }}">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                Keuangan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item {{ ($isKeuanganFasilitas || $isKeuanganKomunitas || $isKeuanganMembership || $isKeuanganEvent) ? 'menu-is-opening menu-open' : '' }}">
                <a href="#" class="nav-link {{ ($isKeuanganFasilitas || $isKeuanganKomunitas || $isKeuanganMembership || $isKeuanganEvent) ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Riwayat Transaksi
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('keuangan.fasilitas') }}" class="nav-link {{ $isKeuanganFasilitas ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Fasilitas</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('keuangan.komunitas') }}" class="nav-link {{ $isKeuanganKomunitas ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Komunitas</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('keuangan.membership') }}" class="nav-link {{ $isKeuanganMembership ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Membership</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('keuangan.event') }}" class="nav-link {{ $isKeuanganEvent ? 'active' : '' }}">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Event</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          <!-- Diskon & Promosi -->
          <li class="nav-item">
            <a href="/pemiliklapangan/promo" class="nav-link">
              <i class="nav-icon fas fa-percent"></i>
              <p>Diskon & Promosi</p>
            </a>
          </li>

          <!-- Separator -->
          <li class="nav-header">MENU SETTING</li>

          <!-- Pengaturan -->
          <li class="nav-item">
            <a href="/pemiliklapangan/pengaturan" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>Pengaturan</p>
            </a>
          </li>

          <!-- Sign Out -->
          <li class="nav-item">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Sign Out</p>
            </a>
          </li>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
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

<!-- Auto-detect active menu -->
<script>
  $(document).ready(function() {
    var currentUrl = window.location.href;
    var currentPath = window.location.pathname;
    
    // Remove active class from all nav links
    $('.nav-sidebar .nav-link').removeClass('active');
    
    // Find and activate the matching nav link
    $('.nav-sidebar .nav-link').each(function() {
      var linkUrl = $(this).attr('href');
      if (linkUrl) {
        // Check if current path matches the link
        if (currentPath === linkUrl || currentUrl.indexOf(linkUrl) !== -1) {
          $(this).addClass('active');
          // Also activate parent if it's a treeview
          $(this).closest('.nav-item').addClass('menu-open');
          $(this).closest('.nav-treeview').siblings('.nav-link').addClass('active');
        }
      }
    });
    
    // For routes that might have dynamic segments, check if path starts with
    $('.nav-sidebar .nav-link').each(function() {
      var linkUrl = $(this).attr('href');
      if (linkUrl && linkUrl !== '#' && linkUrl !== '/') {
        // Check if current path starts with the link path
        if (currentPath.startsWith(linkUrl) && linkUrl !== '/pemiliklapangan/dashboard') {
          $(this).addClass('active');
        }
      }
    });
  });
</script>
</body>
</html>

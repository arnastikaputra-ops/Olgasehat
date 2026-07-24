<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>OLGA SEHAT | Pengelola Kesehatan</title>

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
      background-color: #28a745;
      z-index: 1;
    }
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active {
      background-color: rgba(40, 167, 69, 0.15) !important;
      color: #ffffff !important;
      font-weight: 600;
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
    
    /* Responsive improvements */
    @media (max-width: 768px) {
      .main-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
      }
      .main-sidebar.show {
        transform: translateX(0);
      }
      .content-wrapper {
        margin-left: 0 !important;
      }
      .small-box {
        margin-bottom: 1rem;
      }
      .small-box .inner h3 {
        font-size: 1.5rem;
      }
      .table-responsive {
        font-size: 0.875rem;
      }
      .card-title {
        font-size: 1rem;
      }
    }
    
    @media (max-width: 576px) {
      .small-box .inner {
        padding: 10px;
      }
      .small-box .inner h3 {
        font-size: 1.25rem;
      }
      .small-box .inner p {
        font-size: 0.875rem;
      }
      .small-box .icon {
        font-size: 3rem;
      }
      .table th,
      .table td {
        padding: 0.5rem;
        font-size: 0.8rem;
      }
      .card-header {
        padding: 0.75rem 1rem;
      }
      .card-body {
        padding: 0.75rem;
      }
      .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
      }
    }
    
    /* Better spacing for cards */
    .row {
      margin-left: -7.5px;
      margin-right: -7.5px;
    }
    .row > * {
      padding-left: 7.5px;
      padding-right: 7.5px;
    }
    
    /* Responsive table */
    @media (max-width: 992px) {
      .table-responsive {
        border: 0;
      }
      .table thead {
        display: none;
      }
      .table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
      }
      .table tbody td {
        display: block;
        text-align: right;
        padding: 0.5rem;
        border-bottom: 1px solid #dee2e6;
      }
      .table tbody td:before {
        content: attr(data-label);
        float: left;
        font-weight: bold;
      }
      .table tbody td:last-child {
        border-bottom: 0;
      }
    }
    
    /* Form improvements */
    .form-group {
      margin-bottom: 1rem;
    }
    .form-control {
      border-radius: 0.375rem;
    }
    .form-control:focus {
      border-color: #28a745;
      box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    
    /* Button improvements */
    .btn {
      border-radius: 0.375rem;
      font-weight: 500;
    }
    .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }
    .btn-group .btn {
      margin-right: 0.25rem;
    }
    .btn-group .btn:last-child {
      margin-right: 0;
    }
    
    /* Card improvements */
    .card {
      border-radius: 0.5rem;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card-header {
      border-bottom: 1px solid rgba(0, 0, 0, 0.125);
      padding: 0.75rem 1.25rem;
    }
    .card-body {
      padding: 1.25rem;
    }
    
    /* Responsive buttons */
    @media (max-width: 767px) {
      .btn-md-inline-block {
        display: block;
        width: 100%;
      }
      .btn-md-inline-block + .btn-md-inline-block {
        margin-top: 0.5rem;
      }
    }
    @media (min-width: 768px) {
      .btn-md-inline-block {
        display: inline-block;
        width: auto;
      }
    }
    
    /* Table improvements */
    .table {
      margin-bottom: 0;
    }
    .table th {
      font-weight: 600;
      border-top: none;
    }
    .table td {
      vertical-align: middle;
    }
    
    /* Empty state */
    .table tbody td.text-center {
      padding: 2rem;
    }
    .table tbody td.text-center i {
      display: block;
      margin-bottom: 0.5rem;
    }
    
    /* Badge improvements */
    .badge {
      padding: 0.35em 0.65em;
      font-weight: 500;
    }
    
    /* Content header improvements */
    .content-header h1 {
      font-size: 1.75rem;
      font-weight: 600;
    }
    @media (max-width: 576px) {
      .content-header h1 {
        font-size: 1.5rem;
      }
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
              <span>{{ strtoupper(substr(Auth::user()->name ?? 'PK', 0, 1)) }}</span>
            @endif
          </div>
          <span class="owner-name d-none d-md-inline">{{ Auth::user()->name ?? 'Pengelola' }}</span>
          <i class="fas fa-chevron-down ml-2"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right owner-dropdown shadow-lg border-0">
          <div class="owner-profile-card text-center mb-3">
            <div class="owner-avatar-lg mx-auto mb-2">
              @if(Auth::user()->image ?? false)
                <img src="{{ asset(Auth::user()->image) }}" alt="{{ Auth::user()->name }}">
              @else
                <span>{{ strtoupper(substr(Auth::user()->name ?? 'PK', 0, 1)) }}</span>
              @endif
            </div>
            <h6 class="mb-0">{{ Auth::user()->name ?? 'Pengelola' }}</h6>
            <span class="badge badge-role mt-1">Pengelola Kesehatan</span>
          </div>
          <a href="{{ route('pengelola.pengaturan') }}" class="dropdown-item d-flex align-items-center">
            <span>Keamanan Akun</span>
            <i class="fas fa-info-circle ml-auto text-muted"></i>
          </a>
          <a href="{{ route('pengelola.pengaturan') }}" class="dropdown-item d-flex align-items-center">
            <span>Profil User</span>
            <i class="fas fa-info-circle ml-auto text-muted"></i>
          </a>
          <a href="{{ route('pengelola.pengaturan') }}" class="dropdown-item d-flex align-items-center">
            <span>Profil Bisnis</span>
            <i class="fas fa-info-circle ml-auto text-muted"></i>
          </a>
          <a href="{{ route('pengelola.pengaturan') }}" class="dropdown-item d-flex align-items-center">
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
            <a href="{{ route('pengelola.dashboard') }}" class="nav-link {{ request()->routeIs('pengelola.dashboard*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- Analytics -->
          <li class="nav-item">
            <a href="{{ route('pengelola.analytics') }}" class="nav-link {{ request()->is('pengelolakesehatan/analytics') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Analytics</p>
            </a>
          </li>

          <!-- Separator -->
          <li class="nav-header">MAIN MENU</li>

          <!-- Kelola Fasilitas Kesehatan -->
          <li class="nav-item">
            <a href="{{ route('pengelola.clinics') }}" class="nav-link {{ request()->routeIs('pengelola.clinics*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-hospital"></i>
              <p>Klinik</p>
            </a>
          </li>

          <!-- Dokter -->
          <li class="nav-item">
            <a href="{{ route('pengelola.doctors.index') }}" class="nav-link {{ request()->routeIs('pengelola.doctors*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-md"></i>
              <p>Dokter</p>
            </a>
          </li>

          <!-- Jadwal Dokter -->
          <li class="nav-item">
            <a href="{{ route('pengelola.schedules.index') }}" class="nav-link {{ request()->routeIs('pengelola.schedules*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Jadwal Dokter</p>
            </a>
          </li>

          <!-- Layanan Kesehatan -->
          <li class="nav-item">
            <a href="{{ route('pengelola.services.index') }}" class="nav-link {{ request()->routeIs('pengelola.services*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-heartbeat"></i>
              <p>Layanan</p>
            </a>
          </li>

          <!-- Booking -->
          <li class="nav-item">
            <a href="{{ route('pengelola.bookings.index') }}" class="nav-link {{ request()->routeIs('pengelola.bookings*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-calendar-check"></i>
              <p>Booking</p>
            </a>
          </li>

          <!-- Separator -->
          <li class="nav-header">MENU SETTING</li>

          <!-- Pengaturan -->
          <li class="nav-item">
            <a href="{{ route('pengelola.pengaturan') }}" class="nav-link {{ request()->is('pengelolakesehatan/pengaturan*') ? 'active' : '' }}">
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
    // Fungsi untuk mengatur menu aktif
    function setActiveMenu() {
      var currentUrl = window.location.href;
      var currentPath = window.location.pathname;
      
      console.log('Current URL:', currentUrl);
      console.log('Current Path:', currentPath);
      
      // Hapus semua kelas aktif terlebih dahulu
      $('.nav-sidebar .nav-link').removeClass('active');
      $('.nav-item').removeClass('menu-open');
      
      // Variabel untuk melacak apakah sudah ditemukan menu yang aktif
      var activeFound = false;
      
      // Iterasi melalui semua link di sidebar
      $('.nav-sidebar .nav-link').each(function() {
        var $link = $(this);
        var linkUrl = $link.attr('href');
        
        // Skip jika link tidak valid
        if (!linkUrl || linkUrl === '#' || linkUrl === '/') {
          return true; // continue
        }
        
        // Normalisasi URL untuk perbandingan
        var normalizedLink = linkUrl.replace(/\/+$/, ''); // Hapus trailing slash
        var normalizedCurrent = currentUrl.replace(/\/+$/, '');
        var normalizedCurrentPath = currentPath.replace(/\/+$/, '');
        
        console.log('Checking link:', normalizedLink, 'against:', normalizedCurrent);
        
        // Cek kecocokan eksak
        if (normalizedCurrent === normalizedLink || normalizedCurrentPath === normalizedLink) {
          if (!activeFound) {
            $link.addClass('active');
            $link.closest('.nav-item').addClass('menu-open');
            activeFound = true;
            console.log('Exact match found:', normalizedLink);
          }
          return false; // break
        }
      });
      
      // Jika tidak ditemukan kecocokan eksak, cek kecocokan parsial
      if (!activeFound) {
        $('.nav-sidebar .nav-link').each(function() {
          var $link = $(this);
          var linkUrl = $link.attr('href');
          
          if (!linkUrl || linkUrl === '#' || linkUrl === '/') {
            return true; // continue
          }
          
          var normalizedLink = linkUrl.replace(/\/+$/, '');
          var normalizedCurrent = currentUrl.replace(/\/+$/, '');
          
          // Cek jika current URL mengandung link URL (untuk nested routes)
          if (normalizedCurrent.indexOf(normalizedLink) !== -1 && normalizedLink.length > 1) {
            if (!activeFound) {
              $link.addClass('active');
              $link.closest('.nav-item').addClass('menu-open');
              activeFound = true;
              console.log('Partial match found:', normalizedLink);
            }
            return false; // break
          }
        });
      }
      
      // Fallback: aktifkan dashboard jika tidak ada yang cocok dan di root
      if (!activeFound && (currentPath === '/' || currentPath === '/pengelolakesehatan' || currentPath === '/pengelolakesehatan/dashboard')) {
        $('.nav-sidebar .nav-link[href*="dashboard"]').first().addClass('active');
        console.log('Fallback to dashboard');
      }
    }
    
    // Panggil fungsi saat dokumen siap
    setActiveMenu();
    
    // Tambahkan event handler untuk klik manual
    $('.nav-sidebar .nav-link').on('click', function(e) {
      // Skip untuk link logout atau link khusus lainnya
      if ($(this).attr('onclick') && $(this).attr('onclick').includes('logout')) {
        return true;
      }
      
      var $clickedLink = $(this);
      var linkUrl = $clickedLink.attr('href');
      
      // Skip jika link tidak valid
      if (!linkUrl || linkUrl === '#' || linkUrl.startsWith('javascript:')) {
        return true;
      }
      
      console.log('Menu clicked:', linkUrl);
      
      // Hapus semua kelas aktif
      $('.nav-sidebar .nav-link').removeClass('active');
      $('.nav-item').removeClass('menu-open');
      
      // Set aktif pada link yang diklik
      $clickedLink.addClass('active');
      $clickedLink.closest('.nav-item').addClass('menu-open');
      
      // Simpan status aktif di sessionStorage untuk konsistensi
      if (linkUrl) {
        sessionStorage.setItem('activeSidebarMenu', linkUrl);
      }
    });
    
    // Cek sessionStorage untuk status aktif yang disimpan
    var savedActiveMenu = sessionStorage.getItem('activeSidebarMenu');
    if (savedActiveMenu) {
      var $savedLink = $('.nav-sidebar .nav-link[href="' + savedActiveMenu + '"]');
      if ($savedLink.length > 0) {
        $('.nav-sidebar .nav-link').removeClass('active');
        $('.nav-item').removeClass('menu-open');
        $savedLink.addClass('active');
        $savedLink.closest('.nav-item').addClass('menu-open');
      }
    }
  });
</script>
</body>
</html>


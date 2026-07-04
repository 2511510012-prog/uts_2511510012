<?php
// 1. Proteksi session di baris paling atas
session_start();
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}
include "config/koneksi.php";

// ------------------------------------------------------------------
// PAGE ROUTER (Pertemuan 11) - meniru pola index.php?page=xxx di materi
// Whitelist supaya orang tidak bisa asal include file lain lewat URL.
// ------------------------------------------------------------------
$allowed_pages = [
    'admin' => ['jadwal', 'tambah_jadwal', 'cetak_jadwal'],
    'guru'  => ['jadwal_guru'],
    'siswa' => ['jadwal_siswa'],
];

$page = isset($_GET['page']) ? $_GET['page'] : '';
$role = $_SESSION['role'];
$page_valid = ($page !== '' && isset($allowed_pages[$role]) && in_array($page, $allowed_pages[$role]));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Starter Page</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Home</a>
      </li>
    </ul>
  </nav>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION['username']; ?></a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <?php if ($_SESSION['role'] == 'admin') { ?>
            <li class="nav-item">
              <a href="index.php" class="nav-link <?= $page === '' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard Admin</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>Master Data <i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Data Guru</p></a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Data Mapel</p></a></li>
              </ul>
            </li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Data Siswa</p></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon fas fa-school"></i><p>Data Kelas</p></a></li>

            <li class="nav-header">TRANSAKSI</li>
            <li class="nav-item">
              <a href="index.php?page=jadwal" class="nav-link <?= $page === 'jadwal' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>Data Jadwal</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?page=cetak_jadwal" class="nav-link <?= $page === 'cetak_jadwal' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-print"></i>
                <p>Cetak Jadwal</p>
              </a>
            </li>

          <?php } else if ($_SESSION['role'] == 'guru') { ?>
            <li class="nav-item">
              <a href="index.php" class="nav-link <?= $page === '' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-user-tie"></i>
                <p>Profil Guru</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?page=jadwal_guru" class="nav-link <?= $page === 'jadwal_guru' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>Jadwal Mengajar</p>
              </a>
            </li>

          <?php } else if ($_SESSION['role'] == 'siswa') { ?>
            <li class="nav-item">
              <a href="index.php" class="nav-link <?= $page === '' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-user-graduate"></i>
                <p>Profil Siswa</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?page=jadwal_siswa" class="nav-link <?= $page === 'jadwal_siswa' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>Jadwal Pelajaran</p>
              </a>
            </li>
          <?php } ?>

          <li class="nav-header">AKSI</li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link bg-danger">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>

        </ul>
      </nav>

      </div>
    </aside>

  <div class="content-wrapper">
    <?php if (!$page_valid) { ?>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div>
          </div>
        </div>
      </div>
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <div class="card card-primary card-outline">
                <div class="card-body">
                  <h5 class="card-title">Selamat Datang</h5>
                  <p class="card-text">
                    Selamat Datang di Sistem Jadwal Guru pada SMA/SMK XYZ. Anda login sebagai <strong><?php echo $_SESSION['username']; ?></strong>.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } else {
        // Halaman sudah lolos whitelist sesuai role -> aman untuk di-include
        include "pages/" . $page . ".php";
    } ?>
  </div>

  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>

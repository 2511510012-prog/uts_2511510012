<?php
session_start();
include "config/koneksi.php";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        // Tambahan (Pertemuan 11): simpan kd_guru / kelas siswa ke session
        // supaya halaman jadwal_guru.php dan jadwal_siswa.php bisa
        // langsung memfilter tanpa query ulang berkali-kali.
        if ($_SESSION['role'] == 'guru') {
            $qg = mysqli_query($koneksi, "SELECT * FROM guru WHERE username='$username'");
            if ($g = mysqli_fetch_assoc($qg)) {
                $_SESSION['kd_guru'] = $g['kd_guru'];
                $_SESSION['nm_guru'] = $g['nm_guru'];
            }
        } else if ($_SESSION['role'] == 'siswa') {
            $qs = mysqli_query($koneksi, "SELECT * FROM siswa WHERE username='$username'");
            if ($s = mysqli_fetch_assoc($qs)) {
                $_SESSION['kd_siswa'] = $s['kd_siswa'];
                $_SESSION['nm_siswa'] = $s['nm_siswa'];
                $_SESSION['kelas'] = $s['kelas'];
            }
        }

        header("location:index.php");
        exit();
    } else {
        echo "<script>alert('Username atau Password Salah!'); window.location='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Sistem</b>Jadwal</a>
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Silakan masuk menggunakan akun Anda</p>

      <form action="login.php" method="post">
        <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">Remember Me</label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>

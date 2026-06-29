<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "jadwalguru"; // Ubah menjadi "jadwal" jika nama database Anda jadwal

$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (mysqli_connect_error()) {
    echo "gagal melakukan koneksi ke database: " . mysqli_connect_error();
}
?>
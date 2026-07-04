<?php
session_start();
session_destroy(); // Menghapus seluruh session aktif
header("location:login.php"); // Mengalihkan kembali ke form login
exit();
?>
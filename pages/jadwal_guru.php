<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Jadwal Mengajar Saya</h1>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">

        <?php
        // kd_guru diambil dari session yang diisi saat login.php,
        // bukan dari input user, jadi guru A tidak bisa melihat jadwal guru B.
        if (!isset($_SESSION['kd_guru'])) {
            echo "<div class='alert alert-warning'>Akun Anda belum terhubung ke data guru. Hubungi admin.</div>";
        } else {
            $kd_guru = mysqli_real_escape_string($koneksi, $_SESSION['kd_guru']);
            echo "<p>Guru: <strong>{$_SESSION['nm_guru']}</strong></p>";
        ?>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Semester</th>
              <th>Tahun Ajaran</th>
              <th>Hari</th>
              <th>Jam</th>
              <th>Kelas</th>
              <th>Mata Pelajaran</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $urutHari = "FIELD(d.Hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')";
            $query = mysqli_query($koneksi, "
                SELECT j.semester, j.tahun_ajaran, d.Hari, d.Jam, d.Kelas, m.nm_mapel
                FROM jadwal j
                JOIN detailjadwal d ON j.kd_jadwal = d.kd_jadwal
                JOIN mapel m ON d.kd_mapel = m.kd_mapel
                WHERE j.kd_guru = '$kd_guru'
                ORDER BY $urutHari, d.Jam
            ");
            if (mysqli_num_rows($query) == 0) {
              echo "<tr><td colspan='6' class='text-center'>Belum ada jadwal mengajar</td></tr>";
            }
            while ($row = mysqli_fetch_assoc($query)) {
              echo "<tr>
                <td>{$row['semester']}</td>
                <td>{$row['tahun_ajaran']}</td>
                <td>{$row['Hari']}</td>
                <td>{$row['Jam']}</td>
                <td>{$row['Kelas']}</td>
                <td>{$row['nm_mapel']}</td>
              </tr>";
            }
            ?>
          </tbody>
        </table>
        <?php } ?>

      </div>
    </div>
  </div>
</div>

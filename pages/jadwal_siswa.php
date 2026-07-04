<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Jadwal Pelajaran Kelas Saya</h1>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">

        <?php
        // kelas diambil dari session (diisi saat login dari tabel siswa),
        // bukan dari input, jadi siswa hanya bisa melihat kelasnya sendiri.
        if (!isset($_SESSION['kelas'])) {
            echo "<div class='alert alert-warning'>Akun Anda belum terhubung ke data siswa. Hubungi admin.</div>";
        } else {
            $kelas = mysqli_real_escape_string($koneksi, $_SESSION['kelas']);
            echo "<p>Nama: <strong>{$_SESSION['nm_siswa']}</strong> &nbsp; | &nbsp; Kelas: <strong>{$kelas}</strong></p>";
        ?>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Hari</th>
              <th>Jam</th>
              <th>Mata Pelajaran</th>
              <th>Guru</th>
              <th>Semester</th>
              <th>Tahun Ajaran</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $urutHari = "FIELD(d.Hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')";
            $query = mysqli_query($koneksi, "
                SELECT d.Hari, d.Jam, m.nm_mapel, g.nm_guru, j.semester, j.tahun_ajaran
                FROM detailjadwal d
                JOIN jadwal j ON d.kd_jadwal = j.kd_jadwal
                JOIN guru g ON j.kd_guru = g.kd_guru
                JOIN mapel m ON d.kd_mapel = m.kd_mapel
                WHERE d.Kelas = '$kelas'
                ORDER BY $urutHari, d.Jam
            ");
            if (mysqli_num_rows($query) == 0) {
              echo "<tr><td colspan='6' class='text-center'>Belum ada jadwal untuk kelas ini</td></tr>";
            }
            while ($row = mysqli_fetch_assoc($query)) {
              echo "<tr>
                <td>{$row['Hari']}</td>
                <td>{$row['Jam']}</td>
                <td>{$row['nm_mapel']}</td>
                <td>{$row['nm_guru']}</td>
                <td>{$row['semester']}</td>
                <td>{$row['tahun_ajaran']}</td>
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

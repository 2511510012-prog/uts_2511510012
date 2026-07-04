<style>
  /* Saat tombol "Cetak" ditekan (window.print), sembunyikan navbar, sidebar,
     footer, dan tombol itu sendiri -- yang tercetak hanya tabel jadwal. */
  @media print {
    .main-sidebar, .main-header, .main-footer, .content-header, .no-print {
      display: none !important;
    }
    .content-wrapper {
      margin-left: 0 !important;
    }
  }
</style>

<div class="content-header no-print">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Cetak Jadwal Kelas</h1>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">

        <button class="btn btn-primary btn-sm no-print" onclick="window.print()">
          <i class="fas fa-print"></i> Cetak
        </button>
        <a href="index.php?page=jadwal" class="btn btn-secondary btn-sm no-print">Kembali</a>

        <hr class="no-print">

        <h4 class="text-center">JADWAL PELAJARAN</h4>
        <p class="text-center">SMA/SMK XYZ</p>

        <table class="table table-bordered table-sm mt-3">
          <thead>
            <tr class="text-center">
              <th>Hari</th>
              <th>Jam</th>
              <th>Kelas</th>
              <th>Mata Pelajaran</th>
              <th>Guru</th>
              <th>Semester</th>
              <th>Tahun Ajaran</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Urut berdasarkan hari supaya enak dibaca saat dicetak
            $urutHari = "FIELD(d.Hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')";
            $query = mysqli_query($koneksi, "
                SELECT d.Hari, d.Jam, d.Kelas, m.nm_mapel, g.nm_guru, j.semester, j.tahun_ajaran
                FROM detailjadwal d
                JOIN jadwal j ON d.kd_jadwal = j.kd_jadwal
                JOIN guru g ON j.kd_guru = g.kd_guru
                JOIN mapel m ON d.kd_mapel = m.kd_mapel
                ORDER BY $urutHari, d.Jam
            ");
            if (mysqli_num_rows($query) == 0) {
              echo "<tr><td colspan='7' class='text-center'>Belum ada data jadwal</td></tr>";
            }
            while ($row = mysqli_fetch_assoc($query)) {
              echo "<tr>
                <td>{$row['Hari']}</td>
                <td>{$row['Jam']}</td>
                <td>{$row['Kelas']}</td>
                <td>{$row['nm_mapel']}</td>
                <td>{$row['nm_guru']}</td>
                <td>{$row['semester']}</td>
                <td>{$row['tahun_ajaran']}</td>
              </tr>";
            }
            ?>
          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>

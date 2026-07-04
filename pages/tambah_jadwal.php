<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Tambah Jadwal</h1>
      </div>
    </div>
  </div>
</div>

<?php
// Generate kode jadwal otomatis: J-001, J-002, dst.
$carikode = mysqli_query($koneksi, "SELECT MAX(kd_jadwal) FROM jadwal") or die(mysqli_error($koneksi));
$datakode = mysqli_fetch_array($carikode);

if ($datakode && $datakode[0] !== null) {
    $nilaikode = substr($datakode[0], 2);
    $kode = (int) $nilaikode;
    $kode = $kode + 1;
    $hasilkode = "J-" . str_pad($kode, 3, "0", STR_PAD_LEFT);
} else {
    $hasilkode = "J-001";
}

if (isset($_POST['tambah'])) {
    $kd_jadwal    = mysqli_real_escape_string($koneksi, $_POST['kd_jadwal']);
    $kd_guru      = mysqli_real_escape_string($koneksi, $_POST['kd_guru']);
    $semester     = mysqli_real_escape_string($koneksi, $_POST['semester']);
    $tahun_ajaran = mysqli_real_escape_string($koneksi, $_POST['tahun_ajaran']);

    $kd_mapel = $_POST['kd_mapel'];
    $hari     = $_POST['hari'];
    $jam      = $_POST['jam'];
    $kelas    = $_POST['kelas'];

    // Insert ke tabel jadwal
    $insertjadwal = mysqli_query($koneksi, "INSERT INTO jadwal VALUES ('$kd_jadwal', '$kd_guru', '$semester', '$tahun_ajaran')");

    if (!$insertjadwal) {
        echo "Gagal insert ke tabel jadwal: " . mysqli_error($koneksi);
        die;
    }

    // Insert ke detailjadwal (bisa lebih dari satu baris/mapel)
    $allSuccess = true;
    for ($i = 0; $i < count($kd_mapel); $i++) {
        $m = mysqli_real_escape_string($koneksi, $kd_mapel[$i]);
        $h = mysqli_real_escape_string($koneksi, $hari[$i]);
        $j = mysqli_real_escape_string($koneksi, $jam[$i]);
        $k = mysqli_real_escape_string($koneksi, $kelas[$i]);

        $insert = mysqli_query($koneksi, "INSERT INTO detailjadwal (kd_jadwal, kd_mapel, Hari, Jam, Kelas)
            VALUES ('$kd_jadwal', '$m', '$h', '$j', '$k')");
        if (!$insert) {
            $allSuccess = false;
            echo "Gagal insert detail ke-{$i}: " . mysqli_error($koneksi);
        }
    }

    if ($allSuccess) {
        echo '<div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
        <h5><i class="icon fas fa-info"></i> Info </h5>
        <h4>Berhasil Disimpan</h4></div>';
        echo '<meta http-equiv="refresh" content="1;url=index.php?page=jadwal">';
    } else {
        echo '<div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
        <h5><i class="icon fas fa-info"></i> Info </h5>
        <h4>Gagal menyimpan sebagian atau seluruh data detail.</h4></div>';
    }
}
?>
<div class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">

        <form method="POST" action="index.php?page=tambah_jadwal">
          <div class="form-group">
            <label>Kode Jadwal</label>
            <input type="text" name="kd_jadwal" value="<?= $hasilkode ?>" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Guru</label>
            <select name="kd_guru" class="form-control" required>
              <option selected disabled>--Pilih Guru--</option>
              <?php
              $guru = mysqli_query($koneksi, "SELECT * FROM guru");
              while ($g = mysqli_fetch_assoc($guru)) {
                echo "<option value='{$g['kd_guru']}'>{$g['nm_guru']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label>Semester</label>
            <select name="semester" class="form-control" required>
              <option selected disabled>--Pilih semester--</option>
              <option>Ganjil</option>
              <option>Genap</option>
            </select>
          </div>
          <div class="form-group">
            <label>Tahun Ajaran</label>
            <select name="tahun_ajaran" class="form-control" required>
              <option selected disabled>--Pilih Tahun Ajaran--</option>
              <option>2024-2025</option>
              <option>2025-2026</option>
            </select>
          </div>

          <hr>
          <h5>Detail Jadwal</h5>
          <div id="detail-jadwal">
            <div class="row mb-2">
              <div class="col-md-3">
                <select name="kd_mapel[]" class="form-control">
                  <option selected disabled>--Pilih Mapel--</option>
                  <?php
                  $mapel = mysqli_query($koneksi, "SELECT * FROM mapel");
                  while ($m = mysqli_fetch_assoc($mapel)) {
                    echo "<option value='{$m['kd_mapel']}'>{$m['nm_mapel']}</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="col-md-3">
                <select name="hari[]" class="form-control" required>
                  <option selected disabled>--Pilih Hari--</option>
                  <option>Senin</option>
                  <option>Selasa</option>
                  <option>Rabu</option>
                  <option>Kamis</option>
                  <option>Jumat</option>
                  <option>Sabtu</option>
                </select>
              </div>
              <div class="col-md-3">
                <select name="jam[]" class="form-control" required>
                  <option selected disabled>--Pilih Jam--</option>
                  <option>08.00-10.00</option>
                  <option>08.00-09.30</option>
                  <option>10.30-12.00</option>
                  <option>12.30-14.00</option>
                </select>
              </div>
              <div class="col-md-3">
                <input type="text" name="kelas[]" class="form-control" placeholder="Kelas" required>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-info" onclick="tambahBaris()">+ Tambah Mapel</button>
          <br><br>

          <input type="submit" class="btn btn-primary" name="tambah" value="simpan">
        </form>

      </div>
    </div>
  </div>
</div>

<script>
function tambahBaris() {
  let container = document.getElementById('detail-jadwal');
  let row = container.firstElementChild.cloneNode(true);
  row.querySelectorAll('select').forEach(sel => sel.selectedIndex = 0);
  row.querySelectorAll('input').forEach(input => input.value = '');
  container.appendChild(row);
}
</script>

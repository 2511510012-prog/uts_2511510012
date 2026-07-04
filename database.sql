-- ============================================================
-- SCRIPT INI AMAN DIJALANKAN BERULANG KALI
-- Semua tabel pakai IF NOT EXISTS -> tabel yang sudah ada TIDAK akan
-- terhapus atau tertimpa. Hanya tabel yang belum ada yang dibuat.
-- Jalankan lewat phpMyAdmin (tab SQL) pada database "jadwalguru".
-- ============================================================

-- Tabel login (sudah ada di project kamu, dibuatkan jaga-jaga jika belum ada)
CREATE TABLE IF NOT EXISTS admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(50) NOT NULL,
  role ENUM('admin','guru','siswa') NOT NULL
);

-- Data guru. username menghubungkan ke tabel admin (untuk tahu guru mana yang sedang login)
CREATE TABLE IF NOT EXISTS guru (
  kd_guru VARCHAR(10) PRIMARY KEY,
  nm_guru VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL,
  CONSTRAINT fk_guru_username FOREIGN KEY (username) REFERENCES admin(username)
);

-- Data siswa. kelas dipakai untuk memfilter jadwal kelasnya sendiri
CREATE TABLE IF NOT EXISTS siswa (
  kd_siswa VARCHAR(10) PRIMARY KEY,
  nm_siswa VARCHAR(100) NOT NULL,
  kelas VARCHAR(20) NOT NULL,
  username VARCHAR(50) NOT NULL,
  CONSTRAINT fk_siswa_username FOREIGN KEY (username) REFERENCES admin(username)
);

-- Data mata pelajaran
CREATE TABLE IF NOT EXISTS mapel (
  kd_mapel VARCHAR(10) PRIMARY KEY,
  nm_mapel VARCHAR(100) NOT NULL
);

-- Jadwal (header): satu jadwal dibuat untuk satu guru per semester/tahun ajaran
CREATE TABLE IF NOT EXISTS jadwal (
  kd_jadwal VARCHAR(10) PRIMARY KEY,
  kd_guru VARCHAR(10) NOT NULL,
  semester VARCHAR(20) NOT NULL,
  tahun_ajaran VARCHAR(20) NOT NULL,
  CONSTRAINT fk_jadwal_guru FOREIGN KEY (kd_guru) REFERENCES guru(kd_guru)
);

-- Detail jadwal: baris per mapel/hari/jam/kelas dalam satu jadwal
CREATE TABLE IF NOT EXISTS detailjadwal (
  kd_detail INT AUTO_INCREMENT PRIMARY KEY,
  kd_jadwal VARCHAR(10) NOT NULL,
  kd_mapel VARCHAR(10) NOT NULL,
  Hari VARCHAR(20) NOT NULL,
  Jam VARCHAR(20) NOT NULL,
  Kelas VARCHAR(20) NOT NULL,
  CONSTRAINT fk_detail_jadwal FOREIGN KEY (kd_jadwal) REFERENCES jadwal(kd_jadwal),
  CONSTRAINT fk_detail_mapel FOREIGN KEY (kd_mapel) REFERENCES mapel(kd_mapel)
);

-- ============================================================
-- DATA CONTOH (aman: INSERT IGNORE, tidak akan duplikat kalau dijalankan ulang)
-- Silakan hapus/ubah sesuai kebutuhan. Password contoh: plain text
-- sesuai pola login.php kamu saat ini (belum hashing).
-- ============================================================

INSERT IGNORE INTO admin (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('budi', 'guru123', 'guru'),
('siti', 'siswa123', 'siswa');

INSERT IGNORE INTO guru (kd_guru, nm_guru, username) VALUES
('G-001', 'Budi Santoso', 'budi');

INSERT IGNORE INTO siswa (kd_siswa, nm_siswa, kelas, username) VALUES
('S-001', 'Siti Aminah', 'X IPA 1', 'siti');

INSERT IGNORE INTO mapel (kd_mapel, nm_mapel) VALUES
('M-001', 'Matematika'),
('M-002', 'Bahasa Indonesia');

INSERT IGNORE INTO jadwal (kd_jadwal, kd_guru, semester, tahun_ajaran) VALUES
('J-001', 'G-001', 'Ganjil', '2025-2026');

INSERT IGNORE INTO detailjadwal (kd_jadwal, kd_mapel, Hari, Jam, Kelas) VALUES
('J-001', 'M-001', 'Senin', '08.00-10.00', 'X IPA 1'),
('J-001', 'M-002', 'Selasa', '10.30-12.00', 'X IPA 1');

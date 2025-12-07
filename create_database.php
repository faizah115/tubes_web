<?php require "session_check.php"; ?>



<?php
$server = "localhost";
$user   = "root";
$pass   = "";

// 1. Connect ke MySQL tanpa database
$conn = mysqli_connect($server, $user, $pass);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. BUAT DATABASE BARU
$dbname = "bukupedia";
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";

if (mysqli_query($conn, $sql)) {
    echo "Database 'bukupedia' berhasil dibuat atau sudah ada.<br>";
} else {
    die("Gagal membuat database: " . mysqli_error($conn));
}

mysqli_select_db($conn, $dbname);

// 3. TABEL BUKU
$sql_buku = "
CREATE TABLE IF NOT EXISTS buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    penulis VARCHAR(255),
    genre VARCHAR(255),
    gambar VARCHAR(255),
    halaman_detail VARCHAR(255),
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql_buku);

// 4. TABEL REVIEW
$sql_review = "
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buku_id INT NOT NULL,
    nama VARCHAR(255) NOT NULL,
    komentar TEXT NOT NULL,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE
)";
mysqli_query($conn, $sql_review);

// 5. INSERT DATA BUKU AWAL
$sql_insert = "
INSERT INTO buku (judul, penulis, genre, gambar, halaman_detail, deskripsi) VALUES
('Filosofi Teras', 'Henry Manampiring', 'Self Improvement', 'filosofi_teras.jpeg', 'detail.php?id=1', 'Buku Stoikisme modern yang mudah dipahami.'),
('Laut Bercerita', 'Leila S. Chudori', 'Fiksi', 'laut_bercerita.jpeg', 'detail.php?id=2', 'Novel tragedi penghilangan aktivis 1998.'),
('Alpha Girls', 'Julian Guthrie', 'Biografi', 'alpa_girls.jpeg', 'detail.php?id=3', 'Perjalanan perempuan berpengaruh di dunia teknologi.'),
('Atomic Habits', 'James Clear', 'Self Improvement', 'atomic_habits.jpeg', 'detail.php?id=4', 'Kebiasaan kecil untuk perubahan besar.');
";

mysqli_query($conn, $sql_insert);

echo "<br>Semua tabel sudah dibuat dan data awal sudah dimasukkan.";

mysqli_close($conn);
?>

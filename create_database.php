<?php
/* -------------------------------------------------------
   IMPORT: koneksi database utama & pengecekan session login
-------------------------------------------------------- */
require "koneksi.php";       // koneksi InfinityFree
require "session_check.php"; 


/* -------------------------------------------------------
   MENGECEK KONEKSI DARI koneksi.php
-------------------------------------------------------- */
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}


/* -------------------------------------------------------
   MEMBUAT TABEL "buku" HANYA JIKA BELUM ADA
-------------------------------------------------------- */
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
) ENGINE=InnoDB;
";

mysqli_query($conn, $sql_buku);


/* -------------------------------------------------------
   MEMBUAT TABEL "reviews" HANYA JIKA BELUM ADA
-------------------------------------------------------- */
$sql_reviews = "
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buku_id INT NOT NULL,
    nama VARCHAR(255) NOT NULL,
    komentar TEXT NOT NULL,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE
) ENGINE=InnoDB;
";

mysqli_query($conn, $sql_reviews);


/* -------------------------------------------------------
   MENGECEK APAKAH TABEL BUKU SUDAH PUNYA DATA
   Jika sudah ada → TIDAK memasukkan ulang data default
-------------------------------------------------------- */
$cek = mysqli_query($conn, "SELECT COUNT(*) AS total FROM buku");
$row = mysqli_fetch_assoc($cek);

if ($row['total'] == 0) {

    /* -------------------------------------------------------
       MEMASUKKAN DATA DEFAULT (HANYA SEKALI)
    -------------------------------------------------------- */
    $sql_insert = "
    INSERT INTO buku (judul, penulis, genre, gambar, halaman_detail, deskripsi) VALUES
    ('Filosofi Teras', 'Henry Manampiring', 'Self Improvement', 'filosofi_teras.jpeg', 'detail.php?id=1', 'Buku Stoikisme modern yang mudah dipahami.'),
    ('Laut Bercerita', 'Leila S. Chudori', 'Fiksi', 'laut_bercerita.jpeg', 'detail.php?id=2', 'Novel tragedi penghilangan aktivis 1998.'),
    ('Alpha Girls', 'Julian Guthrie', 'Biografi', 'alpa_girls.jpeg', 'detail.php?id=3', 'Perjalanan perempuan berpengaruh di dunia teknologi.'),
    ('Atomic Habits', 'James Clear', 'Self Improvement', 'atomic_habits.jpeg', 'detail.php?id=4', 'Kebiasaan kecil untuk perubahan besar.');
    ";

    mysqli_query($conn, $sql_insert);
}


/* -------------------------------------------------------
   PESAN TAMBAHAN
-------------------------------------------------------- */
echo "Database aman. Struktur dicek. Data tidak diduplikasi.";

mysqli_close($conn);
?>

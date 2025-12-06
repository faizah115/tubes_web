<?php 
require "koneksi.php";

// Query insert data buku
$sql = "INSERT INTO buku (judul, gambar, halaman_detail, deskripsi) VALUES
('Filosofi Teras', 'filosofi_teras.jpeg', 'filosofi_teras.php', 'Buku tentang Stoikisme...'),
('Laut Bercerita', 'laut_bercerita.jpeg', 'laut_bercerita.php', 'Novel tragedi 1998...'),
('Alpha Girls', 'alpa_girls.jpeg', 'alpha_girls.php', 'Tentang perempuan inspiratif...'),
('Atomic Habits', 'atomic_habits.jpeg', 'atomic_habits.php', 'Tentang kebiasaan kecil...')";

if (mysqli_query($conn, $sql)) {
    echo "Data buku berhasil dimasukkan!";
} else {
    echo "Gagal: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

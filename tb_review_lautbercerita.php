<?php
require "koneksi.php";

$sql = "CREATE TABLE reviews_lautbercerita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    komentar TEXT NOT NULL,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Tabel reviews_lautbercerita berhasil dibuat!";
} else {
    echo "Gagal membuat tabel: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
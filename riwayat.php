<?php
/* -----------------------------------------
   IMPORT FILE PENTING UNTUK CEK LOGIN & DB
------------------------------------------*/
require "session_check.php";   // memastikan user sudah login
require "koneksi.php";         // koneksi ke database

/* -----------------------------------------
   AMBIL ID USER DARI SESSION
   Digunakan untuk menampilkan review miliknya
------------------------------------------*/
$user_id = $_SESSION["user_id"]; // sudah pasti ada setelah login

/* -----------------------------------------
   QUERY SQL UNTUK MENGAMBIL REVIEW PENGGUNA
   Mengambil komentar + judul buku yang direview
------------------------------------------*/
$sql = "
    SELECT r.*, b.judul 
    FROM reviews r 
    JOIN buku b ON r.buku_id = b.id
    WHERE r.user_id = $user_id
    ORDER BY r.created_at DESC
";

$result = mysqli_query($conn, $sql); // eksekusi query
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Komentar Saya</title>

    <!-- File CSS untuk styling halaman -->
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body>

<!-- Judul Halaman -->
<h2 class="title">Riwayat Komentar Kamu</h2>

<!-- Container untuk menampung semua review -->
<div class="review-container">

    <!-- Looping semua review milik user -->
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    
        <!-- Card untuk satu review -->
        <div class="review-card">

            <!-- Menampilkan judul buku yang direview -->
            <h3><?= $row["judul"] ?></h3>

            <!-- Menampilkan komentar, nl2br agar baris baru muncul -->
            <p><?= nl2br($row["komentar"]) ?></p>

            <!-- Jika review memiliki gambar maka tampilkan -->
            <?php if ($row["gambar"]): ?>
                <img src="uploads/<?= $row["gambar"] ?>" width="150">
            <?php endif; ?>

            <!-- Tombol Edit & Delete -->
            <div class="actions">
                <a href="edit_review.php?id=<?= $row['id'] ?>" class="btn-warning">Edit</a>

                <a href="delete_review.php?id=<?= $row['id'] ?>" 
                   class="btn-danger"
                   onclick="return confirm('Hapus komentar ini?')">
                   Delete
                </a>
            </div>

        </div>

    <?php endwhile; ?>

</div>

</body>
</html>

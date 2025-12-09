<?php
/* ============================================================
   IMPORT FILE: CEK SESSION LOGIN & KONEKSI DATABASE
============================================================ */
require "session_check.php";
require "koneksi.php";

/* ============================================================
   CEK ROLE (ADMIN / USER)
============================================================ */

if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {

    $title = "Semua Riwayat Komentar";

    $sql = "
        SELECT reviews.*, buku.judul, users.username 
        FROM reviews
        LEFT JOIN buku ON reviews.buku_id = buku.id
        LEFT JOIN users ON users.id = reviews.user_id
        ORDER BY reviews.id DESC
    ";
    $result = mysqli_query($conn, $sql);

} else {

    $username = $_SESSION["username"]; 

    $user_q = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' LIMIT 1");
    $user = mysqli_fetch_assoc($user_q);

    if (!$user) {
        die("User tidak ditemukan!");
    }

    $user_id = $user["id"];
    $title = "Riwayat Komentar Anda"; 

    $sql = "
        SELECT reviews.*, buku.judul
        FROM reviews
        LEFT JOIN buku ON reviews.buku_id = buku.id
        WHERE reviews.user_id = $user_id
        ORDER BY reviews.id DESC
    ";
    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="CSS/riwayat.css">
</head>
<body>

<!-- ============================================================
     NAVBAR (Sudah diperbaiki)
============================================================ -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container py-2">

        <a class="navbar-brand fw-bold fs-4" href="index.php">Buku Pedia</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto gap-3">
                
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Beranda</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="riwayat_halaman.php">Riwayat Komentar</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php#daftar-buku">Daftar Buku</a>
                </li>

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger"
                       onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                       Keluar
                    </a>
                </li>

            </ul>
        </div>

    </div>
</nav>


<!-- ============================================================
     KONTEN HALAMAN RIWAYAT
============================================================ -->
<div class="riwayat-container">
    <h2 class="title"><?= $title ?></h2>

    <table class="riwayat-table">
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>Nama User</th>
                <th>Foto</th>
                <th>Komentar</th>
                <th>Dikirim</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php while ($r = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $r['judul'] ?></td>
                <td><?= $r["username"] ?? "-" ?></td>

                <td>
                    <?php if (!empty($r["gambar"])): ?>
                        <img src="uploads/<?= $r['gambar'] ?>" class="img-mini">
                    <?php else: ?>
                        <span class="no-img">-</span>
                    <?php endif; ?>
                </td>

                <td><?= nl2br($r['komentar']) ?></td>
                
                <td class="date-col">
                    <?= $r['created_at'] ?? '-' ?>
                </td>

                <td class="action-col">
                    <a href="edit_review.php?id=<?= $r['id'] ?>" class="btn-edit">Edit</a>
<a href="delete_review.php?id=<?= $r['id'] ?>&from=riwayat" 
   class="btn-delete"
   onclick="return confirm('Hapus komentar ini?')">
   Delete
</a>


                </td>

            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

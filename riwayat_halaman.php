<?php
/* ============================================================
   IMPORT FILE: CEK SESSION LOGIN & KONEKSI DATABASE
   Pastikan pengguna sudah login dan database tersedia
============================================================ */
require "session_check.php";
require "koneksi.php";

/* ============================================================
   CEK ROLE (ADMIN / USER)
   Admin dapat melihat SEMUA review
   User hanya melihat review miliknya sendiri
============================================================ */

// Jika yang login adalah ADMIN → tampilkan seluruh review dari semua user
if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {

    $title = "Semua Riwayat Komentar"; // judul halaman untuk admin

    // Query untuk mengambil semua review + nama user + judul buku
    $sql = "
        SELECT reviews.*, buku.judul, users.username 
        FROM reviews
        LEFT JOIN buku ON reviews.buku_id = buku.id
        LEFT JOIN users ON users.id = reviews.user_id
        ORDER BY reviews.id DESC
    ";
    $result = mysqli_query($conn, $sql);

} else {
    /* ----------------------------------------------------------
       Jika USER biasa login → tampilkan hanya review miliknya
    -----------------------------------------------------------*/

    $username = $_SESSION["username"]; // ambil username dari session

    // Ambil ID user berdasarkan username
    $user_q = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' LIMIT 1");
    $user = mysqli_fetch_assoc($user_q);

    if (!$user) {
        die("User tidak ditemukan!");
    }

    $user_id = $user["id"];
    $title = "Riwayat Komentar Anda"; // judul halaman untuk user

    // Query: ambil hanya review yang dimiliki user tersebut
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

    <!-- Judul halaman menyesuaikan apakah admin atau user -->
    <title><?= $title ?></title>

    <!-- File CSS untuk styling riwayat -->
    <link rel="stylesheet" href="CSS/riwayat.css">
</head>

<body>

<!-- Container utama halaman riwayat komentar -->
<div class="riwayat-container">

    <!-- Judul halaman -->
    <h2 class="title"><?= $title ?></h2>

    <!-- Tabel berisi daftar komentar / review -->
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

        <!-- Loop semua data review yang diambil dari database -->
        <?php while ($r = mysqli_fetch_assoc($result)): ?>
            <tr>

                <!-- Judul buku yang direview -->
                <td><?= $r['judul'] ?></td>

                <!-- Nama user (admin bisa lihat semua, user biasa ditampilkan '-') -->
                <td><?= $r["username"] ?? "-" ?></td>

                <!-- Foto yang diupload user (jika ada) -->
                <td>
                    <?php if (!empty($r["gambar"])): ?>
                        <img src="uploads/<?= $r['gambar'] ?>" class="img-mini">
                    <?php else: ?>
                        <span class="no-img">-</span>
                    <?php endif; ?>
                </td>

                <!-- Komentar user -->
                <td><?= nl2br($r['komentar']) ?></td>

                <!-- Tanggal komentar dikirim -->
                <td class="date-col">
                    <?= $r['created_at'] ?? '-' ?>
                </td>

                <!-- Tombol Edit & Delete -->
                <td class="action-col">
                    <a href="edit_review.php?id=<?= $r['id'] ?>" class="btn-edit">Edit</a>
                    <a href="delete_review.php?id=<?= $r['id'] ?>" class="btn-delete"
                       onclick="return confirm('Hapus komentar ini?')">Delete</a>
                </td>

            </tr>
        <?php endwhile; ?>

        </tbody>
    </table>

</div>

</body>
</html>

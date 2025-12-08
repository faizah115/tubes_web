<?php
require "session_check.php";
require "koneksi.php";

$username = $_SESSION["username"];

// Ambil ID user
$user_q = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' LIMIT 1");
$user = mysqli_fetch_assoc($user_q);
$user_id = $user["id"];

// Ambil riwayat komentar user
$sql = "
    SELECT reviews.*, buku.judul 
    FROM reviews
    LEFT JOIN buku ON reviews.buku_id = buku.id
    WHERE reviews.user_id = $user_id
    ORDER BY reviews.id DESC
";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Komentar Anda</title>
    <link rel="stylesheet" href="CSS/riwayat.css">
</head>

<body>

<div class="riwayat-container">

    <h2 class="title">Riwayat Komentar Anda</h2>

    <table class="riwayat-table">
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>Foto</th>
                <th>Komentar</th>
                <th>Dikirim</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

        <?php while ($r = mysqli_fetch_assoc($result)): ?>
            <tr>
                <!-- judul buku -->
                <td><?= $r['judul'] ?></td>

                <!-- foto -->
                <td>
                    <?php if (!empty($r["gambar"])): ?>
                        <img src="uploads/<?= $r['gambar'] ?>" class="img-mini">
                    <?php else: ?>
                        <span class="no-img">-</span>
                    <?php endif; ?>
                </td>

                <!-- komentar -->
                <td><?= nl2br($r['komentar']) ?></td>

                <!-- tanggal -->
                <td class="date-col">
                    <?= $r['created_at'] ?? '-' ?>
                </td>

                <!-- aksi -->
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

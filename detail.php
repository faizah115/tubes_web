<?php
require "koneksi.php";

$id = $_GET['id'];

// Ambil data buku berdasarkan ID
$sql = "SELECT * FROM buku WHERE id = $id";
$result = mysqli_query($conn, $sql);
$b = mysqli_fetch_assoc($result);

if (!$b) {
    die("Buku tidak ditemukan!");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $b['judul']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container py-5">
    <h1><?= $b['judul']; ?></h1>
    <p><strong>Penulis:</strong> <?= $b['penulis']; ?></p>

    <img src="assets/<?= $b['gambar']; ?>" width="300" class="rounded mb-4">

    <p><?= nl2br($b['deskripsi']); ?></p>

    <a href="index.php" class="btn btn-dark mt-3">Kembali</a>
</div>
</body>
</html>

<?php
require "koneksi.php";
require "session_check.php";

if (!isset($_GET["id"])) {
    die("ID buku tidak ditemukan!");
}

$buku_id = intval($_GET["id"]);

// Ambil detail buku
$qBuku = mysqli_query($conn, "SELECT * FROM buku WHERE id = $buku_id");
$buku = mysqli_fetch_assoc($qBuku);

if (!$buku) {
    die("Buku tidak ditemukan!");
}

// ======================================
// PROSES KIRIM REVIEW
// ======================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = mysqli_real_escape_string($conn, $_POST["nama"]);
    $komentar = mysqli_real_escape_string($conn, $_POST["komentar"]);
    $user_id = $_SESSION["user_id"];

    $gambar = "";

    if (!empty($_FILES["foto"]["name"])) {
        $namaFile = time() . "_" . $_FILES["foto"]["name"];
        move_uploaded_file($_FILES["foto"]["tmp_name"], "uploads/" . $namaFile);
        $gambar = mysqli_real_escape_string($conn, $namaFile);
    }

    $sql = "
        INSERT INTO reviews (buku_id, user_id, nama, komentar, gambar)
        VALUES ($buku_id, $user_id, '$nama', '$komentar', '$gambar')
    ";

    mysqli_query($conn, $sql);

    header("Location: detail.php?id=" . $buku_id);
    exit;
}

// ======================================
// AMBIL SEMUA REVIEW
// ======================================
$qReview = mysqli_query($conn, "SELECT * FROM reviews WHERE buku_id = $buku_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $buku["judul"] ?></title>

    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">

<!-- ============================================================
     NAVBAR (VERSI FINAL)
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
     DETAIL BUKU + REVIEW
============================================================ -->
<div class="container py-5">

    <!-- DETAIL BUKU -->
    <div class="row mb-5">
        <div class="col-md-4">
            <img src="assets/<?= $buku['gambar'] ?>" class="img-fluid rounded shadow">
        </div>

        <div class="col-md-8">
            <h1 class="fw-bold"><?= $buku["judul"] ?></h1>

            <?php if (!empty($buku["penulis"])): ?>
                <p class="text-muted">Penulis: <?= $buku["penulis"] ?></p>
            <?php endif; ?>

            <?php if (!empty($buku["genre"])): ?>
                <p><b>Genre:</b> <?= $buku["genre"] ?></p>
            <?php endif; ?>

            <p class="mt-4"><?= nl2br($buku["deskripsi"]) ?></p>
        </div>
    </div>

    <hr>

    <!-- FORM TAMBAH REVIEW -->
    <h3 class="fw-bold mt-5 mb-3">Tambah Review</h3>

    <form method="POST" enctype="multipart/form-data" class="row g-3 bg-white p-4 rounded shadow-sm">

        <div class="col-md-4">
            <label class="form-label">Nama Pengguna</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Upload Gambar (opsional)</label>
            <input type="file" name="foto" class="form-control">
        </div>

        <div class="col-12">
            <label class="form-label">Komentar</label>
            <textarea name="komentar" class="form-control" rows="3" required></textarea>
        </div>

        <div class="col-12">
            <button class="btn btn-dark px-4">Kirim Review</button>
        </div>
    </form>


    <!-- DAFTAR REVIEW -->
    <h3 class="fw-bold mt-5 mb-3">Review Pengguna</h3>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <th>Foto</th>
                <th>Komentar</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

        <?php if (mysqli_num_rows($qReview) === 0): ?>
            <tr>
                <td colspan="4" class="text-center text-muted">Belum ada review</td>
            </tr>
        <?php else: ?>

            <?php while ($r = mysqli_fetch_assoc($qReview)): ?>
            <tr>
                <td><?= $r["nama"] ?></td>

                <td>
                    <?php if ($r["gambar"]): ?>
                        <img src="uploads/<?= $r['gambar'] ?>" width="120" class="rounded">
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </td>

                <td><?= nl2br($r["komentar"]) ?></td>

                <td>
                    <a href="edit_review.php?id=<?= $r['id'] ?>&buku_id=<?= $buku['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
 <a href="delete_review.php?id=<?= $r['id'] ?>&buku_id=<?= $buku['id'] ?>&from=detail"
   class="btn btn-sm btn-danger"
   onclick="return confirm('Hapus review ini?')">
   Delete
</a>

                </td>
            </tr>
            <?php endwhile; ?>

        <?php endif; ?>

        </tbody>
    </table>

</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

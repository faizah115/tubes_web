<?php
require "koneksi.php";
require "session_check.php";



if (!isset($_GET["id"])) {
    die("ID buku tidak ditemukan!");
}

$buku_id = intval($_GET["id"]);

$qBuku = mysqli_query($conn, "SELECT * FROM buku WHERE id = $buku_id");
$buku = mysqli_fetch_assoc($qBuku);

if (!$buku) {
    die("Buku tidak ditemukan!");
}

// ==========================================
// DETEKSI MOBILE DEVICE
// ==========================================
function isMobile() {
    return preg_match('/(android|avantgo|blackberry|iphone|ipad|ipod|opera mini|iemobile|mobile)/i',
    $_SERVER['HTTP_USER_AGENT']);
}

$isMobile = isMobile();
// ==========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = mysqli_real_escape_string($conn, $_POST["nama"]);
    $komentar = mysqli_real_escape_string($conn, $_POST["komentar"]);
    $user_id = $_SESSION["user_id"] ?? 0;

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

$qReview = mysqli_query($conn, "SELECT * FROM reviews WHERE buku_id = $buku_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $buku["judul"] ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- CSS TERPISAH -->
<<link rel="stylesheet" href="CSS/detail.css?v=3">


</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container py-2">

        <a class="navbar-brand fw-bold fs-4" href="index.php">Buku Pedia</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto gap-3">
                <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="riwayat_halaman.php">Riwayat Komentar</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#daftar-buku">Daftar Buku</a></li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger"
                       onclick="return confirm('Apakah Anda yakin ingin keluar?')">Keluar</a>
                </li>
            </ul>
        </div>

    </div>
</nav>

<div class="container py-5">

    <div class="row mb-5">
<div class="col-md-4 book-cover">
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

            <p class="mt-4 text-justify"><?= nl2br($buku["deskripsi"]) ?></p>
        </div>
    </div>

    <hr>

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


    <h3 class="fw-bold mt-5 mb-3">Review Pengguna</h3>

    <!-- ============================================
         DESKTOP: TABEL HANYA MUNCUL JIKA BUKAN MOBILE
    ============================================= -->
    <?php if (!$isMobile): ?>
    <table class="table table-striped review-table">
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

                <td class="text-justify"><?= nl2br($r["komentar"]) ?></td>

                <td>
                    <a href="edit_review.php?id=<?= $r['id'] ?>&buku_id=<?= $buku['id'] ?>" 
                        class="btn btn-sm btn-warning">Edit</a>

                    <a href="delete_review.php?id=<?= $r['id'] ?>&buku_id=<?= $buku['id'] ?>&from=detail"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Hapus review ini?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>

        <?php endif; ?>

        </tbody>
    </table>
    <?php endif; ?>


    <!-- ============================================
         MOBILE VIEW (CARD) HANYA MUNCUL JIKA MOBILE
    ============================================= -->
    <?php if ($isMobile): ?>
    <?php mysqli_data_seek($qReview, 0); ?>

    <?php while ($r = mysqli_fetch_assoc($qReview)): ?>
    <div class="review-card">

        <h6 class="fw-bold"><?= $r["nama"] ?></h6>

        <?php if ($r["gambar"]): ?>
            <img src="uploads/<?= $r['gambar'] ?>" class="review-img mb-2">
        <?php endif; ?>

        <p class="text-justify"><?= nl2br($r["komentar"]) ?></p>

        <div class="review-actions">
            <a href="edit_review.php?id=<?= $r['id'] ?>&buku_id=<?= $buku['id'] ?>"
               class="btn btn-warning btn-sm">Edit</a>

            <a href="delete_review.php?id=<?= $r['id'] ?>&buku_id=<?= $buku['id'] ?>&from=detail"
               onclick="return confirm('Hapus review ini?')"
               class="btn btn-danger btn-sm">Delete</a>
        </div>

    </div>
    <?php endwhile; ?>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

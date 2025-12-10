<?php
require "koneksi.php";
require "session_check.php";    
    
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search != '') {
    $sql = "SELECT * FROM buku WHERE judul LIKE '%$search%' ORDER BY id ASC";
} else {
    $sql = "SELECT * FROM buku ORDER BY id ASC";
}

$result = mysqli_query($conn, $sql);

$books = [];
while ($row = mysqli_fetch_assoc($result)) {
    $books[] = $row;
}

$search = isset($_GET['search']) ? $_GET['search'] : "";

if ($search != "") {
    $sql = "SELECT * FROM buku WHERE judul LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM buku";
}

$query = mysqli_query($conn, $sql);


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Kita | Forum Diskusi Buku </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
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
                    <a class="nav-link" href="#daftar-buku">Daftar Buku</a>
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

<section class="hero-section d-flex align-items-center">
    <img src="assets/index.jpeg" class="hero-bg">

    <div class="container">
        <div class="hero-text-left text-white">
            <h1 class="fw-bold display-3">Forum Review Buku</h1>
            <h1 class="fw-bold display-3">Bagikan pengalaman mu</h1>

            <p class="mt-3 fs-5">
                Temukan buku favoritmu dan bagikan pengalaman membaca mu
            </p>
        </div>
    </div>
</section>

    <section class="text-center py-5">
        <h2 class="fw-bold">Mengapa Bergabung dengan BukuPedia? </h2>
        <p class="text-muted">Dapatkan inspirasi dan wawasan baru dari setiap ulasan di BukuPedia</p>


        <h2 class="fw-bold text-center mb-4">Pilih buku kamu!</h2>
<section id="daftar-buku"></section>

<form method="GET" class="d-flex justify-content-center mb-4">
    <div class="search-box d-flex align-items-center">
        
        

        <input type="text" name="search" class="search-input"
        placeholder="Cari buku..."
        value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">

       <button type="submit" class="search-btn">
    <i class="bi bi-search"></i>
</button>

    </div>
</form>

        <div class="container mt-4">
            <div class="row gy-4">
                <div class="col-md-3">
                    <h5>Fiksi</h5>
                    <p class="text-muted small">Dalami fakta, pengetahuan, sejarah, biografi inspiratif, dan laporan investigatif.</p>
                </div>
                <div class="col-md-3">
                    <h5>Non Fiksi</h5>
                    <p class="text-muted small">Jelajahi kisah imajinatif, petualangan epik, hingga romansa yang memikat hati.</p>
                </div>
                <div class="col-md-3">
                    <h5>Self Improvement</h5>
                    <p class="text-muted small">Temukan panduan pengembangan diri dan kesehatan mental.</p>
                </div>
                <div class="col-md-3">
                    <h5>Filsafat</h5>
                    <p class="text-muted small">Pikirkan makna kehidupan dan eksistensi manusia.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="daftar-buku" class="py-5 bg-light">
        <div class="container">
            <h2 class="fw-bold text-center mb-4">Pilih buku kamu!</h2>

            <div class="row g-4">

                <?php foreach ($books as $b): ?>
<div class="col-md-3">
    <div class="card shadow-sm border-0 h-100 book-card">

        <img src="assets/<?= $b['gambar'] ?>" class="card-img-top">

        <div class="card-body d-flex flex-column">
            <h5 class="card-title book-title"><?= $b['judul'] ?></h5>

            <a href="detail.php?id=<?= $b['id'] ?>" class="btn btn-dark w-100 mt-auto btn-detail">
                Lihat Detail
            </a>
        </div>

    </div>
</div>
                <?php endforeach; ?>

            </div>

        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        © 2025 BukuPedia. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">




</body>
</html>
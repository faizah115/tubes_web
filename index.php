<?php 
require "koneksi.php";

// Ambil data buku dari database
$books = [];
$sql = "SELECT * FROM buku";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $books[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Kita | Forum Diskusi Buku </title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="#">Buku Kita</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto gap-4">
                    <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Riwayat Komentar</a></li>
                    <li class="nav-item"><a class="nav-link" href="#daftar-buku">Daftar Buku</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero position-relative">
        <img src="assets/index.jpeg" class="hero-img">

        <div class="hero-text">
            <h1 class="fw-bold display-4">Forum Diskusi Buku<br>Bagikan pengalaman mu</h1>
            <p>Temukan buku favoritmu dan bagikan pengalaman membaca mu</p>
            <button class="btn btn-light px-4 py-2 rounded-pill fw-semibold">
                Daftar Sekarang
            </button>
        </div>
    </section>

    <!-- BENEFITS -->
    <section class="text-center py-5">
        <h2 class="fw-bold">Mengapa Bergabung dengan BukuKita? </h2>
        <p class="text-muted">Dapatkan inspirasi dan wawasan baru dari setiap ulasan di BukuKita</p>

        <div class="container mt-4">
            <div class="row gy-4">

                <div class="col-md-3">
                    <h5>Fiksi</h5>
                    <p class="text-muted small">
                        Dalami fakta, pengetahuan, sejarah, biografi inspiratif, dan laporan investigatif.
                    </p>
                </div>

                <div class="col-md-3">
                    <h5>Non Fiksi</h5>
                    <p class="text-muted small">
                        Jelajahi kisah imajinatif, petualangan epik, hingga romansa yang memikat hati.
                    </p>
                </div>

                <div class="col-md-3">
                    <h5>Self Improvement</h5>
                    <p class="text-muted small">
                        Temukan panduan pengembangan diri, kesehatan mental, dan produktivitas.
                    </p>
                </div>

                <div class="col-md-3">
                    <h5>Filsafat</h5>
                    <p class="text-muted small">
                        Pikirkan makna kehidupan dan eksistensi melalui buku-buku filosofis.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- DAFTAR BUKU -->
    <section id="daftar-buku" class="py-5 bg-light">
        <div class="container">

            <h2 class="fw-bold text-center mb-4">Pilih buku kamu!</h2>

            <div class="row g-4">

                <?php foreach ($books as $b): ?>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 h-100">
                            <img src="assets/<?= $b['gambar'] ?>" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title"><?= $b['judul'] ?></h5>

                                <a href="<?= $b['halaman_detail'] ?>" class="btn btn-dark w-100 mt-2">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-dark text-white text-center py-3">
        © 2025 BukuKita. All rights reserved.
    </footer>

</body>
</html>
<?php
require "koneksi.php";
$books = mysqli_query($conn, "SELECT * FROM buku");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Kita</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- JS DOM -->
    <script src="js/script.js" defer></script>
</head>

<body>

<div class="container py-5">

    <h2 class="fw-bold mb-4">Daftar Buku</h2>

    <!-- Search Input (JavaScript DOM) -->
    <input type="text" id="searchInput" class="form-control mb-4" placeholder="Cari buku...">

    <div class="row g-4" id="bookList">
    <?php while ($b = mysqli_fetch_assoc($books)): ?>
        <div class="col-md-3 book-card">
            <div class="card shadow-sm border-0 h-100">
                <img src="assets/<?= $b['gambar'] ?>" class="card-img-top">

                <div class="card-body">
                    <h5 class="card-title"><?= $b['judul'] ?></h5>

                    <a href="detail.php?id=<?= $b['id'] ?>" class="btn btn-dark w-100 mt-2">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    </div>

</div>

</body>
</html>

<?php
require "session_check.php";
require "koneksi.php";

// Cek apakah ID ada
if (!isset($_GET["id"])) {
    die("ID buku tidak ditemukan!");
}

$id = intval($_GET["id"]);

// Ambil data detail buku
$query = $conn->prepare("SELECT * FROM buku WHERE id=? LIMIT 1");
$query->bind_param("i", $id);
$query->execute();
$buku = $query->get_result()->fetch_assoc();

if (!$buku) {
    die("Buku tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku | <?= htmlspecialchars($buku["judul"]); ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Responsif tambahan */
        .detail-img {
            width: 100%;
            max-height: 450px;
            object-fit: cover;
            border-radius: 12px;
        }

        .detail-container {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .detail-img {
                max-height: 300px;
            }

            h2 {
                font-size: 22px;
            }

            p {
                font-size: 15px;
            }
        }
    </style>
</head>

<body class="bg-light">

<div class="container my-5">
    <div class="detail-container">

        <div class="row g-4 align-items-start">

            <!-- BAGIAN GAMBAR -->
            <div class="col-md-5">
                <img src="assets/<?= htmlspecialchars($buku['gambar']); ?>" 
                     alt="<?= htmlspecialchars($buku['judul']); ?>" 
                     class="detail-img img-fluid shadow">
            </div>

            <!-- BAGIAN TEKS -->
            <div class="col-md-7">
                <h2 class="fw-bold"><?= htmlspecialchars($buku["judul"]); ?></h2>
                <p class="text-muted mb-2">
                    <strong>Penulis:</strong> <?= htmlspecialchars($buku["penulis"]); ?>
                </p>

                <p class="mb-4"><?= nl2br(htmlspecialchars($buku["deskripsi"])); ?></p>

                <!-- Tombol aksi -->
                <div class="d-flex flex-wrap gap-2 mt-4">

                    <!-- Tombol kembali -->
                    <a href="index.php" class="btn btn-secondary px-4">Kembali</a>

                    <!-- Tambah Review -->
                    <a href="review_form.php?id=<?= $buku["id"]; ?>" class="btn btn-primary px-4">
                        Tambah Review
                    </a>

                    <!-- Lihat Review -->
                    <a href="riwayat_halaman.php?id=<?= $buku["id"]; ?>" 
                       class="btn btn-dark px-4">
                        Lihat Review Buku Ini
                    </a>

                </div>
            </div>
        </div>

    </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

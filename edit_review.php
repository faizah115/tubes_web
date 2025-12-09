<?php
/* ----------------------------------------------------------
   IMPORT: koneksi database & pengecekan session login
---------------------------------------------------------- */
require "koneksi.php";
require "session_check.php";

/* ----------------------------------------------------------
   CEK APAKAH ID REVIEW DIKIRIM MELALUI URL
---------------------------------------------------------- */
if (!isset($_GET["id"])) {
    die("ID review tidak ada!");
}

$id = intval($_GET["id"]); 


/* ----------------------------------------------------------
   AMBIL DATA REVIEW BERDASARKAN ID
---------------------------------------------------------- */
$q = $conn->prepare("SELECT * FROM reviews WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$data = $q->get_result()->fetch_assoc();

if (!$data) {
    die("Review tidak ditemukan!");
}


/* ----------------------------------------------------------
   Ambil buku_id untuk redirect
---------------------------------------------------------- */
$buku_id = isset($_GET["buku_id"]) ? intval($_GET["buku_id"]) : $data["buku_id"];


// =================================================================
//                            UPDATE REVIEW
// =================================================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama     = $_POST["nama"];
    $komentar = $_POST["komentar"];
    $gambar   = $data["gambar"];  

    if (!empty($_FILES["foto"]["name"])) {

        if ($gambar && file_exists("uploads/" . $gambar)) {
            unlink("uploads/" . $gambar); 
        }

        $newName = uniqid() . "_" . $_FILES["foto"]["name"];
        move_uploaded_file($_FILES["foto"]["tmp_name"], "uploads/" . $newName);

        $gambar = $newName;
    }

    $update = $conn->prepare("UPDATE reviews SET nama=?, komentar=?, gambar=? WHERE id=?");
    $update->bind_param("sssi", $nama, $komentar, $gambar, $id);
    $update->execute();

    header("Location: detail.php?id=" . $buku_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
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
     FORM EDIT REVIEW
============================================================ -->

<div class="container py-5">

    <h3 class="fw-bold mb-4">Edit Review</h3>

    <form method="POST" enctype="multipart/form-data" class="row g-3 bg-white p-4 rounded shadow-sm">

        <div class="col-md-6">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" value="<?= $data['nama'] ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Upload Gambar Baru</label>
            <input type="file" name="foto" class="form-control">

            <?php if ($data["gambar"]): ?>
                <img src="uploads/<?= $data['gambar'] ?>" width="120" class="mt-2 rounded">
            <?php endif; ?>
        </div>

        <div class="col-12">
            <label class="form-label">Komentar</label>
            <textarea name="komentar" class="form-control" rows="4" required><?= $data['komentar'] ?></textarea>
        </div>

        <div class="col-12 mt-2">
            <button class="btn btn-warning px-4">Simpan</button>
            <a href="detail.php?id=<?= $buku_id ?>" class="btn btn-secondary px-4">Batal</a>
        </div>

    </form>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

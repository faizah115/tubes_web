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

function isMobile() {
    return preg_match(
        '/(android|avantgo|blackberry|iphone|ipad|ipod|opera mini|iemobile|mobile)/i',
        $_SERVER['HTTP_USER_AGENT']
    );
}
$isMobile = isMobile();

$loginUsername = $_SESSION["username"];
$getUserLogin = mysqli_query($conn, "SELECT id, role FROM users WHERE username='$loginUsername' LIMIT 1");
$userLogin = mysqli_fetch_assoc($getUserLogin);
if (!$userLogin) {
    $current_user_id = 0;
    $current_role = "guest"; 
} else {
    $current_user_id = $userLogin["id"];
    $current_role    = $userLogin["role"];
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama     = mysqli_real_escape_string($conn, $_POST["nama"]);
    $komentar = mysqli_real_escape_string($conn, $_POST["komentar"]);
    $user_id  = $current_user_id;

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
$qReviewMobile = mysqli_query($conn, "SELECT * FROM reviews WHERE buku_id = $buku_id ORDER BY id DESC");

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $buku["judul"] ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="CSS/detail.css?v=3">
</head>

    <!-- Navbar -->
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
                    <a href="logout.php" class="nav-link text-danger" onclick="return confirm('Keluar?')">Keluar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


    <!-- Delete -->
<?php if (isset($_SESSION["delete_notice"])): ?>
<div class="alert alert-warning text-center mt-3">
    <?= $_SESSION["delete_notice"]; unset($_SESSION["delete_notice"]); ?>
</div>
<?php endif; ?>

<div class="detail-wrapper py-5">
    <div class="detail-container">

        <div class="detail-image">
            <img src="assets/<?= $buku['gambar'] ?>" 
                 alt="<?= $buku['judul'] ?>"
                 class="img-fluid rounded shadow">
        </div>
        <div class="detail-content">
            <h1 class="fw-bold mb-3"><?= $buku["judul"] ?></h1>

            <?php if (!empty($buku["penulis"])): ?>
                <p class="text-muted mb-1"><strong>Penulis:</strong> <?= $buku["penulis"] ?></p>
            <?php endif; ?>

            <?php if (!empty($buku["genre"])): ?>
                <p class="mb-3"><strong>Genre:</strong> <?= $buku["genre"] ?></p>
            <?php endif; ?>

            <p class="mt-3 text-justify" style="line-height: 1.7;">
                <?= nl2br($buku["deskripsi"]) ?>
            </p>
        </div>

    </div> 

    <hr>

    <!-- Form Feview -->
    <h3 class="fw-bold mt-5 mb-3">Tambah Review</h3>

    <form method="POST" enctype="multipart/form-data" 
          class="row g-3 bg-white p-4 rounded shadow-sm">

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

    <!-- DESKTOP TABLE -->
<?php if (!$isMobile): ?>
<div class="review-container">

<table class="review-table">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Foto</th>
            <th>Komentar</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php while ($r = mysqli_fetch_assoc($qReview)): ?>
        <tr>
            <td><?= $r["nama"] ?></td>

            <td>
                <?php if ($r["gambar"]): ?>
                    <img src="uploads/<?= $r['gambar'] ?>" class="review-img-mini">
                <?php else: ?>
                    <span class="text-muted">-</span>
                <?php endif; ?>
            </td>

            <td class="text-justify"><?= nl2br($r["komentar"]) ?></td>


    <!-- Hapus komentar untuka admin -->
<td class="text-center">
<?php if ($current_role == "admin"): ?>
    <a href="delete_review.php?id=<?= $r['id'] ?>&buku_id=<?= $buku_id ?>&from=detail"
       onclick="return confirm('Admin akan menghapus komentar ini.')"
       class="btn btn-danger btn-sm">Delete</a>

<?php elseif ($r["user_id"] == $current_user_id): ?>
    <a href="edit_review.php?id=<?= $r['id'] ?>&buku_id=<?= $buku_id ?>"
       class="btn btn-warning btn-sm mb-1">Edit</a>

    <a href="delete_review.php?id=<?= $r['id'] ?>&buku_id=<?= $buku_id ?>&from=detail"
       onclick="return confirm('Hapus review ini?')"
       class="btn btn-danger btn-sm">Delete</a>

<?php else: ?>
    <span class="text-muted small">Tidak ada aksi</span>
<?php endif; ?>
</td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>


    <!-- MOBILE CARD -->
<?php else: ?>
    <?php while ($r = mysqli_fetch_assoc($qReviewMobile)): ?>
        <div class="review-card">
            <h5 class="fw-bold mb-2"><?= $r["nama"] ?></h5>
            <?php if ($r["gambar"]): ?>
                <img src="uploads/<?= $r["gambar"] ?>" class="review-img mb-3">
            <?php endif; ?>
            <p class="text-justify"><?= nl2br($r["komentar"]) ?></p>
            <div class="review-actions mt-3">
         <?php if ($current_role == "admin"): ?>
    <a href="edit_review.php?id=<?= $r['id'] ?>&buku_id=<?= $buku_id ?>"
       class="btn btn-warning btn-sm mb-1">Edit</a>
    <a href="delete_review.php?id=<?= $r['id'] ?>&buku_id=<?= $buku_id ?>&from=detail"
       class="btn btn-danger btn-sm">Delete</a>
<?php else: ?>
    <span class="text-muted small">Tidak ada aksi</span>
<?php endif; ?>
            </div>

        </div>
    <?php endwhile; ?>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
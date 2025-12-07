<?php
require "koneksi.php";

// Ambil semua review dari database
$sql = "SELECT * FROM review_lautbercerita ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$review = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Jika submit → hanya INSERT (tidak ada edit/delete)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST["nama"];
    $komentar = $_POST["komentar"];

    // Upload gambar
    $gambar = NULL;
    if (!empty($_FILES["foto"]["name"])) {
        $namaFile = uniqid() . "_" . $_FILES["foto"]["name"];
        move_uploaded_file($_FILES["foto"]["tmp_name"], "uploads/" . $namaFile);
        $gambar = $namaFile;
    }

    // Masukkan review ke database
    $stmt = $conn->prepare("
        INSERT INTO review_lautbercerita (nama, komentar, gambar)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sss", $nama, $komentar, $gambar);
    $stmt->execute();

    header("Location: laut_bercerita.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Review Buku | Laut Bercerita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container py-5">

    <h2 class="fw-bold">Laut Bercerita</h2>
    <p class="text-muted">Penulis: Leila S. Chudori.</p>
                    <td>
                    <img src="assets/review_lb.jpeg" width="200" class="rounded">
                </td>
                 <td>
                    <img src="assets/laut_bercerita.jpeg" width="200" class="rounded">
                </td>
    <p class="text-muted">Laut Bercerita adalah sebuah novel yang mengisahkan tentang perjalanan hidup dan pengalaman yang mendalam. Cerita ini membawa pembaca menyelami berbagai emosi dan refleksi tentang kehidupan, kehilangan, dan harapan. Dengan gaya penulisan yang kuat dan penuh makna, Laut Bercerita mengajak kita untuk merenungkan arti sebenarnya dari keberanian dan ketabahan dalam menghadapi tantangan hidup.</p>

    <hr>

    <h4>Kirim Review</h4>
    <form method="POST" enctype="multipart/form-data" class="row g-3">

        <div class="col-md-4">
            <label>Nama Pengguna</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label>Upload Gambar</label>
            <input type="file" name="foto" class="form-control">
        </div>

        <div class="col-12">
            <label>Komentar</label>
            <textarea name="komentar" class="form-control" rows="3" required></textarea>
        </div>

        <div class="col-12">
            <button class="btn btn-dark px-4">Kirim Review</button>
        </div>

    </form>

    <hr>

    <h4>Review Pengguna</h4>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <th>Foto</th>
                <th>Komentar</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php if (empty($review)): ?>
            <tr>
                <td colspan="4" class="text-center text-muted">Belum ada review</td>
            </tr>
        <?php else: ?>
            <?php foreach ($review as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r["nama"]) ?></td>

                    <td>
                        <?php if ($r["gambar"]): ?>
                            <img src="uploads/<?= $r["gambar"] ?>" width="120">
                        <?php else: ?>
                            Tidak ada gambar
                        <?php endif; ?>
                    </td>

                    <td><?= nl2br(htmlspecialchars($r["komentar"])) ?></td>

                    <td>
                        <a href="edit_review_lautbercerita.php?id=<?= $r['id'] ?>" class="btn btn-warning btn-sm">Edit</a>

                        <a href="delete_review_lautbercerita.php?id=<?= $r['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Hapus review ini?')">
                           Hapus
                        </a>
                    </td>

                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>

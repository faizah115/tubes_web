<?php
require "koneksi.php";

$id = $_GET["id"];

$result = mysqli_query($conn, "SELECT * FROM reviews_filosofi_teras WHERE id=$id");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Review tidak ditemukan!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = $_POST["nama"];
    $komentar = $_POST["komentar"];
    $gambar = $data["gambar"];

    // jika upload gambar baru
    if (!empty($_FILES["foto"]["name"])) {

        // hapus gambar lama
        if ($gambar && file_exists("uploads/" . $gambar)) {
            unlink("uploads/" . $gambar);
        }

        // upload baru
        $namaFile = uniqid() . "_" . $_FILES["foto"]["name"];
        move_uploaded_file($_FILES["foto"]["tmp_name"], "uploads/" . $namaFile);
        $gambar = $namaFile;
    }

    // update database
    $stmt = $conn->prepare("
        UPDATE reviews_filosofi_teras
        SET nama=?, komentar=?, gambar=?
        WHERE id=?
    ");
    $stmt->bind_param("sssi", $nama, $komentar, $gambar, $id);
    $stmt->execute();

    header("Location: filosofi_teras.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container py-5">
    <h3>Edit Review</h3>

    <form method="POST" enctype="multipart/form-data" class="row g-3">

        <div class="col-md-4">
            <label>Nama Pengguna</label>
            <input type="text" name="nama" value="<?= $data['nama'] ?>" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label>Upload Gambar Baru (Opsional)</label>
            <input type="file" name="foto" class="form-control">

            <?php if ($data["gambar"]): ?>
                <img src="uploads/<?= $data['gambar'] ?>" width="120" class="mt-2">
            <?php endif; ?>
        </div>

        <div class="col-12">
            <label>Komentar</label>
            <textarea name="komentar" class="form-control" rows="3" required><?= $data['komentar'] ?></textarea>
        </div>

        <div class="col-12">
            <button class="btn btn-warning">Simpan Perubahan</button>
            <a href="filosofi_teras.php" class="btn btn-secondary">Batal</a>
        </div>

    </form>

</div>
</body>
</html>

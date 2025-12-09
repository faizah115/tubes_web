<?php
require "koneksi.php";
require "session_check.php";

if (!isset($_GET["id"])) {
    die("ID review tidak ada!");
}

$id = intval($_GET["id"]);

// Ambil data review (untuk mengetahui buku_id juga)
$q = $conn->prepare("SELECT * FROM reviews WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$data = $q->get_result()->fetch_assoc();

if (!$data) {
    die("Review tidak ditemukan!");
}

// Ambil buku_id dari URL atau fallback dari database
$buku_id = isset($_GET["buku_id"]) ? intval($_GET["buku_id"]) : $data["buku_id"];


// =======================================
// UPDATE REVIEW
// =======================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = $_POST["nama"];
    $komentar = $_POST["komentar"];
    $gambar = $data["gambar"];

    // Upload foto baru
    if (!empty($_FILES["foto"]["name"])) {

        // Hapus foto lama jika ada
        if ($gambar && file_exists("uploads/" . $gambar)) {
            unlink("uploads/" . $gambar);
        }

        $newName = uniqid() . "_" . $_FILES["foto"]["name"];
        move_uploaded_file($_FILES["foto"]["tmp_name"], "uploads/" . $newName);

        $gambar = $newName;
    }

    // Update database
    $update = $conn->prepare("UPDATE reviews SET nama=?, komentar=?, gambar=? WHERE id=?");
    $update->bind_param("sssi", $nama, $komentar, $gambar, $id);
    $update->execute();

    header("Location: detail.php?id=" . $buku_id);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
<h3>Edit Review</h3>

<form method="POST" enctype="multipart/form-data" class="row g-3">

    <div class="col-md-6">
        <label>Nama</label>
        <input type="text" name="nama" value="<?= $data['nama'] ?>" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Upload Gambar Baru</label>
        <input type="file" name="foto" class="form-control">

        <?php if ($data["gambar"]): ?>
            <img src="uploads/<?= $data['gambar'] ?>" width="120" class="mt-2 rounded">
        <?php endif; ?>
    </div>

    <div class="col-12">
        <label>Komentar</label>
        <textarea name="komentar" class="form-control" rows="4" required><?= $data['komentar'] ?></textarea>
    </div>

    <div class="col-12">
        <button class="btn btn-warning">Simpan</button>
        <a href="detail.php?id=<?= $buku_id ?>" class="btn btn-secondary">Batal</a>
    </div>

</form>

</body>
</html>

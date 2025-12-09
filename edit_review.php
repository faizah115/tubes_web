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

$id = intval($_GET["id"]); // ubah ke integer


/* ----------------------------------------------------------
   AMBIL DATA REVIEW BERDASARKAN ID
   Digunakan untuk menampilkan data lama & mengambil buku_id
---------------------------------------------------------- */
$q = $conn->prepare("SELECT * FROM reviews WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$data = $q->get_result()->fetch_assoc();

if (!$data) {
    die("Review tidak ditemukan!");
}


/* ----------------------------------------------------------
   MENGAMBIL buku_id
   Jika tidak dikirim via URL → gunakan data dari database
---------------------------------------------------------- */
$buku_id = isset($_GET["buku_id"]) ? intval($_GET["buku_id"]) : $data["buku_id"];


// =================================================================
//                              UPDATE REVIEW
// =================================================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama     = $_POST["nama"];
    $komentar = $_POST["komentar"];
    $gambar   = $data["gambar"];  // gambar lama (default)

    /* ----------------------------------------------------------
       JIKA USER MENGUPLOAD FOTO BARU
       → hapus foto lama
       → simpan foto baru ke folder uploads/
    ---------------------------------------------------------- */
    if (!empty($_FILES["foto"]["name"])) {

        if ($gambar && file_exists("uploads/" . $gambar)) {
            unlink("uploads/" . $gambar); // hapus foto lama
        }

        $newName = uniqid() . "_" . $_FILES["foto"]["name"];
        move_uploaded_file($_FILES["foto"]["tmp_name"], "uploads/" . $newName);

        $gambar = $newName; // ganti dengan foto baru
    }

    /* ----------------------------------------------------------
       UPDATE DATA REVIEW KE DATABASE
    ---------------------------------------------------------- */
    $update = $conn->prepare("UPDATE reviews SET nama=?, komentar=?, gambar=? WHERE id=?");
    $update->bind_param("sssi", $nama, $komentar, $gambar, $id);
    $update->execute();

    // kembali ke halaman detail buku
    header("Location: detail.php?id=" . $buku_id);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Review</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<!-- Judul halaman -->
<h3>Edit Review</h3>

<!-- Form edit review -->
<form method="POST" enctype="multipart/form-data" class="row g-3">

    <!-- Input nama pengguna -->
    <div class="col-md-6">
        <label>Nama</label>
        <input type="text" name="nama" value="<?= $data['nama'] ?>" class="form-control" required>
    </div>

    <!-- Input upload gambar baru -->
    <div class="col-md-6">
        <label>Upload Gambar Baru</label>
        <input type="file" name="foto" class="form-control">

        <!-- Menampilkan foto lama jika ada -->
        <?php if ($data["gambar"]): ?>
            <img src="uploads/<?= $data['gambar'] ?>" width="120" class="mt-2 rounded">
        <?php endif; ?>
    </div>

    <!-- Input komentar -->
    <div class="col-12">
        <label>Komentar</label>
        <textarea name="komentar" class="form-control" rows="4" required><?= $data['komentar'] ?></textarea>
    </div>

    <!-- Tombol simpan & batal -->
    <div class="col-12">
        <button class="btn btn-warning">Simpan</button>
        <a href="detail.php?id=<?= $buku_id ?>" class="btn btn-secondary">Batal</a>
    </div>

</form>

</body>
</html>

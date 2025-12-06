<?php
// ===============================================
// Sistem Review - Laut Bercerita (PHP Version)
// ===============================================

// Lokasi file JSON
$dataFile = __DIR__ . "/data/reviews_laut.json";
$uploadDir = __DIR__ . "/uploads/";

// Pastikan folder tersedia
if (!is_dir(__DIR__ . "/data")) mkdir(__DIR__ . "/data", 0777, true);
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// Ambil data review
$reviews = file_exists($dataFile)
    ? json_decode(file_get_contents($dataFile), true)
    : [];

// Simpan JSON
function saveData($file, $data)
{
    file_put_contents($file, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// ===============================================
// Tambah / Edit Review
// ===============================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST["nama"]);
    $komentar = trim($_POST["komentar"]);
    $edit_id = isset($_POST["edit_id"]) ? intval($_POST["edit_id"]) : null;

    if ($nama === "" || $komentar === "") {
        $error = "Nama dan komentar wajib diisi!";
    } else {

        // Upload gambar (jika ada)
        $gambar = null;
        if (!empty($_FILES["foto"]["name"])) {
            $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
            $newName = uniqid("img_") . "." . $ext;
            $target = $uploadDir . $newName;

            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target)) {
                $gambar = "uploads/" . $newName;
            }
        }

        // ---- Mode Edit ----
        if ($edit_id !== null && isset($reviews[$edit_id])) {
            $reviews[$edit_id]["nama"] = $nama;
            $reviews[$edit_id]["komentar"] = $komentar;

            // Jika upload gambar baru → hapus lama
            if ($gambar !== null) {
                if (!empty($reviews[$edit_id]["gambar"]) && file_exists(__DIR__ . "/" . $reviews[$edit_id]["gambar"])) {
                    unlink(__DIR__ . "/" . $reviews[$edit_id]["gambar"]);
                }
                $reviews[$edit_id]["gambar"] = $gambar;
            }
        }

        // ---- Mode Tambah ----
        else {
            $reviews[] = [
                "nama" => $nama,
                "komentar" => $komentar,
                "gambar" => $gambar
            ];
        }

        saveData($dataFile, $reviews);
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }
}

// ===============================================
// Hapus Review
// ===============================================
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);

    if (isset($reviews[$id])) {
        if (!empty($reviews[$id]["gambar"]) && file_exists(__DIR__ . "/" . $reviews[$id]["gambar"])) {
            unlink(__DIR__ . "/" . $reviews[$id]["gambar"]);
        }
        array_splice($reviews, $id, 1);
        saveData($dataFile, $reviews);
    }

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

// ===============================================
// Ambil data edit
// ===============================================
$editMode = false;
$editData = null;
if (isset($_GET["edit"])) {
    $edit_id = intval($_GET["edit"]);
    if (isset($reviews[$edit_id])) {
        $editMode = true;
        $editData = $reviews[$edit_id];
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Buku | Laut Bercerita</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="index.php">BukuKita</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto gap-4">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Riwayat Komentar</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Daftar Buku</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- DETAIL BUKU -->
    <section class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <img src="assets/Laut Bercerita.jpeg" class="img-fluid rounded shadow">
            </div>

            <div class="col-md-8">
                <h2 class="fw-bold">Laut Bercerita</h2>
                <p class="text-muted">Penulis: Leila S. Chudori</p>
                <p class="text-muted">Genre: Fiksi / Drama / Tragedi</p>

                <p class="deskripsibuku">
                    "Laut Bercerita" adalah novel fiksi sejarah yang mengisahkan tragedi kelam penghilangan paksa aktivis pada masa Orde Baru 1998...
                </p>
            </div>
        </div>
    </section>

    <hr>

    <!-- FORM REVIEW -->
    <section class="container py-4">

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Nama Pengguna</label>
                <input type="text" name="nama" class="form-control" required
                    value="<?= $editMode ? $editData['nama'] : '' ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Upload Gambar</label>
                <input type="file" name="foto" class="form-control">

                <?php if ($editMode && !empty($editData["gambar"])): ?>
                    <img src="<?= $editData["gambar"] ?>" width="150" class="mt-2 rounded">
                <?php endif; ?>
            </div>

            <div class="col-12">
                <label class="form-label">Komentar</label>
                <textarea name="komentar" class="form-control" rows="3" required><?= $editMode ? $editData['komentar'] : '' ?></textarea>
            </div>

            <?php if ($editMode): ?>
                <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
                <div class="col-12">
                    <button class="btn btn-warning px-4">Simpan Perubahan</button>
                    <a href="laut_bercerita.php" class="btn btn-secondary">Batal</a>
                </div>
            <?php else: ?>
                <div class="col-12">
                    <button class="btn btn-dark px-4">Kirim Review</button>
                </div>
            <?php endif; ?>

        </form>
    </section>

    <hr>

    <!-- TABEL REVIEW -->
    <section class="container py-4">
        <h3 class="fw-bold mb-3">Review Pengguna</h3>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>Foto</th>
                    <th>Komentar</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada review</td>
                    </tr>

                <?php else: ?>
                    <?php foreach ($reviews as $i => $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r["nama"]) ?></td>

                            <td>
                                <?php if (!empty($r["gambar"])): ?>
                                    <img src="<?= $r["gambar"] ?>" width="200" class="rounded">
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada gambar</span>
                                <?php endif; ?>
                            </td>

                            <td class="komentar"><?= nl2br(htmlspecialchars($r["komentar"])) ?></td>

                            <td>
                                <a href="?edit=<?= $i ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="?delete=<?= $i ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus review ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            </tbody>
        </table>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        © 2025 BukuKita. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

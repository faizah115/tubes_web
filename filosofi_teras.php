<?php
// filosofi_teras.php
// Simple review system: simpan review ke JSON, upload gambar ke uploads/
// Pastikan folder 'uploads' dan 'data' dapat ditulis oleh webserver.

// Path file data
$dataFile = __DIR__ . '/data/reviews.json';
$uploadDir = __DIR__ . '/uploads/';

// Create folders jika belum ada
if (!is_dir(__DIR__ . '/data')) mkdir(__DIR__ . '/data', 0777, true);
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// Ambil data existing
$reviews = [];
if (file_exists($dataFile)) {
    $json = file_get_contents($dataFile);
    $reviews = $json ? json_decode($json, true) : [];
}

// Helper: simpan ke JSON
function save_reviews($path, $arr) {
    file_put_contents($path, json_encode(array_values($arr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Proses form submit (tambah / edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['nama'] ?? '');
    $komentar = trim($_POST['komentar'] ?? '');
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : null;

    // Validasi sederhana
    if ($name === '' || $komentar === '') {
        $error = "Nama dan komentar wajib diisi.";
    } else {
        // Handle file upload (opsional)
        $imagePath = null;
        if (!empty($_FILES['foto']['name'])) {
            $f = $_FILES['foto'];
            if ($f['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $allowed = ['jpg','jpeg','png','gif','webp'];
                if (!in_array(strtolower($ext), $allowed)) {
                    $error = "Format gambar tidak diizinkan. (jpg,jpeg,png,gif,webp)";
                } else {
                    $newName = uniqid('img_') . '.' . $ext;
                    $target = $uploadDir . $newName;
                    if (move_uploaded_file($f['tmp_name'], $target)) {
                        $imagePath = 'uploads/' . $newName;
                    } else {
                        $error = "Gagal menyimpan file gambar.";
                    }
                }
            } else {
                $error = "Terjadi error saat upload gambar.";
            }
        }

        // Jika tidak ada error, simpan data
        if (!isset($error)) {
            if ($edit_id !== null && isset($reviews[$edit_id])) {
                // Edit: replace fields. Jika user upload gambar baru, hapus gambar lama.
                if ($imagePath) {
                    // hapus gambar lama jika ada
                    if (!empty($reviews[$edit_id]['image']) && file_exists(__DIR__ . '/' . $reviews[$edit_id]['image'])) {
                        @unlink(__DIR__ . '/' . $reviews[$edit_id]['image']);
                    }
                    $reviews[$edit_id]['image'] = $imagePath;
                }
                $reviews[$edit_id]['name'] = htmlspecialchars($name, ENT_QUOTES);
                $reviews[$edit_id]['comment'] = htmlspecialchars($komentar, ENT_QUOTES);
                $reviews[$edit_id]['updated_at'] = date('Y-m-d H:i:s');
            } else {
                // Tambah baru
                $reviews[] = [
                    'name' => htmlspecialchars($name, ENT_QUOTES),
                    'comment' => htmlspecialchars($komentar, ENT_QUOTES),
                    'image' => $imagePath, // bisa null
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            save_reviews($dataFile, $reviews);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Proses delete via GET ?delete=ID
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if (isset($reviews[$id])) {
        // hapus file image jika ada
        if (!empty($reviews[$id]['image']) && file_exists(__DIR__ . '/' . $reviews[$id]['image'])) {
            @unlink(__DIR__ . '/' . $reviews[$id]['image']);
        }
        array_splice($reviews, $id, 1); // remove dan reindex
        save_reviews($dataFile, $reviews);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Jika edit request via GET ?edit=ID -> isi form dengan data
$editMode = false;
$editData = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    if (isset($reviews[$id])) {
        $editMode = true;
        $editData = $reviews[$id];
        $editIndex = $id;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Review Buku | Filosofi Teras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* sedikit styling internal agar langsung jalan */
    .hero-img { width:100%; height:auto; object-fit:cover; }
    .komentar { white-space: pre-wrap; }
    .preview-img { max-width:200px; border-radius:8px; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
  <div class="container">
    <a class="navbar-brand fw-bold fs-4" href="index.php">BukuKita</a>
  </div>
</nav>

<section class="container py-5">
  <div class="row">
    <div class="col-md-4">
      <img src="assets/filosofi_teras.jpeg" class="img-fluid rounded shadow" alt="Filosofi Teras">
    </div>
    <div class="col-md-8">
      <h2 class="fw-bold">Filosofi Teras</h2>
      <p class="text-muted">Penulis: Henry Manampiring</p>
      <p class="text-muted">Genre: Non-Fiksi/ Pengembangan Diri</p>
      <p class="mt-3">Filosofi Teras (atau Stoikisme) ... (deskripsi singkat).</p>
    </div>
  </div>
</section>

<hr>

<section class="container py-4">
  <?php if(!empty($error)): ?>
    <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Nama Pengguna</label>
      <input type="text" name="nama" class="form-control" required value="<?= $editMode ? htmlspecialchars($editData['name'] ?? '') : '' ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Upload Gambar</label>
      <input type="file" name="foto" class="form-control">
      <?php if($editMode && !empty($editData['image'])): ?>
        <div class="mt-2">
          <small>Gambar saat ini:</small><br>
          <img src="<?=htmlspecialchars($editData['image'])?>" class="preview-img" alt="preview">
        </div>
      <?php endif; ?>
    </div>
    <div class="col-12">
      <label class="form-label">Komentar</label>
      <textarea name="komentar" class="form-control" rows="3" required><?= $editMode ? htmlspecialchars($editData['comment'] ?? '') : '' ?></textarea>
    </div>

    <?php if($editMode): ?>
      <input type="hidden" name="edit_id" value="<?= $editIndex ?>">
      <div class="col-12">
        <button class="btn btn-warning px-4" type="submit">Simpan Perubahan</button>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">Batal</a>
      </div>
    <?php else: ?>
      <div class="col-12">
        <button class="btn btn-dark px-4" type="submit">Kirim Review</button>
      </div>
    <?php endif; ?>
  </form>
</section>

<hr>

<section class="container py-4">
  <h3 class="fw-bold mb-3">Review Pengguna</h3>

  <table class="table table-striped table-bordered">
    <thead class="table-dark">
      <tr><th>Nama</th><th>Foto</th><th>Komentar</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      <?php if(empty($reviews)): ?>
        <tr><td colspan="4" class="text-center">Belum ada review.</td></tr>
      <?php else: ?>
        <?php foreach($reviews as $i => $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td>
              <?php if(!empty($r['image']) && file_exists(__DIR__ . '/' . $r['image'])): ?>
                <img src="<?= htmlspecialchars($r['image']) ?>" width="200" class="rounded">
              <?php else: ?>
                <small class="text-muted">-</small>
              <?php endif; ?>
            </td>
            <td class="komentar"><?= nl2br(htmlspecialchars($r['comment'])) ?></td>
            <td>
              <a class="btn btn-sm btn-warning" href="?edit=<?= $i ?>">Edit</a>
              <a class="btn btn-sm btn-danger" href="?delete=<?= $i ?>" onclick="return confirm('Hapus review ini?')">Hapus</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</section>

<footer class="bg-dark text-white text-center py-3">© 2025 BukuKita. All rights reserved.</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

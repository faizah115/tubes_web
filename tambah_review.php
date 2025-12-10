<?php
require "session_check.php";
require "koneksi.php";

/* Pastikan user login */
if (!isset($_SESSION["username"])) {
    die("Anda harus login untuk mengirim komentar.");
}

$username = $_SESSION["username"];

/* Ambil user_id berdasarkan username */
$user_q = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' LIMIT 1");
$user = mysqli_fetch_assoc($user_q);

if (!$user) {
    die("User tidak ditemukan.");
}

$user_id = $user["id"]; // ⭐ WAJIB: untuk Riwayat Komentar


/* Ambil data dari form */
$buku_id  = $_POST["buku_id"] ?? null;
$nama     = $_POST["nama"] ?? "";
$komentar = $_POST["komentar"] ?? "";
$created_at = date("Y-m-d H:i:s");

if (!$buku_id) {
    die("ID buku tidak ada.");
}


/* PROSES UPLOAD FOTO (opsional) */
$gambar = null;

if (isset($_FILES["gambar"]) && $_FILES["gambar"]["error"] === 0) {

    $folder = "uploads/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["gambar"]["name"]);
    $targetPath = $folder . $fileName;

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetPath)) {
        $gambar = $fileName;
    }
}


/* SIMPAN DATA KOMENTAR KE DATABASE */
/* ⭐ PERBAIKAN PENTING:
   User ID sekarang disimpan ke database agar riwayat bekerja di semua device
*/
$sql = "
    INSERT INTO reviews (buku_id, user_id, nama, komentar, gambar, created_at)
    VALUES (?, ?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iissss", $buku_id, $user_id, $nama, $komentar, $gambar, $created_at);

if ($stmt->execute()) {

    header("Location: riwayat_halaman.php?status=success");
    exit;

} else {
    echo "Gagal menyimpan komentar: " . $conn->error;
}
?>

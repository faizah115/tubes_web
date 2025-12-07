



<?php
require "koneksi.php";

if (!isset($_GET["id"])) {
    die("ID review tidak ditemukan!");
}

$id = intval($_GET["id"]);

// Ambil gambar lama terlebih dahulu
$q = $conn->prepare("SELECT gambar FROM reviews WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$res = $q->get_result();
$data = $res->fetch_assoc();

if ($data) {
    if ($data["gambar"] && file_exists("uploads/" . $data["gambar"])) {
        unlink("uploads/" . $data["gambar"]);
    }
}

// Hapus row dari database
$del = $conn->prepare("DELETE FROM reviews WHERE id=?");
$del->bind_param("i", $id);
$del->execute();

// Balik ke halaman detail buku sebelumnya
$buku_id = $_GET["buku_id"] ?? "";
header("Location: detail.php?id=" . $buku_id);
exit;
?>

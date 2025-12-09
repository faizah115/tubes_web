<?php
/* --------------------------------------------------
   IMPORT FILE: koneksi database & cek session login
--------------------------------------------------- */
require "koneksi.php";
require "session_check.php";

/* --------------------------------------------------
   CEK APAKAH ID REVIEW DIKIRIM MELALUI URL
--------------------------------------------------- */
if (!isset($_GET["id"])) {
    die("ID review tidak ditemukan!");
}

$id = intval($_GET["id"]); // mengubah id menjadi integer


/* --------------------------------------------------
   AMBIL DATA REVIEW UNTUK CEK ADA GAMBAR ATAU TIDAK
   Jika ada gambarnya → hapus file gambarnya
--------------------------------------------------- */
$q = $conn->prepare("SELECT gambar FROM reviews WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$res   = $q->get_result();
$data  = $res->fetch_assoc();

if ($data) {
    // Jika ada gambar dan file-nya memang ada → hapus file tersebut
    if ($data["gambar"] && file_exists("uploads/" . $data["gambar"])) {
        unlink("uploads/" . $data["gambar"]);
    }
}


/* --------------------------------------------------
   HAPUS DATA REVIEW DARI DATABASE
--------------------------------------------------- */
$del = $conn->prepare("DELETE FROM reviews WHERE id=?");
$del->bind_param("i", $id);
$del->execute();


/* --------------------------------------------------
   REDIRECT KEMBALI KE HALAMAN DETAIL BUKU
   buku_id dikirim dari URL → dipakai agar kembali ke buku yang benar
--------------------------------------------------- */
$buku_id = $_GET["buku_id"] ?? ""; // jika tidak ada, beri string kosong
header("Location: detail.php?id=" . $buku_id);
exit;
?>

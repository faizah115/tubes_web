<?php
/* --------------------------------------------------
   IMPORT FILE: koneksi database & cek session login
--------------------------------------------------- */
require "koneksi.php";
require "session_check.php";

/* --------------------------------------------------
   CEK ID REVIEW
--------------------------------------------------- */
if (!isset($_GET["id"])) {
    die("ID review tidak ditemukan!");
}

$id = intval($_GET["id"]); // aman


/* --------------------------------------------------
   AMBIL DATA REVIEW (untuk hapus gambar jika ada)
--------------------------------------------------- */
$q = $conn->prepare("SELECT gambar FROM reviews WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$res  = $q->get_result();
$data = $res->fetch_assoc();

if ($data) {
    $gambarPath = __DIR__ . "/uploads/" . $data["gambar"];

    if ($data["gambar"] && file_exists($gambarPath)) {
        unlink($gambarPath); // Hapus file
    } else {
        error_log("Gambar tidak ditemukan atau path salah: " . $gambarPath);
    }
}



/* --------------------------------------------------
   HAPUS REVIEW DARI DATABASE
--------------------------------------------------- */
$del = $conn->prepare("DELETE FROM reviews WHERE id=?");
$del->bind_param("i", $id);
$del->execute();


/* --------------------------------------------------
   CEK ASAL HALAMAN (redirect sesuai asal)
--------------------------------------------------- */
$from    = $_GET["from"] ?? "";        // riwayat OR detail
$buku_id = $_GET["buku_id"] ?? "";     // dipakai jika dari detail

// Jika delete berasal dari halaman Riwayat
if ($from === "riwayat") {
    header("Location: riwayat_halaman.php");
    exit;
}

// Jika delete berasal dari halaman Detail Buku
if ($from === "detail" && $buku_id !== "") {
    header("Location: detail.php?id=" . $buku_id);
    exit;
}

// Jika tidak ada info asal → fallback
header("Location: index.php");
exit;

?>

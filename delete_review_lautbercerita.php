<?php
require "koneksi.php";

$id = $_GET["id"];

// ambil data gambar
$result = mysqli_query($conn, "SELECT gambar FROM reviews_lautbercerita WHERE id=$id");
$row = mysqli_fetch_assoc($result);

// hapus gambar
if ($row && $row["gambar"] && file_exists("uploads/" . $row["gambar"])) {
    unlink("uploads/" . $row["gambar"]);
}

// hapus review dari database
mysqli_query($conn, "DELETE FROM reviews_filosofi_teras WHERE id=$id");

header("Location: filosofi_teras.php");
exit;
?>

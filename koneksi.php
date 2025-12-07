<?php
$server = "localhost";
$user   = "root";
$pass   = "";
$dbname = "bukupedia";

$conn = mysqli_connect($server, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>

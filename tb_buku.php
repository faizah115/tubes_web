<?php 
$dbhost = "localhost"; 
$dbuser = "root"; 
$dbpass = ""; 
$dbname = "bukukita_db"; 
//Membuat koneksi    
$conn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname); 
if(mysqli_connect_errno()){ 
echo "Koneksi gagal: ".mysqli_connect_error(); 
} 
//query membuat tabel
$sql = "CREATE TABLE buku (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    halaman_detail VARCHAR(255) NOT NULL,
    deskripsi TEXT
)"; 
if (mysqli_query($conn, $sql)) { 
echo "Table buku berhasil dibuat"; 
} else { 
echo "Gagal membuat tabel: " . mysqli_error($conn); 
} 
mysqli_close($conn); 
?> 


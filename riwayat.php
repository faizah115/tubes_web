<?php
require "session_check.php";   
require "koneksi.php";         


$user_id = $_SESSION["user_id"]; 


$sql = "
    SELECT r.*, b.judul 
    FROM reviews r 
    JOIN buku b ON r.buku_id = b.id
    WHERE r.user_id = $user_id
    ORDER BY r.created_at DESC
";

$result = mysqli_query($conn, $sql); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Komentar Saya</title>

    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<h2 class="title">Riwayat Komentar Kamu</h2>
<!-- Container untuk menampung semua review -->
<div class="review-container">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="review-card">
            <h3><?= $row["judul"] ?></h3>
            <p><?= nl2br($row["komentar"]) ?></p>
            <?php if ($row["gambar"]): ?>
                <img src="uploads/<?= $row["gambar"] ?>" width="150">
            <?php endif; ?>

            <!-- Tombol Edit & Delete -->
            <div class="actions">
                <a href="edit_review.php?id=<?= $row['id'] ?>" class="btn-warning">Edit</a>

                <a href="delete_review.php?id=<?= $row['id'] ?>" 
                   class="btn-danger"
                   onclick="return confirm('Hapus komentar ini?')">
                   Delete
                </a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>

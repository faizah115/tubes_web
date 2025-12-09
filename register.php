<?php
/* --------------------------------------------
   IMPORT FILE KONEKSI DATABASE
   Dibutuhkan untuk proses pendaftaran akun
--------------------------------------------- */
require "koneksi.php";
?>
<!DOCTYPE html>
<!-- Deklarasi dokumen HTML5 -->
<html lang="id"> <!-- Bahasa Indonesia -->

<head>
    <meta charset="UTF-8"> <!-- Encoding standar -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Supaya tampilan responsif di HP -->

    <title>Daftar Akun Baru</title> <!-- Judul tab browser -->

    <!-- File CSS utama -->
    <link rel="stylesheet" href="CSS/style.css">

    <!-- Import font Poppins dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="login-body">
    <!-- Judul halaman pendaftaran -->
    <h1 class="page-title">
        Buat Akun Baru di <span class="highlight">BUKUPEDIA</span>
    </h1>
    <!-- Container utama untuk form pendaftaran -->
    <div class="login-container">
        <div class="login-box">

            <!-- Judul form -->
            <h2>Daftar Akun Baru</h2>
            <p class="subtitle">Silakan isi form untuk membuat akun</p>
            <!-- Pesan sukses jika proses register berhasil -->
            <?php if (isset($_GET["success"])): ?>
                <div class="alert success">
                    <?= htmlspecialchars($_GET["success"]) ?>
                </div>
            <?php endif; ?>

            <!-- Pesan error jika terjadi kesalahan input -->
            <?php if (isset($_GET["error"])): ?>
                <div class="alert">
                    <?= htmlspecialchars($_GET["error"]) ?>
                </div>
            <?php endif; ?>
            <!-- Form pendaftaran user baru -->
            <form method="post" action="proses_register.php">

                <!-- Input username -->
                <div class="input-group">
                    <input type="text" name="username" placeholder="Buat Username" required>
                </div>

                <!-- Input password -->
                <div class="input-group">
                    <input type="password" name="password" placeholder="Buat Password" required>
                </div>

                <!-- Tombol submit -->
                <button type="submit" class="btn-login">Daftar</button>
            </form>
            <!-- Tombol kembali ke halaman login -->
            <p class="subtitle" style="margin-top:15px;">
                ← <a href="login.php">Kembali ke Login</a>
            </p>

        </div>
    </div>
</body>
</html>

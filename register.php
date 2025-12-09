<?php
/* --------------------------------------------
   IMPORT FILE KONEKSI DATABASE
   Dibutuhkan untuk proses pendaftaran akun
--------------------------------------------- */
require "koneksi.php";
?>
<!DOCTYPE html>
<html lang="id"> 

<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru</title> 

    <!-- File CSS utama -->
    <link rel="stylesheet" href="CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="login-body">
    <h1 class="page-title">
        Buat Akun Baru di <span class="highlight">BUKUPEDIA</span>
    </h1>
    <!-- Container utama untuk form pendaftaran -->
    <div class="login-container">
        <div class="login-box">

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
                <div class="input-group">
                    <input type="text" name="username" placeholder="Buat Username" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Buat Password" required>
                </div>
                <button type="submit" class="btn-login">Daftar</button>
            </form>
            <p class="subtitle" style="margin-top:15px;">
                ← <a href="login.php">Kembali ke Login</a>
            </p>

        </div>
    </div>
</body>
</html>

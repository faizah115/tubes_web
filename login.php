<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aplikasi</title>
<link rel="stylesheet" href="CSS/style.css">


    <!-- Google Font aesthetic -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body class="login-body">

    <div class="login-container">
        <div class="login-box">

            <h2>Selamat Datang</h2>
            <p class="subtitle">Masukkan akun untuk melanjutkan</p>

            <!-- tampilkan pesan error -->
            <?php if (isset($_GET["error"])): ?>
                <div class="alert">
                    <?= htmlspecialchars($_GET["error"]) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="cek_login.php">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>
    </div>

</body>
</html>

<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aplikasi</title>

    <!-- CSS Aesthetic -->
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/alert.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body class="login-register-body">

    <div class="login-container">
        <div class="login-box">

            <h2>Login Pengguna</h2>
            <p class="subtitle">Masukkan akun untuk melanjutkan</p>

            <!-- Pesan sukses -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert-success">
                    <?= $_GET['success'] ?>
                </div>
            <?php endif; ?>

            <!-- Pesan error -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert-error">
                    <?= $_GET['error'] ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="cek_login.php">

                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        ingat saya
                    </label>
                </div>

                <button type="submit" class="btn-login">Login</button>

            </form>

            <!-- Navigasi -->
            <p class="subtitle" style="margin-top:10px;">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </p>

        </div>
    </div>

</body>
</html>

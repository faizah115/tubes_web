<?php 
session_start(); 

// Ambil role dari URL (default = user)
$role = $_GET["role"] ?? "user";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aplikasi</title>

    <!-- CSS Aesthetic milikmu -->
    <link rel="stylesheet" href="CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body class="login-register-body">

    <div class="login-container">
        <div class="login-box">

            <h2><?= $role == "admin" ? "Login Admin" : "Login Pengguna" ?></h2>
            <p class="subtitle">Masukkan akun untuk melanjutkan</p>

            <!-- Pesan Error -->
            <?php if (isset($_GET["error"])): ?>
                <div class="alert">
                    <?= htmlspecialchars($_GET["error"]) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="cek_login.php">

                <!-- ROLE diteruskan ke cek_login -->
                <input type="hidden" name="role" value="<?= $role ?>">

                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember Me
                    </label>
                </div>

                <button type="submit" class="btn-login">Login</button>

            </form>

            <!-- Navigasi -->
            <?php if ($role == "admin"): ?>
                <p class="subtitle" style="margin-top:10px;">
                    Bukan admin? <a href="login.php?role=user">Login pengguna</a>
                </p>
            <?php else: ?>
                <p class="subtitle" style="margin-top:10px;">
                    Belum punya akun? <a href="register.php">Daftar di sini</a>
                </p>
                <p class="subtitle">
                    Admin? <a href="login.php?role=admin">Login admin</a>
                </p>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>

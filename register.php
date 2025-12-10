<?php
$error = $_GET['error'] ?? "";
$success = $_GET['success'] ?? "";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengguna</title>

    <link rel="stylesheet" href="CSS/register.css">
</head>

<body>

<div class="register-card">

    <h2 class="register-title">Daftar Akun Baru</h2>
    <p class="register-sub">Silakan isi form untuk membuat akun</p>

    <?php if ($error): ?>
        <p style="color:red; text-align:center; margin-bottom:10px;">
            <?= $error ?>
        </p>
    <?php endif; ?>

 <?php if (isset($_GET['success'])): ?>
    <div class="alert-success">
        <?= $_GET['success'] ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert-error">
        <?= $_GET['error'] ?>
    </div>
<?php endif; ?>


    <form method="POST" action="proses_register.php">

        <input 
            type="text" 
            name="username" 
            placeholder="Username"
            class="register-input"
            required>

        <input 
            type="password" 
            name="password" 
            placeholder="Password"
            class="register-input"
            required>

        <button type="submit" class="register-btn">Daftar</button>
    </form>

    <div class="register-link">
        ← <a href="login.php">Kembali ke Login</a>
    </div>

</div>

</body>
</html>

<!DOCTYPE html>
<!-- Deklarasi jenis dokumen HTML5 -->
<html lang="id"> <!-- Menandakan bahasa halaman adalah Indonesia -->

<head>
    <meta charset="UTF-8"> <!-- Mengatur encoding karakter -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Agar halaman responsif di perangkat mobile -->

    <title>Pilih Akses</title> <!-- Judul tab pada browser -->

    <!-- Import font Poppins dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        /* ============================================
           STYLING BODY — Background & tata letak halaman
        ============================================= */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif; /* Font utama */
            background: linear-gradient(135deg, #ff9a3c, #ff6f3c, #ffbd69); /* Gradasi warna */
            height: 100vh; /* Full tinggi layar */
            display: flex;
            justify-content: center; /* Posisi horizontal tengah */
            align-items: center;     /* Posisi vertikal tengah */
            text-align: center;
        }

        /* ============================================
           CONTAINER BOX — Kotak putih tempat pilihan akses
        ============================================= */
        .box {
            background: #ffffffdd; /* Putih semi transparan */
            padding: 40px;
            width: 370px;
            border-radius: 18px; /* Sudut melengkung */
            box-shadow: 0 12px 35px rgba(0,0,0,0.2); /* Bayangan */
            animation: fadeIn 0.7s ease; /* Animasi masuk */
        }

        /* Judul utama dalam box */
        h2 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* Paragraf deskripsi */
        p {
            color: #555;
            margin-bottom: 25px;
        }

        /* ============================================
           BUTTON STYLE — Tombol Admin & User
        ============================================= */
        .btn {
            display: block; /* Supaya tombol memenuhi 1 baris */
            width: 100%; /* Lebar penuh */
            padding: 12px;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #ff6f3c, #ff9a3c);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 500;
            transition: 0.3s; /* Efek hover */
            cursor: pointer;
            text-decoration: none; /* Hilangkan underline */
            font-size: 15px;
        }

        /* Efek saat tombol disorot (hover) */
        .btn:hover {
            transform: scale(1.05); /* Membesar sedikit */
            background: linear-gradient(135deg, #ff9a3c, #ff6f3c);
        }

        /* ============================================
           ANIMASI FADE IN — untuk efek muncul pada box
        ============================================= */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
    <!-- ============================================
         KONTEN UTAMA — Pilihan akses user/admin
    ============================================= -->
    <div class="box">

        <!-- Judul halaman -->
        <h2>Pilih Akses</h2>

        <!-- Deskripsi informasi -->
        <p>Silakan pilih jenis akun yang ingin digunakan</p>

        <!-- Tombol login admin (mengirim parameter role=admin) -->
        <a href="login.php?role=admin" class="btn">Login sebagai Admin</a>

        <!-- Tombol login/daftar user (mengirim parameter role=user) -->
        <a href="login.php?role=user" class="btn">Login / Daftar sebagai User</a>

    </div>

</body>
</html>

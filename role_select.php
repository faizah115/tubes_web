<!DOCTYPE html>
<html lang="id"> 

<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pilih Akses</title> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        /* ============================================
           STYLING BODY — Background & tata letak halaman
        ============================================= */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #ff9a3c, #ff6f3c, #ffbd69); 
            height: 100vh; 
            display: flex;
            justify-content: center; 
            align-items: center;     
            text-align: center;
        }

        /* ============================================
           CONTAINER BOX — Kotak putih tempat pilihan akses
        ============================================= */
        .box {
            background: #ffffffdd; 
            padding: 40px;
            width: 370px;
            border-radius: 18px; 
            box-shadow: 0 12px 35px rgba(0,0,0,0.2); 
            animation: fadeIn 0.7s ease; 
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
            display: block; 
            width: 100%; 
            padding: 12px;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #ff6f3c, #ff9a3c);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 500;
            transition: 0.3s; 
            cursor: pointer;
            text-decoration: none; 
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
          Pilihan akses user/admin
    ============================================= -->
    <div class="box">
        <h2>Pilih Akses</h2>
        <p>Silakan pilih jenis akun yang ingin digunakan</p>
        <a href="login.php?role=admin" class="btn">Login sebagai Admin</a>
        <a href="login.php?role=user" class="btn">Login / Daftar sebagai User</a>

    </div>

</body>
</html>

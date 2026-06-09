<?php
session_start();

// --- 1. KONEKSI DATABASE ---
$host     = "localhost"; 
$dbname   = "daftar";     
$uname    = "root";      
$password = "";          

$koneksi = mysqli_connect($host, $uname, $password, $dbname);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$pesan_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($koneksi, $_POST['Nama_lengkap']);
    $pass = $_POST['Password'];

    // Mencari data user berdasarkan Nama_Lengkap di tabel form_daftar
    $sql = "SELECT * FROM form_daftar WHERE Nama_Lengkap = '$nama'";
    $result = mysqli_query($koneksi, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // MENGECEK PASSWORD (Menggunakan password_verify karena di database di-hash)
        if (password_verify($pass, $row['Password'])) {
            $_SESSION['nama'] = $row['Nama_Lengkap'];
            header("Location: toko.html");
            exit;
        } else {
            $pesan_error = "Password salah!";
        }
    } else {
        $pesan_error = "Akun tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Kue</title>
    <style>
        @keyframes munculDulu {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes denyutGlow {
            0% { box-shadow: 0 0 15px #f94646; }
            50% { box-shadow: 0 0 30px #ff0000; }
            100% { box-shadow: 0 0 15px #f94646; }
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #000 url('gambar/merah2.jpg') no-repeat center bottom / cover;
            color: #fff;
            height: 100vh; 
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden; 
        }

        .container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            justify-content: space-between;
            align-items: center;
            animation: munculDulu 1s ease-out;
        }

        .kiri {
            flex: 1;
            padding-right: 50px;
            margin-bottom: 120px; 
        }

        .kiri h2 {
            color: red;
            font-size: 24px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .kiri p {
            font-size: 16px;
            line-height: 1.5;
            text-align: justify;
        }

        .kanan {
            flex: 0.8;
            display: flex;
            flex-direction: column;
            align-items: flex-end; 
        }

        .login-box {
            border: 2px solid #444;
            padding: 40px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 5px;
            width: 300px;
            animation: denyutGlow 3s infinite ease-in-out;
            transition: transform 0.3s;
        }

        .login-box:hover {
            transform: translateY(-5px);
        }

        table {
            width: 100%;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 5px;
            background: #fff;
            border: 1px solid #ccc;
            color: #000;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border: 1px solid red;
            box-shadow: 0 0 5px red;
        }

        .btn-submit, .btn-reg {
            border: 1px solid #999;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit {
            background: #eee;
            padding: 5px 15px;
            font-size: 14px;
        }

        .btn-submit:hover {
            background: #fff;
            box-shadow: 0 0 10px white;
        }

        .btn-reg {
            background: #eee;
            padding: 3px 15px;
        }

        .btn-reg:hover {
            background: red;
            color: white;
            border-color: white;
        }

        .error-msg {
            color: #ffeb3b;
            font-size: 12px;
            margin-bottom: 10px;
            text-align: center;
            animation: munculDulu 0.5s;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="kiri">
            <h2>MY TOKO KUE</h2>
            <p>
                "MY TOKO KUE hadir untuk memudahkan Anda mendapatkan
                jajanan favorit tanpa repot. Nikmati kemudahan sistem pemesanan langsung, 
                beragam pilihan produk terlengkap, serta metode pembayaran yang fleksibel.
                Belanja camilan kini jadi jauh lebih mudah dan menyenangkan!"
            </p>
        </div>

        <div class="kanan">
            <div class="login-box">
                <?php if ($pesan_error != ""): ?>
                    <div class="error-msg"><?php echo $pesan_error; ?></div>
                <?php endif; ?>

                <form action="" method="post">
                    <table>
                        <tr>
                            <td>Nama Lengkap</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px;">
                                <input type="text" name="Nama_lengkap" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Password</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 15px;">
                                <input type="password" name="Password" required>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <button type="submit" class="btn-submit">Login</button>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; padding-top: 10px; font-size: 13px;">
                                Belum punya Akun ?
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <button type="button" class="btn-reg" onclick="window.location.href='Registar.php'">Register</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
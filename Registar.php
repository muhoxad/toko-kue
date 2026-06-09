<?php
session_start();

$host     = "localhost"; 
$dbname   = "daftar";    
$uname    = "root";      
$password = "";          

$koneksi = mysqli_connect($host, $uname, $password, $dbname);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$pesan = "";

if (isset($_POST["daftar"])) {
    // Sanitasi input
    $nama          = mysqli_real_escape_string($koneksi, $_POST['Nama_Lengkap']);
    $jenis_kelamin = $_POST['Jenis_Kelamin'];
    $agama         = $_POST['Agama'];
    $tempat_lahir  = mysqli_real_escape_string($koneksi, $_POST['Tempat_Lahir']);
    $tanggal_lahir = $_POST['Tanggal_Lahir'];
    $alamat_rumah  = mysqli_real_escape_string($koneksi, $_POST['Alamat_Rumah']);
    $email         = mysqli_real_escape_string($koneksi, $_POST['Email']);
    $pass_input    = $_POST['Password'];
    $repeat_password = $_POST['Repeat_Password'];

    if ($pass_input !== $repeat_password) {
        $pesan = "<div class='error'>Password tidak cocok!</div>";
    } else {
        // HASH PASSWORD (Sangat penting untuk keamanan!)
        $hashed_password = password_hash($pass_input, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO form_daftar (Nama_Lengkap, Jenis_Kelamin, Agama, Tempat_Lahir, Tanggal_Lahir, Alamat_Rumah, Email, Password)
                VALUES ('$nama', '$jenis_kelamin', '$agama', '$tempat_lahir', '$tanggal_lahir', '$alamat_rumah', '$email', '$hashed_password')";

        if (mysqli_query($koneksi, $sql)) {
            echo "<script>alert('Pendaftaran Berhasil! Silahkan Login.'); window.location='Loginn.php';</script>";
            exit;
        } else {
            $pesan = "<div class='error'>Gagal menyimpan: " . mysqli_error($koneksi) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - MY TOKO KUE</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #121212; color: #fff; min-height: 100vh; }
        
        .navbar { display: flex; justify-content: space-between; align-items: center; background: rgba(189, 17, 17, 0.9); padding: 15px 50px; }
        .logo { font-size: 22px; font-weight: bold; text-transform: uppercase; }
        .navbar a { color: #fff; text-decoration: none; font-weight: bold; background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 5px; }
        
        .main-container { display: flex; justify-content: center; padding: 40px 20px; }
        form { background: rgba(0, 0, 0, 0.85); padding: 30px; border-radius: 15px; width: 100%; max-width: 450px; border: 1px solid #f94646; box-shadow: 0 0 25px #f94646; }
        
        table { width: 100%; }
        td { padding: 8px 0; }
        
        input, select { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #444; box-sizing: border-box; }
        
        /* Tampilan Radio Button yang Sejajar */
        .radio-group { display: flex; align-items: center; gap: 15px; }
        .radio-group label { display: flex; align-items: center; cursor: pointer; }
        .radio-group input { width: auto; margin-right: 5px; }

        button[name="daftar"] { width: 100%; padding: 12px; background: #f94646; color: white; border: none; border-radius: 5px; cursor: pointer; margin-top: 15px; }
        .error { background: rgba(255, 0, 0, 0.2); color: #ffbaba; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">MY TOKO KUE</div>
        <a href="Loginn.php">KEMBALI KE LOGIN</a>
    </div>

    <div class="main-container">
        <form action="" method="POST">
            <h2>Registrasi Pelanggan</h2>
            <?php echo $pesan; ?>
            <table>
                <tr><td>Nama Lengkap</td><td><input type="text" name="Nama_Lengkap" required></td></tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td class="radio-group">
                        <label><input type="radio" name="Jenis_Kelamin" value="L" required> Laki-laki</label>
                        <label><input type="radio" name="Jenis_Kelamin" value="P" required> Perempuan</label>
                    </td>
                </tr>
                <tr><td>Agama</td><td><select name="Agama"><option>Islam</option><option>Kristen</option><option>Hindu</option><option>Budha</option></select></td></tr>
                <tr><td>Tempat & Tgl Lahir</td><td>
                    <input type="text" name="Tempat_Lahir" style="width: 45%;" required>
                    <input type="date" name="Tanggal_Lahir" style="width: 50%;" max="<?php echo date('Y-m-d'); ?>" required>
                </td></tr>
                <tr><td>Alamat</td><td><input type="text" name="Alamat_Rumah" required></td></tr>
                <tr><td>Email</td><td><input type="email" name="Email" required></td></tr>
                <tr><td>Password</td><td><input type="password" name="Password" required></td></tr>
                <tr><td>Konfirmasi Password</td><td><input type="password" name="Repeat_Password" required></td></tr>
                <tr><td colspan="2"><button type="submit" name="daftar">SUBMIT & DAFTAR</button></td></tr>
            </table>
        </form>
    </div>
</body>
</html>
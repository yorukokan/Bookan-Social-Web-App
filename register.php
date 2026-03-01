<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
require "database.php";
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $kadi = $_POST["kadi"];
    $email = $_POST["email"];
    $sifre = password_hash($_POST["sifre"], PASSWORD_DEFAULT);

    $ekle = $baglanti->prepare("INSERT INTO kullanicilar (kullanici_adi, eposta, sifre) VALUES (?,?,?)");
    $ekle->bind_param("sss", $kadi, $email, $sifre);

    if ($ekle->execute()) 
    {
        header("Location: login.php");
        exit();
    } 
    else 
    {
        $error = "Bu e-posta adresi zaten kayıtlı!";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol | BOOKAN</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
<div class="register-wrapper">
    <div class="register-left">

        <h2>Kayıt Ol</h2>
        <p>Hesabını oluştur ve kitap dünyasına katıl.</p>
        <?php if($error): ?>
            <p style="color:red; margin-bottom:15px;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="kadi" class="register-input" placeholder="Kullanıcı Adı" required>
            <input type="email" name="email" class="register-input" placeholder="E-posta" required>
            <input type="password" name="sifre" class="register-input" placeholder="Şifre" required>
            <button class="register-btn" type="submit">Kayıt Ol</button>
        </form>
        <div class="login-link">
            Zaten hesabın var mı? <a href="login.php">Giriş Yap</a>
        </div>
    </div>
    <div class="register-right">
        <img src="assets/images/register.png" alt="register">
    </div>
</div>
</body>
</html>

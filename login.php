<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
require "database.php";
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $email = $_POST["email"];
    $sifre = $_POST["sifre"];

    $sorgu = $baglanti->prepare("SELECT * FROM kullanicilar WHERE eposta=?");
    $sorgu->bind_param("s", $email);
    $sorgu->execute();
    $sonuc = $sorgu->get_result();

    if ($sonuc->num_rows > 0) 
    {
        $u = $sonuc->fetch_assoc();

        if (password_verify($sifre, $u["sifre"])) 
        {
            $_SESSION["kullanici_id"] = $u["id"];
            $_SESSION["kullanici_adi"] = $u["kullanici_adi"];
            header("Location: index.php");
            exit();
        } 
        else 
        {
            $error = "Şifre hatalı!";
        }
    } 
    else 
    {
        $error = "Bu e-posta ile hesap bulunamadı.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap | BOOKAN</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-left"> 
        <h2>Giriş Yap</h2>
        <p>Hesabına giriş yaparak alıntılarını ve kitaplarını yönet.</p>

        <?php if($error): ?>
            <p style="color: red; margin-bottom: 15px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" class="login-input" placeholder="E-posta" required>
            <input type="password" name="sifre" class="login-input" placeholder="Şifre" required>
            <button class="login-btn" type="submit">Giriş Yap</button>
        </form>
        <div class="register-link">
            Hesabın yok mu? <a href="register.php">Kayıt Ol</a>
        </div>
    </div>
    <div class="login-right">
        <img src="assets/images/login.png" alt="bookan">
    </div>
</div>
</body>
</html>

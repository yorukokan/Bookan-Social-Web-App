<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
require "database.php";
session_start();

if (!isset($_SESSION["kullanici_id"])) 
  {
    header("Location: login.php");
    exit();
}

$kullanici_id = $_SESSION["kullanici_id"];
$mesaj="";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $liste_adi = trim($_POST["liste_adi"]);
    $aciklama = trim($_POST["aciklama"]);

    if (empty($liste_adi)) 
    {
        $mesaj = "<div class='error-msg'>Liste adı boş bırakılamaz.</div>";
    } 
    else 
    {
        try 
        {
            $ekle = $baglanti->prepare("INSERT INTO okuma_listeleri (kullanici_id, liste_adi, aciklama) VALUES (?, ?, ?)");
            $ekle->bind_param("iss", $kullanici_id, $liste_adi, $aciklama);

            if ($ekle->execute()) 
            {
                $mesaj = "<div class='success-msg'>Liste başarıyla oluşturuldu!</div>";
                unset($_POST);
            }
        } 
        catch (mysqli_sql_exception $e) 
        {
            $mesaj = "<div class='error-msg'>Bu adda bir listeniz zaten mevcut. Lütfen başka bir ad deneyin.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Liste Oluştur | BOOKAN</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/alinti_ekle.css"> 
</head>
<body>
<?php require "header.php"; ?>
<main class="main-wrapper">
  <div class="container quote-add-wrapper">
    <div class="quote-add-card">
      <h2>Yeni Okuma Listesi Oluştur</h2>
      <?php echo $mesaj; ?>
      <form method="POST" class="quote-form">
        <label>Liste Adı:</label>
        <input type="text" name="liste_adi" class="quote-select" placeholder="Okunacaklar, Romanlar, 2026 Listem vb." required value="<?= htmlspecialchars($_POST['liste_adi'] ?? '') ?>">
        <label>Açıklama:</label>
        <textarea name="aciklama" class="quote-textarea" rows="4" placeholder="Bu liste hakkındaki notlarınız..."><?= htmlspecialchars($_POST['aciklama'] ?? '') ?></textarea>
        <button type="submit" class="quote-btn">Listeyi Oluştur</button>
      </form>
    </div>
  </div>
</main>
<?php require "footer.php"; ?>
</body>
</html>
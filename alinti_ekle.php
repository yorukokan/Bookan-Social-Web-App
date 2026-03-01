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

$kullanici_id=$_SESSION["kullanici_id"];
$mesaj="";

$kitap_sorgu=$baglanti->query("SELECT id, kitap_adi FROM kitaplar ORDER BY kitap_adi ASC");

$selected_kitap_id=isset($_GET['kitap_id']) ? intval($_GET['kitap_id']):0;

if ($_SERVER["REQUEST_METHOD"]=="POST") 
{
    $kitap_id = $_POST["kitap_id"];
    $alinti_metni = trim($_POST["alinti_metni"]);

    if (empty($alinti_metni)) 
    {
        $mesaj = "<div class='error-msg'>Alıntı metni boş bırakılamaz.</div>";
    } 
    else 
    {
        $ekle = $baglanti->prepare("INSERT INTO alintilar (kullanici_id,kitap_id,alinti_metni) VALUES (?,?,?)");
        $ekle->bind_param("iis",$kullanici_id,$kitap_id,$alinti_metni);

        if ($ekle->execute()) 
        {
            $mesaj="<div class='success-msg'>Alıntı başarıyla paylaşıldı.</div>";
        } 
        else 
        {
            $mesaj="<div class='error-msg'>Bir hata oluştu,tekrar deneyin.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Alıntı Ekle | BOOKAN</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/alinti_ekle.css">
</head>
<body>
<?php require "header.php"; ?>
<main class="main-wrapper">
  <div class="container quote-add-wrapper">
    <div class="quote-add-card">
      <h2>Alıntı Ekle</h2>
      <?php echo $mesaj; ?>
      <form method="POST" class="quote-form">
        <label>Kitap Seç:</label>
        <select name="kitap_id" class="quote-select" required>
          <?php $kitap_sorgu->data_seek(0);?>
          <?php while($k=$kitap_sorgu->fetch_assoc()):?>
            <option value="<?php echo $k['id'];?>" 
                    <?= ($selected_kitap_id == $k['id']) ? 'selected':''?>>
              <?php echo $k['kitap_adi']; ?>
            </option>
          <?php endwhile;?>
        </select>
        <label>Alıntı Metni:</label>
        <textarea name="alinti_metni" class="quote-textarea" rows="6" placeholder="Bu kısıma alıntıyı yazınız." required></textarea>
        <button type="submit" class="quote-btn">Paylaş</button>
      </form>
    </div>
  </div>
</main>
<?php require"footer.php";?>
</body>
</html>
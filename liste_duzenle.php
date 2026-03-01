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

$liste_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$kullanici_id = $_SESSION["kullanici_id"];
$mesaj = "";

$sorgu_liste = $baglanti->prepare("SELECT * FROM okuma_listeleri WHERE id = ? AND kullanici_id = ?");
$sorgu_liste->bind_param("ii", $liste_id, $kullanici_id);
$sorgu_liste->execute();
$liste = $sorgu_liste->get_result()->fetch_assoc();

if (!$liste) 
{
    die("Liste bulunamadı veya bu listeyi düzenleme yetkiniz yok.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guncelle"])) 
{
    $yeni_ad = trim($_POST["liste_adi"]);
    $yeni_aciklama = trim($_POST["aciklama"]);

    if (empty($yeni_ad)) 
        {
        $mesaj = "<div class='error-msg'>Liste adı boş bırakılamaz.</div>";
    } 
    else 
    {
        try 
        {
            $guncelle = $baglanti->prepare("UPDATE okuma_listeleri SET liste_adi = ?, aciklama = ? WHERE id = ?");
            $guncelle->bind_param("ssi", $yeni_ad, $yeni_aciklama, $liste_id);
            if ($guncelle->execute()) 
            {
                $mesaj = "<div class='success-msg'>Liste bilgileri başarıyla güncellendi!</div>";
                $liste['liste_adi'] = $yeni_ad;
                $liste['aciklama'] = $yeni_aciklama;
            }
        } 
        catch (mysqli_sql_exception $e) 
        {
             $mesaj = "<div class='error-msg'>Bu adda bir listeniz zaten mevcut.</div>";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kitap_cikar"])) 
{
    $kitap_id = intval($_POST["kitap_id"]);
    
    $cikar = $baglanti->prepare("DELETE FROM liste_kitaplari WHERE liste_id = ? AND kitap_id = ?");
    $cikar->bind_param("ii", $liste_id, $kitap_id);
    $cikar->execute();
    $mesaj = "<div class='success-msg'>Kitap listeden başarıyla çıkarıldı.</div>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["listeyi_sil"])) 
{
    $baglanti->query("DELETE FROM liste_kitaplari WHERE liste_id = $liste_id");
    $baglanti->query("DELETE FROM okuma_listeleri WHERE id = $liste_id");
    
    $_SESSION['list_msg'] = "Liste başarıyla silindi.";
    header("Location: profil.php");
    exit();
}

$kitaplar_sorgu = $baglanti->
prepare("
    SELECT k.id, k.kitap_adi, k.yazar, k.kapak_resmi
    FROM liste_kitaplari lk
    JOIN kitaplar k ON k.id = lk.kitap_id
    WHERE lk.liste_id = ?
    ORDER BY lk.eklenme_tarihi DESC");

$kitaplar_sorgu->bind_param("i", $liste_id);
$kitaplar_sorgu->execute();
$kitaplar = $kitaplar_sorgu->get_result();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($liste["liste_adi"]); ?> Düzenle | BOOKAN</title>

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/alinti_ekle.css"> 
    <link rel="stylesheet" href="assets/css/search.css"> 
    <style>
        .kitap-item 
        {
            justify-content: space-between;
        }
        .kitap-item .info 
        {
            flex-grow: 1;
        }
    </style>
</head>

<body>
<?php require "header.php"; ?>
<main class="main-wrapper">
  <div class="container quote-add-wrapper">

    <div class="quote-add-card" style="margin-bottom: 30px;">

      <h2>"<?php echo htmlspecialchars($liste["liste_adi"]); ?>" Listesini Düzenle</h2>

      <?php echo $mesaj; ?>

      <form method="POST" class="quote-form">
        <input type="hidden" name="guncelle" value="1">
        
        <label>Liste Adı:</label>
        <input type="text" name="liste_adi" class="quote-select" required value="<?= htmlspecialchars($liste['liste_adi']) ?>">

        <label>Açıklama (İsteğe Bağlı):</label>
        <textarea name="aciklama" class="quote-textarea" rows="4"><?= htmlspecialchars($liste['aciklama'] ?? '') ?></textarea>

        <button type="submit" class="quote-btn">Listeyi Güncelle</button>
      </form>
    </div>

    <div class="quote-add-card" style="margin-bottom: 30px;">
        <h2>Listedeki Kitaplar (<?php echo $kitaplar->num_rows; ?>)</h2>

        <?php if ($kitaplar->num_rows > 0): ?>
            <div class="kitap-grid" style="grid-template-columns: 1fr;">
                <?php while($kitap = $kitaplar->fetch_assoc()): ?>
                    <div class="kitap-item">
                        <img src="uploads/<?php echo htmlspecialchars($kitap['kapak_resmi'] ?: 'book_placeholder.png'); ?>" alt="Kapak">
                        <div class="info">
                            <h4><?php echo htmlspecialchars($kitap['kitap_adi']); ?></h4>
                            <p><?php echo htmlspecialchars($kitap['yazar']); ?></p>
                        </div>
                        <form action="liste_duzenle.php?id=<?php echo $liste_id; ?>" method="POST" style="margin-left: 15px;">
                            <input type="hidden" name="kitap_id" value="<?php echo $kitap['id']; ?>">
                            <input type="hidden" name="kitap_cikar" value="1">
                            <button type="submit" class="btn btn-outline" style="padding: 8px 15px; font-size: 13px; border-color: #a30000; color: #a30000;">
                                Çıkar
                            </button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="no-content" style="box-shadow: none;">Bu listede hiç kitap yok.</p>
        <?php endif; ?>
    </div>
    <div class="quote-add-card" style="background: #ffefef; border: 1px solid #ffb4b4;">
        <h2>Listeyi Sil</h2>
        <p style="color: #a30000; margin-bottom: 15px;">
            UYARI: Bu işlem geri alınamaz. Liste silindiğinde içindeki tüm kitaplar da bu listeden kalıcı olarak kaldırılacaktır.
        </p>
        <form action="liste_duzenle.php?id=<?php echo $liste_id; ?>" method="POST" onsubmit="return confirm('Bu listeyi kalıcı olarak silmek istediğinizden emin misiniz?');">
            <input type="hidden" name="listeyi_sil" value="1">
            <button type="submit" class="quote-btn" style="background: #a30000; font-weight: 700;">
                Listeyi Kalıcı Olarak Sil
            </button>
        </form>
    </div>
  </div>
</main>
<?php require "footer.php"; ?>
</body>
</html>
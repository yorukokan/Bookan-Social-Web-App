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

$tur_sorgu = $baglanti->query("SELECT DISTINCT kitap_turu FROM kitaplar ORDER BY kitap_turu ASC");
$mevcut_turler = [];
while ($tur = $tur_sorgu->fetch_assoc()) 
{
    $mevcut_turler[] = $tur['kitap_turu'];
}

$hata_mesaji = $_SESSION['kitap_ekle_hata'] ?? null;
$basari_mesaji = $_SESSION['kitap_ekle_basari'] ?? null;
unset($_SESSION['kitap_ekle_hata']);
unset($_SESSION['kitap_ekle_basari']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Kitap Ekle | BOOKAN</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/kitap_ekle.css"> 
    </head>
<body>

<?php require "header.php"; ?>

<main class="main-wrapper">
    <div class="kitap-ekle-form-wrapper">
        <h2>📚 Yeni Kitap Ekle</h2>
        
        <?php if ($hata_mesaji): ?>
            <div class="error-msg"><?php echo $hata_mesaji; ?></div>
        <?php endif; ?>
        <?php if ($basari_mesaji): ?>
            <div class="success-msg"><?php echo $basari_mesaji; ?></div>
        <?php endif; ?>

        <form method="POST" action="kitap_ekle_action.php" enctype="multipart/form-data">
            
            <label for="kitap_adi">Kitap Adı *</label>
            <input type="text" id="kitap_adi" name="kitap_adi" required>

            <label for="yazar">Yazar Adı *</label>
            <input type="text" id="yazar" name="yazar" required>

            <label for="kitap_turu">Kitap Türü *</label>
            <div class="input-group-select">
                <select id="kitap_turu" name="kitap_turu" required style="flex-grow: 1;">
                    <option value="">Bir Tür Seçin</option>
                    <?php foreach ($mevcut_turler as $tur): ?>
                        <option value="<?php echo htmlspecialchars($tur); ?>">
                            <?php echo htmlspecialchars($tur); ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="Yeni Tür Ekle">VEYA YENİ TÜR YAZIN</option>
                </select>
            </div>
            <label for="aciklama">Açıklama *</label>
            <textarea id="aciklama" name="aciklama" required></textarea>
            <label for="kapak_resmi" style="margin-top: 15px;">Kapak Resmi</label>
            <input type="file" id="kapak_resmi" name="kapak_resmi" accept="image/*">
            <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Kitabı Kaydet</button>
        </form>
    </div>
</main>
<?php require "footer.php"; ?>
</body>
</html>
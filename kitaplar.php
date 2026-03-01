<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
require "database.php";
session_start();

$logged_in = isset($_SESSION["kullanici_id"]);
$turlar = $baglanti->query("SELECT DISTINCT kitap_turu FROM kitaplar ORDER BY kitap_turu ASC");
$seciliTur = isset($_GET['tur']) ? $_GET['tur'] : "Hepsi";

if ($seciliTur == "Hepsi") 
{
    $kitaplar = $baglanti->query("SELECT * FROM kitaplar ORDER BY eklenme_tarihi DESC");
} 
else 
{    $sorgu = $baglanti->prepare("SELECT * FROM kitaplar WHERE kitap_turu=? ORDER BY eklenme_tarihi DESC");
    $sorgu->bind_param("s", $seciliTur);
    $sorgu->execute();
    $kitaplar = $sorgu->get_result();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kitaplar | BOOKAN</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/kitaplar.css">
</head>
<body>
<?php require "header.php"; ?>
<main class="main-wrapper">
    <div class="container books-wrapper">
        
        <h2 class="books-title">📚 Kitaplar</h2>
        
        <div class="tur-bar" style="margin-bottom: 30px;">
            <a href="kitaplar.php" class="tur-item <?= ($seciliTur=='Hepsi') ? 'active' : '' ?>">Hepsi </a>
            <?php while($t = $turlar->fetch_assoc()): ?>
                <a href="kitaplar.php?tur=<?= urlencode($t['kitap_turu']) ?>"
                   class="tur-item <?= ($seciliTur == $t['kitap_turu']) ? 'active' : '' ?>">
                    <?= $t['kitap_turu'] ?>
                </a>
            <?php endwhile; ?>
        </div>
        
        <div class="books-grid">
            <?php while($kitap = $kitaplar->fetch_assoc()): ?>
                <div class="book-card">
                    <?php if (!empty($kitap["kapak_resmi"])): ?>
                        <img src="uploads/<?php echo $kitap['kapak_resmi']; ?>" alt="Kapak">
                    <?php else: ?>
                        <img src="assets/images/book_placeholder.png" alt="Kapak">
                    <?php endif; ?>
                    <div class="book-info">
                        <h3><?php echo $kitap["kitap_adi"]; ?></h3>
                        <p><?php echo $kitap["yazar"]; ?></p>
                        <span class="tur-badge"><?= $kitap["kitap_turu"]; ?></span>
                        <div class="actions">
                            <a class="view-btn" href="kitap_detay.php?id=<?php echo $kitap['id']; ?>">İncele</a>
                            <?php if (isset($_SESSION["kullanici_id"])): ?>
                                <a class="quote-add-btn" href="alinti_ekle.php?kitap_id=<?php echo $kitap['id']; ?>">
                                    Alıntı Gir
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php if ($logged_in): ?>
        <div class="add-book-section" style="text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px dashed var(--c4);">
             <a href="kitap_ekle.php" class="btn btn-primary add-book-btn">
                ➕ Yeni Kitap Ekle
            </a>
            <p style="margin-top: 10px; font-size: 13px; color: #777;">Burada olmayan bir kitap mı var? Sen ekle!</p>
        </div>
        <?php endif; ?>
        </div>
</main>
<?php require "footer.php"; ?>
</body>
</html>
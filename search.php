<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
require "database.php";
session_start();

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$kitap_sonuclari = [];
$kullanici_sonuclari = [];

if ($query) 
{
    $search_term = "%" . $query . "%";

    $kitap_sorgu = $baglanti->prepare("SELECT id, kitap_adi, yazar, kapak_resmi FROM kitaplar WHERE kitap_adi LIKE ? OR yazar LIKE ? LIMIT 10");
    $kitap_sorgu->bind_param("ss", $search_term, $search_term);
    $kitap_sorgu->execute();
    $kitap_sonuclari = $kitap_sorgu->get_result();

    $kullanici_sorgu = $baglanti->prepare("SELECT id, kullanici_adi, profil_resmi FROM kullanicilar WHERE kullanici_adi LIKE ? LIMIT 10");
    $kullanici_sorgu->bind_param("s", $search_term);
    $kullanici_sorgu->execute();
    $kullanici_sonuclari = $kullanici_sorgu->get_result();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Arama Sonuçları: <?php echo htmlspecialchars($query); ?> | BOOKAN</title>

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/search.css"> 
</head>
<body>
<?php require "header.php"; ?>
<main class="main-wrapper">
  <div class="container search-wrapper">
    <h2>"<?php echo htmlspecialchars($query); ?>" İçin Sonuçlar</h2>
    <?php if (!$query): ?>
        <p class="empty-message">Lütfen arama yapmak için bir anahtar kelime girin.</p>
    <?php elseif ($kitap_sonuclari->num_rows == 0 && $kullanici_sonuclari->num_rows == 0): ?>
        <p class="empty-message">Aradığınız kriterlere uygun sonuç bulunamadı.</p>
    <?php else: ?>
        <div class="search-section">
            <h3>📚 Kitaplar veya Yazarlar (<?php echo $kitap_sonuclari->num_rows; ?>)</h3>
            <div class="kitap-grid">
                <?php while($kitap = $kitap_sonuclari->fetch_assoc()): ?>
                    <a href="kitap_detay.php?id=<?php echo $kitap['id']; ?>" class="kitap-item">
                        <img src="uploads/<?php echo htmlspecialchars($kitap['kapak_resmi'] ?: 'book_placeholder.png'); ?>" alt="Kapak">
                        <div class="info">
                            <h4><?php echo htmlspecialchars($kitap['kitap_adi']); ?></h4>
                            <p><?php echo htmlspecialchars($kitap['yazar']); ?></p>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="search-section">
            <h3>👥 Kullanıcılar (<?php echo $kullanici_sonuclari->num_rows; ?>)</h3>
            <div class="kullanici-list">
                <?php while($kullanici = $kullanici_sonuclari->fetch_assoc()): ?>
                    <a href="profil.php?id=<?php echo $kullanici['id']; ?>" class="kullanici-item">
                        <img src="uploads/avatars/<?php echo htmlspecialchars($kullanici['profil_resmi'] ?: 'default_avatar.png'); ?>" alt="Avatar" class="kullanici-avatar">
                        <span class="kullanici-adi"><?php echo htmlspecialchars($kullanici['kullanici_adi']); ?></span>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; ?>
  </div>
</main>
<?php require "footer.php"; ?>
</body>
</html>
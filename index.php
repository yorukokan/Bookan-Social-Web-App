<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "database.php";
session_start();

$kadi = isset($_SESSION["kullanici_adi"]) ? $_SESSION["kullanici_adi"] : "Kitap Sever";
$current_user_id = $_SESSION["kullanici_id"] ?? 0;

$kitap_sayisi     = $baglanti->query("SELECT COUNT(*) AS s FROM kitaplar")->fetch_assoc()["s"];
$alinti_sayisi    = $baglanti->query("SELECT COUNT(*) AS s FROM alintilar")->fetch_assoc()["s"];
$kullanici_sayisi = $baglanti->query("SELECT COUNT(*) AS s FROM kullanicilar")->fetch_assoc()["s"];

$populer_kitaplar = $baglanti->
query("
    SELECT k.*, COUNT(f.id) AS fav_sayisi
    FROM kitaplar k
    LEFT JOIN favoriler f ON f.kitap_id = k.id
    GROUP BY k.id
    ORDER BY fav_sayisi DESC, k.eklenme_tarihi DESC
    LIMIT 8");

$yeni_alintilar = $baglanti->
query("
    SELECT a.alinti_metni, kt.kitap_adi, kt.id AS kitap_id, kt.kapak_resmi, k.kullanici_adi
    FROM alintilar a
    JOIN kitaplar kt ON kt.id = a.kitap_id
    JOIN kullanicilar k ON k.id = a.kullanici_id
    ORDER BY a.paylasim_tarihi DESC
    LIMIT 5");

$populer_turler = $baglanti->
query("
    SELECT kitap_turu, COUNT(*) AS tur_sayisi 
    FROM kitaplar 
    GROUP BY kitap_turu 
    ORDER BY tur_sayisi DESC 
    LIMIT 6");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Ana Sayfa | BOOKAN</title>

  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/home.css">
  <link rel="stylesheet" href="assets/css/kitaplar.css"> 
  <link rel="stylesheet" href="assets/css/profil.css"> 
</head>
<body>
<?php require "header.php"; ?>
<main class="main-wrapper">
  <div class="container home-wrapper">
    <section class="home-hero">
      <div class="hero-text">
        <h1>Hoş geldin, <?php echo htmlspecialchars($kadi); ?> 👋</h1>
        <p>
          Bookan ile okuduğun kitaplardan alıntılar paylaşabilir, yeni eserler
          keşfedebilir ve diğer okuyucuların paylaşımlarından ilham alabilirsin.
        </p>

        <div class="hero-buttons">
          <?php if (!isset($_SESSION["kullanici_id"])): ?>
            <a href="login.php" class="btn btn-primary">Giriş Yap</a>
            <a href="register.php" class="btn btn-outline">Kayıt Ol</a>
          <?php else: ?>
            <a href="alinti_ekle.php" class="btn btn-primary">Alıntı Paylaş</a>
            <a href="kitaplar.php" class="btn btn-outline">Kitapları Keşfet</a>
          <?php endif; ?>
        </div>
      </div>
      <div class="hero-image">
        <img src="assets/images/bookan.png" alt="Bookan">
      </div>
    </section>
    <section class="stats-grid">
      <div class="stat-card">
        <h3><?php echo $kitap_sayisi; ?></h3>
        <p>Sistemde kayıtlı kitap</p>
      </div>
      <div class="stat-card">
        <h3><?php echo $alinti_sayisi; ?></h3>
        <p>Paylaşılan alıntı</p>
      </div>
      <div class="stat-card">
        <h3><?php echo $kullanici_sayisi; ?></h3>
        <p>Toplam kullanıcı</p>
      </div>
    </section>
    <div style="height: 40px;"></div> 

    <?php if ($populer_kitaplar->num_rows>0):?>
    <section class="home-section">
        <h2 class="section-title">🔥 Trend Kitaplar</h2>
        <div class="horizontal-scroll-grid">
            <?php while($kitap = $populer_kitaplar->fetch_assoc()): ?>
                <a href="kitap_detay.php?id=<?php echo $kitap['id']; ?>" class="book-card-small">
                     <?php if (!empty($kitap["kapak_resmi"])):?>
                        <img src="uploads/<?php echo $kitap['kapak_resmi']; ?>" alt="Kapak">
                      <?php else: ?>
                        <img src="assets/images/book_placeholder.png" alt="Kapak">
                      <?php endif; ?>
                    <div class="info">
                        <h4><?php echo htmlspecialchars($kitap['kitap_adi']); ?></h4>
                        <p><?php echo htmlspecialchars($kitap['yazar']); ?></p>
                        <small>⭐️<?php echo $kitap['fav_sayisi']; ?>Favori</small>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <div style="height: 40px;"></div>

    <?php if ($populer_turler->num_rows>0): ?>
    <section class="home-section">
        <h2 class="section-title">✨ Keşfedilecek Popüler Türler</h2>
        <div class="tur-bar" style="margin-bottom: 20px;">
            <?php while($t = $populer_turler->fetch_assoc()): ?>
                <a href="kitaplar.php?tur=<?= urlencode($t['kitap_turu']) ?>"
                   class="tur-item">
                   <?= $t['kitap_turu'] ?>
                </a>
            <?php endwhile; ?>
            <a href="kitaplar.php" class="tur-item active">Tüm Kitaplar</a>
        </div>
    </section>
    <?php endif;?>
    <?php if ($yeni_alintilar->num_rows > 0): ?>
    <section class="home-section">
        <h2 class="section-title">💬 Son Paylaşılan Alıntılar</h2>
        <div class="quotes-grid">
            <?php while($a = $yeni_alintilar->fetch_assoc()): ?>
                <div class="quote-card-home card">
                    <div class="quote-book-link">
                        <div class="book-cover-small">
                            <?php if ($a["kapak_resmi"]): ?>
                                <img src="uploads/<?php echo $a['kapak_resmi']; ?>" alt="Kapak">
                            <?php else: ?>
                                <img src="assets/images/book_placeholder.png" alt="Kapak">
                            <?php endif; ?>
                        </div>
                        <a href="kitap_detay.php?id=<?php echo $a['kitap_id']; ?>" style="font-weight: 600; color: var(--c2);">
                            <?php echo htmlspecialchars($a['kitap_adi']); ?>
                        </a>
                    </div>
                    <p class="quote-content-home">“<?php echo nl2br(htmlspecialchars($a['alinti_metni'])); ?>”</p>
                    <small class="quote-author-home">— <?php echo htmlspecialchars($a['kullanici_adi']); ?></small>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    <?php endif; ?>
  </div>
</main>
<?php require "footer.php"; ?>
</body>
</html>
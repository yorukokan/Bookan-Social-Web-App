<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
require "database.php";
session_start();

$liste_id=isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($liste_id==0) 
{
    header("Location: profil.php");
    exit();
}

$current_user_id = $_SESSION["kullanici_id"] ?? 0;

$liste_sorgu = $baglanti->
prepare("
    SELECT ol.liste_adi, ol.aciklama, u.kullanici_adi, u.id AS liste_sahibi_id
    FROM okuma_listeleri ol
    JOIN kullanicilar u ON u.id = ol.kullanici_id
    WHERE ol.id = ?");

$liste_sorgu->bind_param("i", $liste_id);
$liste_sorgu->execute();
$liste_bilgisi = $liste_sorgu->get_result()->fetch_assoc();

if (!$liste_bilgisi) 
{
    die("Liste bulunamadı.");
}

$liste_sahibi_id = $liste_bilgisi['liste_sahibi_id'];
$profil_sahibi_mi = $current_user_id == $liste_sahibi_id;

$kitaplar_sorgu = $baglanti->
prepare("
    SELECT k.id, k.kitap_adi, k.yazar, k.kapak_resmi, k.kitap_turu
    FROM liste_kitaplari lk
    JOIN kitaplar k ON k.id = lk.kitap_id
    WHERE lk.liste_id = ?
    ORDER BY lk.eklenme_tarihi DESC");

$kitaplar_sorgu->bind_param("i", $liste_id);
$kitaplar_sorgu->execute();
$kitaplar = $kitaplar_sorgu->get_result();

$kitap_sayisi = $kitaplar->num_rows;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($liste_bilgisi["liste_adi"]); ?> | BOOKAN</title>

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/kitaplar.css">
    <!-- css kısa olduğu için yeni css oluşturmadım -->
    <style>
        .list-detail-header 
        {
            background: linear-gradient(135deg,var(--c4),var(--c3));
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 40px;
        }
        .list-detail-header h1 
        {
            font-size: 36px;
            color: var(--c1);
            margin-bottom: 10px;
        }
        .list-detail-header p 
        {
            font-size: 16px;
            color: #444;
            margin-bottom: 15px;
        }
        .list-meta 
        {
            font-size: 14px;
            color: var(--c1);
            font-weight: 600;
        }
    </style>
</head>
<body>
<?php require "header.php"; ?>
<main class="main-wrapper">
    <div class="container books-wrapper">
        <div class="list-detail-header">
            <h1>📚 <?php echo htmlspecialchars($liste_bilgisi["liste_adi"]); ?></h1>
            <p><?php echo htmlspecialchars($liste_bilgisi["aciklama"] ?: "Bu liste için bir açıklama girilmemiş."); ?></p>
            <div class="list-meta">
                <span>Oluşturan: <a href="profil.php?id=<?php echo $liste_sahibi_id; ?>" style="color:var(--c1); font-weight:700;"><?php echo htmlspecialchars($liste_bilgisi["kullanici_adi"]); ?></a></span>
                <span style="margin-left: 20px;">|</span>
                <span style="margin-left: 20px;"><?php echo $kitap_sayisi; ?> Kitap</span>
            </div>
            
            <?php if ($profil_sahibi_mi): ?>
                <a href="liste_duzenle.php?id=<?php echo $liste_id; ?>" class="btn btn-primary" style="margin-top: 20px;">
                    Listeyi Düzenle
                </a>
            <?php endif; ?>
        </div>
        <h2 class="books-title" style="margin-bottom: 30px;">Listedeki Kitaplar</h2>
        <?php if ($kitaplar->num_rows > 0): ?>
            <div class="books-grid">
                <?php while($kitap = $kitaplar->fetch_assoc()): ?>
                    <div class="book-card">
                        <a href="kitap_detay.php?id=<?php echo $kitap['id']; ?>">
                            <?php if (!empty($kitap["kapak_resmi"])): ?>
                                <img src="uploads/<?php echo $kitap['kapak_resmi']; ?>" alt="Kapak">
                            <?php else: ?>
                                <img src="assets/images/book_placeholder.png" alt="Kapak">
                            <?php endif; ?>

                            <div class="book-info">
                                <h3><?php echo $kitap['kitap_adi']; ?></h3>
                                <p><?php echo $kitap['yazar']; ?></p>
                                <span class="tur-badge"><?= $kitap["kitap_turu"]; ?></span>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="no-content">Bu listede henüz hiç kitap yok.</p>
        <?php endif; ?>
    </div>
</main>
<?php require "footer.php"; ?>
</body>
</html>
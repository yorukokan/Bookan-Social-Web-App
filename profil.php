<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
require "database.php";
session_start();

if (isset($_GET["id"])) 
{
    $kullanici_id = intval($_GET["id"]);
} 
else if (isset($_SESSION["kullanici_id"])) 
{
    $kullanici_id = $_SESSION["kullanici_id"];
} 
else 
{
    header("Location: login.php");
    exit();
}

$profil_sorgu = $baglanti->prepare("SELECT * FROM kullanicilar WHERE id = ?");
$profil_sorgu->bind_param("i", $kullanici_id);
$profil_sorgu->execute();
$profil_kullanici = $profil_sorgu->get_result()->fetch_assoc();

if (!$profil_kullanici) 
    {
    die("Kullanıcı bulunamadı.");
}

$profil_sahibi_mi = isset($_SESSION["kullanici_id"]) && $_SESSION["kullanici_id"] == $kullanici_id;
$current_user_id = $_SESSION["kullanici_id"] ?? 0;

$alinti_sayisi = $baglanti->query("SELECT COUNT(*) AS s FROM alintilar WHERE kullanici_id = $kullanici_id")->fetch_assoc()["s"];
$favori_sayisi = $baglanti->query("SELECT COUNT(*) AS s FROM favoriler WHERE kullanici_id = $kullanici_id")->fetch_assoc()["s"];
$takip_edilen_sayisi = $baglanti->query("SELECT COUNT(*) AS s FROM takip WHERE takip_eden_id = $kullanici_id")->fetch_assoc()["s"];
$takipci_sayisi = $baglanti->query("SELECT COUNT(*) AS s FROM takip WHERE takip_edilen_id = $kullanici_id")->fetch_assoc()["s"];
$liste_sayisi = $baglanti->query("SELECT COUNT(*) AS s FROM okuma_listeleri WHERE kullanici_id = $kullanici_id")->fetch_assoc()["s"];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["follow_action_type"]) && !$profil_sahibi_mi) 
{
    $action_type = $_POST["follow_action_type"];
    
    $kontrol_takip = $baglanti->prepare("SELECT id FROM takip WHERE takip_eden_id = ? AND takip_edilen_id = ?");
    $kontrol_takip->bind_param("ii", $current_user_id, $kullanici_id);
    $kontrol_takip->execute();
    $is_following = $kontrol_takip->get_result()->num_rows > 0;
    
    if ($action_type == "follow" && !$is_following) 
    {
        $sorgu = $baglanti->prepare("INSERT INTO takip (takip_eden_id, takip_edilen_id) VALUES (?, ?)");
        $sorgu->bind_param("ii", $current_user_id, $kullanici_id);
        $sorgu->execute();
    } 
    elseif ($action_type == "unfollow" && $is_following) 
    {
        $sorgu = $baglanti->prepare("DELETE FROM takip WHERE takip_eden_id = ? AND takip_edilen_id = ?");
        $sorgu->bind_param("ii", $current_user_id, $kullanici_id);
        $sorgu->execute();
    }
    header("Location: profil.php?id=" . $kullanici_id);
    exit();
}

$is_following = false;
if ($current_user_id != 0 && !$profil_sahibi_mi) 
{
    $kontrol_takip = $baglanti->prepare("SELECT id FROM takip WHERE takip_eden_id = ? AND takip_edilen_id = ?");
    $kontrol_takip->bind_param("ii", $current_user_id, $kullanici_id);
    $kontrol_takip->execute();
    $is_following = $kontrol_takip->get_result()->num_rows > 0;
}

$okuma_listeleri_sorgu = $baglanti->prepare("
    SELECT 
        ol.id, 
        ol.liste_adi, 
        (SELECT COUNT(lk.id) FROM liste_kitaplari lk WHERE lk.liste_id = ol.id) AS kitap_sayisi
    FROM okuma_listeleri ol
    WHERE ol.kullanici_id = ?
    ORDER BY ol.olusturma_tarihi DESC
");
$okuma_listeleri_sorgu->bind_param("i", $kullanici_id);
$okuma_listeleri_sorgu->execute();
$okuma_listeleri = $okuma_listeleri_sorgu->get_result();


$alinti_sorgu = $baglanti->prepare("
  SELECT a.*, kt.kitap_adi, kt.kapak_resmi 
  FROM alintilar a
  JOIN kitaplar kt ON kt.id = a.kitap_id
  WHERE a.kullanici_id = ?
  ORDER BY a.paylasim_tarihi DESC
");
$alinti_sorgu->bind_param("i", $kullanici_id);
$alinti_sorgu->execute();
$alintilar = $alinti_sorgu->get_result();

$favori_sorgu = $baglanti->prepare("
    SELECT k.*, k.kitap_turu
    FROM favoriler f
    JOIN kitaplar k ON k.id = f.kitap_id
    WHERE f.kullanici_id=?
    ORDER BY f.id DESC
");
$favori_sorgu->bind_param("i", $kullanici_id);
$favori_sorgu->execute();
$favori_kitaplar = $favori_sorgu->get_result();

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($profil_kullanici["kullanici_adi"]); ?> Profili | BOOKAN</title>

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/profil.css">
    <link rel="stylesheet" href="assets/css/kitaplar.css">
    </head>
<body>

<?php require "header.php"; ?>

<main class="main-wrapper">
    <div class="container profile-wrapper">

        <div class="profile-card">

            <div class="avatar-container">
                <img src="uploads/avatars/<?php echo htmlspecialchars($profil_kullanici["profil_resmi"] ?: 'default_avatar.png'); ?>"
                     alt="Avatar"
                     class="profile-avatar">

                <?php if ($profil_sahibi_mi): ?>
                    <form id="avatar-form" action="upload_avatar.php" method="POST" enctype="multipart/form-data">
                        <label for="avatar-file" class="upload-btn">
                            📷
                            <input type="file" name="avatar" id="avatar-file" accept="image/*" style="display: none;" onchange="document.getElementById('avatar-form').submit();">
                        </label>
                    </form>
                    <?php 
                        if (isset($_SESSION['avatar_msg'])) {
                            echo '<div style="font-size:12px; color:red; margin-top:5px; text-align: center;">' . $_SESSION['avatar_msg'] . '</div>';
                            unset($_SESSION['avatar_msg']);
                        }
                    ?>
                <?php endif; ?>
            </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($profil_kullanici["kullanici_adi"]); ?></h2>
                <p><?php echo htmlspecialchars($profil_kullanici["eposta"]); ?></p>
                <?php if ($profil_kullanici["bio"]): ?>
                    <p style="margin-top: 10px; font-style: italic; color: var(--c1);">
                        "<?php echo htmlspecialchars($profil_kullanici["bio"]); ?>"
                    </p>
                <?php endif; ?>
            </div>

            <?php if ($current_user_id != 0 && !$profil_sahibi_mi): ?>
                <form method="POST" style="margin-left: auto; align-self: flex-start;">
                    <input type="hidden" name="follow_action_type" value="<?php echo $is_following ? 'unfollow' : 'follow'; ?>">
                    <button type="submit" class="btn <?php echo $is_following ? 'btn-outline' : 'btn-primary'; ?>">
                        <?php echo $is_following ? 'Takibi Bırak' : 'Takip Et'; ?>
                    </button>
                </form>
            <?php endif; ?>
            
            <div class="stats-box-inner">
                <div class="stat-item">
                    <h3><?php echo $alinti_sayisi; ?></h3>
                    <p>Alıntı</p>
                </div>
                <div class="stat-item">
                    <h3><?php echo $favori_sayisi; ?></h3>
                    <p>Favori Kitap</p>
                </div>
                <div class="stat-item">
                    <h3><?php echo $liste_sayisi; ?></h3>
                    <p>Okuma Listesi</p>
                </div>

                <a href="takip_listesi.php?type=following&id=<?php echo $kullanici_id; ?>" class="stat-item">
                    <h3><?php echo $takip_edilen_sayisi; ?></h3>
                    <p>Takip Edilen</p>
                </a>

                <a href="takip_listesi.php?type=followers&id=<?php echo $kullanici_id; ?>" class="stat-item">
                    <h3><?php echo $takipci_sayisi; ?></h3>
                    <p>Takipçi</p>
                </a>
            </div>
        </div>
        
        <section class="profile-section" style="margin-top: 50px;">
            <h2 class="section-title">📚 Okuma Listelerim (<?php echo $liste_sayisi; ?>)</h2>
            
            <?php if ($profil_sahibi_mi): ?>
                <div style="margin-bottom: 20px;">
                    <a href="liste_olustur.php" class="btn btn-primary" style="padding: 8px 15px; font-size: 14px; border-radius: 8px;">
                        + Yeni Liste Oluştur
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($okuma_listeleri->num_rows > 0): ?>
                <div class="list-grid">
                    <?php while($liste = $okuma_listeleri->fetch_assoc()): ?>
                        <div class="list-card">
                            <div class="list-card-info">
                                <h4><?php echo htmlspecialchars($liste['liste_adi']); ?></h4>
                                <p><?php echo $liste['kitap_sayisi']; ?> Kitap</p>
                            </div>
                            <div class="list-card-actions">
                                <a href="liste_detay.php?id=<?php echo $liste['id']; ?>" class="btn btn-outline">
                                    Gör
                                </a>
                                <?php if ($profil_sahibi_mi): ?>
                                    <a href="liste_duzenle.php?id=<?php echo $liste['id']; ?>" class="btn btn-primary">
                                        Düzenle
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="no-content">Henüz oluşturulmuş bir okuma listesi yok.</p>
            <?php endif; ?>
        </section>
        <section class="profile-section">
            <h2 class="section-title">⭐ Favori Kitaplarım (<?php echo $favori_sayisi; ?>)</h2>

            <?php if ($favori_kitaplar->num_rows > 0): ?>
                <div class="books-grid">
                    <?php while($kitap = $favori_kitaplar->fetch_assoc()): ?>
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
                <p class="no-content">Henüz favorilere eklenmiş bir kitap yok.</p>
            <?php endif; ?>
        </section>

        <section class="profile-section" style="margin-top: 50px;">
            <h2 class="section-title">✏️ Son Paylaşılan Alıntılarım (<?php echo $alinti_sayisi; ?>)</h2>

            <?php if ($alintilar->num_rows > 0): ?>
                <?php while($alinti = $alintilar->fetch_assoc()): ?>
                    <div class="quote-card-profile">
                        <a href="kitap_detay.php?id=<?php echo $alinti['kitap_id']; ?>" class="quote-book-link">
                            <div class="book-cover-small">
                                <?php if ($alinti["kapak_resmi"]): ?>
                                    <img src="uploads/<?php echo $alinti['kapak_resmi']; ?>" alt="Kapak">
                                <?php else: ?>
                                    <img src="assets/images/book_placeholder.png" alt="Kapak">
                                <?php endif; ?>
                            </div>
                            <strong><?php echo htmlspecialchars($alinti["kitap_adi"]); ?></strong>
                        </a>
                        <p class="quote-text-profile">“<?php echo nl2br(htmlspecialchars($alinti["alinti_metni"])); ?>”</p>
                        <small class="quote-date-profile"><?php echo $alinti["paylasim_tarihi"]; ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-content">Henüz paylaşılan bir alıntı yok.</p>
            <?php endif; ?>
        </section>

    </div>
</main>

<?php require "footer.php"; ?>

</body>
</html>
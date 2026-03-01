<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
require "database.php";
session_start();

if (!isset($_GET["id"])) 
{
    die("Kitap bulunamadı.");
}

$id = intval($_GET["id"]);
$is_favorite=false;
$current_user_id=$_SESSION["kullanici_id"] ?? 0;
$user_rating=0; 
$user_review_text="";
$average_rating=0;
$total_reviews=0;

$sorgu = $baglanti->prepare("SELECT * FROM kitaplar WHERE id=?");
$sorgu->bind_param("i", $id);
$sorgu->execute();
$kitap = $sorgu->get_result()->fetch_assoc();

if (!$kitap) {
    die("Kitap bulunamadı.");
}

$rating_sorgu = $baglanti->prepare("SELECT AVG(puan) as avg_puan, COUNT(id) as total_puan FROM kitap_puanlari WHERE kitap_id = ?");
$rating_sorgu->bind_param("i", $id);
$rating_sorgu->execute();
$rating_sonuc = $rating_sorgu->get_result()->fetch_assoc();

if ($rating_sonuc && $rating_sonuc['total_puan'] > 0) 
{
    $average_rating = round($rating_sonuc['avg_puan'], 2);
    $total_reviews = $rating_sonuc['total_puan'];
}

$all_reviews_sorgu = $baglanti->
prepare("
    SELECT kimm.*, kp.puan, k.kullanici_adi 
    FROM kitap_incelemeleri_metin kimm
    JOIN kullanicilar k ON k.id = kimm.kullanici_id
    LEFT JOIN kitap_puanlari kp ON kp.kitap_id = kimm.kitap_id AND kp.kullanici_id = kimm.kullanici_id
    WHERE kimm.kitap_id=?
    ORDER BY kimm.inceleme_tarihi DESC
");
$all_reviews_sorgu->bind_param("i", $id);
$all_reviews_sorgu->execute();
$all_reviews = $all_reviews_sorgu->get_result();



if ($current_user_id != 0) 
{
    
    $fav_kontrol = $baglanti->prepare("SELECT id FROM favoriler WHERE kullanici_id = ? AND kitap_id = ?");
    $fav_kontrol->bind_param("ii", $current_user_id, $id);
    $fav_kontrol->execute();
    if ($fav_kontrol->get_result()->num_rows > 0) 
    {
        $is_favorite = true;
    }
    
    $user_rate_sorgu = $baglanti->prepare("SELECT puan FROM kitap_puanlari WHERE kullanici_id = ? AND kitap_id = ?");
    $user_rate_sorgu->bind_param("ii", $current_user_id, $id);
    $user_rate_sorgu->execute();
    $user_rate_sonuc = $user_rate_sorgu->get_result()->fetch_assoc();
    if ($user_rate_sonuc) 
    {
        $user_rating = $user_rate_sonuc['puan'];
    }

    $user_review_sorgu = $baglanti->prepare("SELECT inceleme_metni FROM kitap_incelemeleri_metin WHERE kullanici_id = ? AND kitap_id = ?");
    $user_review_sorgu->bind_param("ii", $current_user_id, $id);
    $user_review_sorgu->execute();
    $user_review_sonuc = $user_review_sorgu->get_result()->fetch_assoc();
    if ($user_review_sonuc) 
    {
        $user_review_text=$user_review_sonuc['inceleme_metni'];
    }

    $liste_sorgu = $baglanti->prepare("SELECT id, liste_adi FROM okuma_listeleri WHERE kullanici_id = ? ORDER BY liste_adi ASC");
    $liste_sorgu->bind_param("i", $current_user_id);
    $liste_sorgu->execute();
    $listeler = $liste_sorgu->get_result();

    $kitap_listelerinde=[];
    if ($listeler->num_rows > 0) 
        {
        $kitap_liste_kontrol = $baglanti->prepare("SELECT liste_id FROM liste_kitaplari WHERE kitap_id = ?");
        $kitap_liste_kontrol->bind_param("i", $id);
        $kitap_liste_kontrol->execute();
        $sonuclar = $kitap_liste_kontrol->get_result();
        while($r = $sonuclar->fetch_assoc())
        {
            $kitap_listelerinde[] = $r['liste_id'];
        }
    }
}

$alinti_sorgu = $baglanti->
prepare("
    SELECT a.*, k.kullanici_adi 
    FROM alintilar a
    JOIN kullanicilar k ON k.id = a.kullanici_id
    WHERE kitap_id=?
    ORDER BY paylasim_tarihi DESC");

$alinti_sorgu->bind_param("i", $id);
$alinti_sorgu->execute();
$alintilar = $alinti_sorgu->get_result();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title><?php echo $kitap["kitap_adi"]; ?> | BOOKAN</title>
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/kitap_detay.css">
</head>
<body>
<?php require "header.php"; ?>
<main class="main-wrapper">
  <div class="container book-detail-wrapper">
    
    <?php if (isset($_SESSION['list_msg'])): ?>
        <div class='success-msg' style="margin-bottom: 20px;"><?php echo $_SESSION['list_msg']; ?></div>
        <?php unset($_SESSION['list_msg']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['list_error'])): ?>
        <div class='error-msg' style="margin-bottom: 20px;"><?php echo $_SESSION['list_error']; ?></div>
        <?php unset($_SESSION['list_error']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['inceleme_msg'])): ?>
        <?php echo $_SESSION['inceleme_msg']; ?>
        <?php unset($_SESSION['inceleme_msg']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['rate_msg'])): ?>
        <?php echo $_SESSION['rate_msg']; ?>
        <?php unset($_SESSION['rate_msg']); ?>
    <?php endif; ?>

    <div class="book-detail-top">
      <div class="book-cover">
        <?php if ($kitap["kapak_resmi"]): ?>
          <img src="uploads/<?php echo $kitap['kapak_resmi']; ?>" alt="Kapak">
        <?php else: ?>
          <img src="assets/images/book_placeholder.png" alt="Kapak">
        <?php endif; ?>
      </div>

      <div class="book-info">
        <h1><?php echo $kitap["kitap_adi"]; ?></h1>
        <h3><?php echo $kitap["yazar"]; ?></h3>

        <div class="rating-info">
            <?php if ($total_reviews > 0): ?>
                <span class="rating-stars">
                    <?php 
                    for ($i = 1; $i <= 5; $i++): 
                        if ($i <= floor($average_rating)): ?>
                            <span style="color: gold;">★</span>
                        <?php else: ?>
                            <span style="color: #ccc;">★</span>
                        <?php endif;
                    endfor; 
                    ?>
                </span>
                <?php echo $average_rating; ?> / 5 (<?php echo $total_reviews; ?> Puan)
            <?php else: ?>
                Henüz puanlama yapılmamış. İlk puanı sen ver!
            <?php endif; ?>
        </div>
        <?php if ($current_user_id != 0): ?>
            <div class="actions">
                <a href="alinti_ekle.php?kitap_id=<?php echo $kitap['id']; ?>" class="action-btn primary">
                    ✏️ Alıntı Paylaş
                </a>

                <a href="favorite_action.php?kitap_id=<?php echo $kitap['id']; ?>" 
                   class="action-btn <?= $is_favorite ? 'favorite-active' : 'favorite-passive' ?>">
                    <?= $is_favorite ? '⭐️ Favoride (Çıkar)' : '⭐ Favorilere Ekle' ?>
                </a>

                <div class="action-dropdown">
                    <button class="action-btn" onclick="toggleDropdown(event, 'listDropdown')">
                        + Listeye Ekle
                    </button>
                    
                    <div id="listDropdown" class="dropdown-content">
                        <?php $listeler->data_seek(0); ?>
                        <?php while($liste = $listeler->fetch_assoc()): 
                            $in_list = in_array($liste['id'], $kitap_listelerinde);
                        ?>
                            <div class="dropdown-item <?= $in_list ? 'in-list' : '' ?>">
                                <span><?= $liste['liste_adi'] ?></span>
                                
                                <form action="list_action.php" method="POST">
                                    <input type="hidden" name="kitap_id" value="<?= $kitap['id'] ?>">
                                    <input type="hidden" name="liste_id" value="<?= $liste['id'] ?>">
                                    <input type="hidden" name="action" value="<?= $in_list ? 'remove' : 'add' ?>">
                                    <button type="submit">
                                        <?= $in_list ? 'Çıkar' : 'Ekle' ?>
                                    </button>
                                </form>
                            </div>
                        <?php endwhile; ?>
                        
                        <a href="liste_olustur.php" class="dropdown-item" style="border-top: 1px solid var(--c5); color: var(--c1); font-weight: 700;">
                            ✨ Yeni Liste Oluştur
                        </a>
                    </div>
                </div>
            </div>
            
            <form action="rate_action.php" method="POST" class="user-rating-form" style="margin-bottom: 20px;">
                <input type="hidden" name="kitap_id" value="<?php echo $kitap['id']; ?>">
                
                <label>Puanım:</label>
                <div class="star-input-group">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="rate-star<?php echo $i; ?>" name="puan" value="<?php echo $i; ?>" 
                                <?php echo ($user_rating == $i) ? 'checked' : ''; ?> required>
                        <label for="rate-star<?php echo $i; ?>" title="<?php echo $i; ?> yıldız"></label>
                    <?php endfor; ?>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 8px 12px; font-size: 14px;">
                    <?= $user_rating ? 'Puanı Güncelle' : 'Puanla' ?>
                </button>
            </form>
            <?php endif; ?>
        <p><?php echo nl2br($kitap["aciklama"]); ?></p>
      </div>

    </div>
    
    <section class="reviews-section">
        <h2 class="quotes-title">📝 Kitap İncelemeleri (<?php echo $all_reviews->num_rows; ?>)</h2>
        
        <?php if ($current_user_id != 0): ?>
            <div class="review-form-wrapper">
                <h3><?= $user_review_text ? 'İncelemeni Güncelle' : 'Bu Kitabı İncele' ?></h3>
                <form action="inceleme_action.php" method="POST">
                    <input type="hidden" name="kitap_id" value="<?php echo $kitap['id']; ?>">
                    
                    <textarea name="inceleme_metni" placeholder="Bu kitap hakkındaki detaylı görüşlerini yaz..." required><?= htmlspecialchars($user_review_text) ?></textarea>
                    
                    <div class="review-form-actions" style="justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 18px; font-size: 15px;">
                            <?= $user_review_text ? 'İncelemeyi Güncelle' : 'Gönder' ?>
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class='error-msg' style="margin-bottom: 20px; text-align: center; border-color: var(--c3); background: var(--c5); color: var(--c1);">
                İnceleme yapmak ve puan vermek için <a href="login.php" style="color: var(--c2); font-weight: bold;">Giriş Yap</a>.
            </div>
        <?php endif; ?>
        
        <?php if ($all_reviews->num_rows > 0): ?>
            <div class="reviews-list">
                <?php while($inc = $all_reviews->fetch_assoc()): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-user-info">
                                İnceleyen: <a href="profil.php?id=<?php echo $inc['kullanici_id']; ?>"><?php echo htmlspecialchars($inc['kullanici_adi']); ?></a>
                            </div>
                            <div class="review-rating-stars">
                                <?php 
                                $inceleme_puan = $inc['puan'] ?? 0;
                                for ($i = 1; $i <= 5; $i++): ?>
                                    <span style="color: <?= ($i <= $inceleme_puan) ? 'gold' : '#ccc' ?>;">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="review-text"><?php echo nl2br(htmlspecialchars($inc['inceleme_metni'])); ?></p>
                        <small class="review-date"><?php echo $inc['inceleme_tarihi']; ?></small>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="no-content">Bu kitaba henüz hiç detaylı inceleme yapılmamış.</p>
        <?php endif; ?>

    </section>
    
    <h2 class="quotes-title" style="margin-top: 50px;">Bu Kitaptan Alıntılar</h2>
    
    <div class="quotes-list">
        <?php if ($alintilar->num_rows == 0): ?>
            <p>Bu kitaba ait hiç alıntı yok.</p>
        <?php endif; ?>

        <?php while($a = $alintilar->fetch_assoc()): ?>
            <div class="quote-card">
                <p>"<?php echo $a["alinti_metni"]; ?>"</p>
                <small>
                    — <?php echo $a["kullanici_adi"]; ?> • <?php echo $a["paylasim_tarihi"]; ?>
                </small>
            </div>
        <?php endwhile; ?>
    </div>
  </div>
</main>
<?php require "footer.php"; ?>

<!-- tek js kodum o yüzen js dosyası oluşturmadım. -->
<script>
    function toggleDropdown(event, id) 
    {
        event.stopPropagation();
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('show');
    }
    document.addEventListener('click', function (event) 
    {
        const dropdowns = document.querySelectorAll('.dropdown-content');
        dropdowns.forEach(dropdown => 
        {
            if (dropdown.classList.contains('show') && !dropdown.contains(event.target) && !event.target.closest('.action-dropdown')) 
            {
                dropdown.classList.remove('show');
            }
        });
    });
</script>
</body>
</html>
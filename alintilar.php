<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
require "database.php";
session_start();


if ($_SERVER["REQUEST_METHOD"]==="POST"&&isset($_POST["yorum_ekle"])) 
{
    if (!isset($_SESSION["kullanici_id"])) 
    {
        header("Location: login.php");
        exit();
    }
    $kullanici_id=$_SESSION["kullanici_id"];
    $alinti_id=intval($_POST["alinti_id"]);
    $yorum_metni=trim($_POST["yorum_metni"]);

    if ($yorum_metni!=="") 
        {
        $yorum_ekle = $baglanti->prepare("INSERT INTO yorumlar (kullanici_id, alinti_id, yorum_metni) VALUES (?,?,?)");
        $yorum_ekle->bind_param("iis", $kullanici_id,$alinti_id,$yorum_metni);
        $yorum_ekle->execute();
    }
    header("Location: alintilar.php");
    exit();
}

$current_user_id = $_SESSION["kullanici_id"] ?? 0;

$alinti_sorgu=$baglanti->
query("
  SELECT a.*, k.kullanici_adi,k.profil_resmi, kt.kitap_adi,kt.id AS kitap_id, kt.kapak_resmi,a.begeni_sayisi FROM alintilar a
  JOIN kullanicilar k ON k.id=a.kullanici_id
  JOIN kitaplar kt ON kt.id=a.kitap_id
  ORDER BY a.paylasim_tarihi DESC");

function isLiked($baglanti,$kullanici_id,$alinti_id) 
{
    if ($kullanici_id==0) return false;
    $sorgu=$baglanti->prepare("SELECT id FROM begeniler WHERE kullanici_id = ? AND alinti_id = ?");
    $sorgu->bind_param("ii",$kullanici_id,$alinti_id);
    $sorgu->execute();
    return $sorgu->get_result()->num_rows>0;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Alıntılar | BOOKAN</title>
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/alintilar.css">
</head>
<body>
<?php require "header.php";?>
<main class="main-wrapper">
  <div class="container quotes-wrapper"> 
    <h2 class="quotes-title">Alıntılar</h2>
    <div class="quotes-feed">
      <?php while($a = $alinti_sorgu->fetch_assoc()):?>
        <?php
          $is_liked = isLiked($baglanti,$current_user_id, $a['id']);
          $yorum_sorgu = $baglanti->
          prepare("
            SELECT y.*, k.kullanici_adi FROM yorumlar y
            JOIN kullanicilar k ON k.id = y.kullanici_id
            WHERE y.alinti_id = ?
            ORDER BY y.yorum_tarihi ASC");
          $yorum_sorgu->bind_param("i",$a["id"]);
          $yorum_sorgu->execute();
          $yorumlar=$yorum_sorgu->get_result();?>

        <div class="quote-card" id="alinti-<?php echo $a['id']; ?>">
            <div class="quote-header">
                <div class="quote-user-img">
                    <a href="profil.php?id=<?php echo $a['kullanici_id']; ?>">
                         <img src="uploads/avatars/<?php echo htmlspecialchars($a['profil_resmi'] ?: 'default_avatar.png'); ?>"alt="Avatar" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                    </a>
                </div>
                <div class="quote-user-info">
                    <h4>
                        <a href="profil.php?id=<?php echo $a['kullanici_id'];?>">
                            <?php echo $a["kullanici_adi"];?>
                        </a>
                    </h4>
                    <p>
                        Kitap: <a href="kitap_detay.php?id=<?php echo $a['kitap_id']; ?>" style="color:var(--c2);font-weight:600;">
                                <?php echo $a["kitap_adi"]; ?></a>
                    </p>
                </div>
            </div>

            <div class="quote-content-wrapper">
                <?php if (!empty($a["kapak_resmi"])): ?>
                    <div class="quote-book-cover-small">
                        <a href="uploads/<?php echo $a['kapak_resmi']; ?>" target="_blank" title="Büyük halini görmek için tıklayın.">
                            <img src="uploads/<?php echo $a['kapak_resmi']; ?>" alt="Kitap Kapak">
                        </a>
                    </div>
                <?php endif;?>

                <div class="quote-text">
                    “<?php echo nl2br(htmlspecialchars($a["alinti_metni"]));?>”
                </div>
            </div>
            <div class="quote-date">
                <span style="float: left; color: var(--c2); font-weight: 600;">
                    👍 <?php echo $a["begeni_sayisi"] ?? 0; ?> Beğeni
                </span>
                <?php echo $a["paylasim_tarihi"]; ?>
                
                <?php if ($current_user_id != 0): ?>
                <a href="alinti_like_action.php?alinti_id=<?php echo $a['id']; ?>&anchor=alinti-<?php echo $a['id']; ?>"
                       class="btn <?= $is_liked ? 'btn-outline' : 'btn-primary' ?>"
                       style="padding: 5px 10px; font-size: 13px; border-radius: 8px; margin-left: 10px; float: right;">
                        <?= $is_liked ? 'Beğeniyi Geri Al':'Beğen'?>
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="comments">
                <?php if ($yorumlar->num_rows > 0): ?>
                    <?php while($y = $yorumlar->fetch_assoc()): ?>
                        <div class="comment">
                            <strong><?php echo $y["kullanici_adi"]; ?>:</strong>
                            <?php echo htmlspecialchars($y["yorum_metni"]); ?>
                            <small>(<?php echo $y["yorum_tarihi"]; ?>)</small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="comment" style="color:#999;">Henüz yorum yok.</div>
                <?php endif; ?>

                <?php if (isset($_SESSION["kullanici_id"])):?>
                    <form method="POST" class="comment-form">
                        <input type="hidden" name="alinti_id" value="<?php echo $a['id'];?>">
                        <textarea name="yorum_metni" placeholder="Yorum yaz..."required></textarea>
                        <button type="submit" name="yorum_ekle">Yorum Yap</button>
                    </form>
                <?php else:?>
                    <div class="login-to-comment">
                        Yorum yapmak için <a href="login.php">giriş yap</a>.
                    </div>
                <?php endif; ?>
            </div>
        </div>
      <?php endwhile; ?>
    </div>
    </div>
</main>
<?php require "footer.php"; ?>
<?php if (isset($_SESSION["kullanici_id"])): ?>
    <a href="alinti_ekle.php" class="fab-btn" title="Yeni Alıntı Ekle">+</a>
<?php endif; ?>
</body>
</html>
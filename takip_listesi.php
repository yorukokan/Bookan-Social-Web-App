<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
require "database.php";
session_start();

$kullanici_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$type = isset($_GET["type"]) ? $_GET["type"] : "";
$current_user_id = $_SESSION["kullanici_id"] ?? 0;

if ($kullanici_id == 0 || ($type !== "following" && $type !== "followers")) 
{
    header("Location: index.php");
    exit();
}


$profil_sorgu = $baglanti->prepare("SELECT kullanici_adi FROM kullanicilar WHERE id = ?");
$profil_sorgu->bind_param("i", $kullanici_id);
$profil_sorgu->execute();
$profil_kullanici = $profil_sorgu->get_result()->fetch_assoc();

if (!$profil_kullanici) 
{
    die("Kullanıcı bulunamadı.");
}

$kullanici_adi = $profil_kullanici["kullanici_adi"];

if ($type === "following") 
{
    $baslik = $kullanici_adi . " adlı kişinin Takip Ettikleri";
    $sorgu_metni = "
        SELECT u.id, u.kullanici_adi, u.profil_resmi 
        FROM takip t 
        JOIN kullanicilar u ON u.id = t.takip_edilen_id 
        WHERE t.takip_eden_id = ?
        ORDER BY u.kullanici_adi ASC
    ";
} 
else 
{ 
    $baslik = $kullanici_adi . " adlı kişinin Takipçileri";
    $sorgu_metni = "
        SELECT u.id, u.kullanici_adi, u.profil_resmi
        FROM takip t 
        JOIN kullanicilar u ON u.id = t.takip_eden_id 
        WHERE t.takip_edilen_id = ?
        ORDER BY u.kullanici_adi ASC
    ";
}

$liste_sorgu = $baglanti->prepare($sorgu_metni);
$liste_sorgu->bind_param("i", $kullanici_id);
$liste_sorgu->execute();
$liste_sonucu = $liste_sorgu->get_result();

function isFollowing($baglanti, $takip_eden_id, $takip_edilen_id)
{
    if ($takip_eden_id == 0) return false;
    $kontrol = $baglanti->prepare("SELECT id FROM takip WHERE takip_eden_id = ? AND takip_edilen_id = ?");
    $kontrol->bind_param("ii", $takip_eden_id, $takip_edilen_id);
    $kontrol->execute();
    return $kontrol->get_result()->num_rows > 0;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title><?php echo $baslik; ?> | BOOKAN</title>

  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/profil.css"> 
  </head>
<body>

<?php require "header.php"; ?>
<main class="main-wrapper">
  <div class="container user-list-wrapper">
    <h2 class="section-title" style="margin-bottom: 30px;">👥 <?php echo $baslik; ?></h2>
    <div class="user-list-grid"> 
    <?php if ($liste_sonucu->num_rows > 0): ?>
      <?php while($user = $liste_sonucu->fetch_assoc()): ?>
        <?php 
            $is_following_user = isFollowing($baglanti, $current_user_id, $user['id']);
            $is_self = ($current_user_id == $user['id']);
        ?>
        <div class="user-card card">
          <a href="profil.php?id=<?php echo $user['id']; ?>" class="user-info-wrapper">
            <img src="uploads/avatars/<?php echo htmlspecialchars($user['profil_resmi'] ?: 'default_avatar.png'); ?>" 
                 alt="Avatar" 
                 class="user-avatar-large">
            <span class="username-large">
              <?php echo htmlspecialchars($user['kullanici_adi']); ?>
            </span>
          </a>
          <?php if ($current_user_id != 0 && !$is_self): ?>
            <form action="follow_action.php?id=<?php echo $user['id']; ?>" method="POST" style="margin-left: auto;">
                <button type="submit" 
                        class="btn <?php echo $is_following_user ? 'btn-outline' : 'btn-primary'; ?>"
                        style="min-width: 120px;">
                    <?php echo $is_following_user ? 'Takibi Bırak' : 'Takip Et'; ?>
                </button>
            </form>
          <?php elseif ($is_self): ?>
            <a href="profil.php" class="btn btn-outline" style="min-width: 120px; margin-left: auto;">Profilim</a>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
    <?php else: ?>
      <p class="no-content">Bu listede henüz kimse yok.</p>
    <?php endif; ?>
  </div>
</main>
<?php require "footer.php"; ?>
</body>
</html>
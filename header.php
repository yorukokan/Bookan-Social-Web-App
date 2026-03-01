<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
if (session_status()===PHP_SESSION_NONE) 
{
    session_start();
}

?>
<header class="site-header">
  <div class="container site-header-inner">
    <div class="brand">BOOKAN</div>
    <form action="search.php" method="GET" class="search-form">
        <input type="text" name="q" placeholder="Kitap,yazar veya kullanıcı ara..." class="search-input" required
               value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']):'' ?>">
        <button type="submit" class="search-btn">🔍</button>
    </form>
    <nav class="nav-links">
      <a href="index.php">Ana Sayfa</a>
      <a href="kitaplar.php">Kitaplar</a>
      <a href="alintilar.php">Alıntılar</a>
      <?php if (isset($_SESSION["kullanici_id"])):?>
        <a href="profil.php">Profil</a>
        <a href="logout.php">Çıkış</a>
      <?php else:?>
        <a href="login.php">Giriş Yap</a>
        <a href="register.php">Kayıt Ol</a>
      <?php endif;?>
    </nav>
  </div>
</header>
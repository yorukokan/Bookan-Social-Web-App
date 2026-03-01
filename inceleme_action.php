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

$kullanici_id = $_SESSION["kullanici_id"];
$kitap_id = isset($_POST["kitap_id"])?intval($_POST["kitap_id"]):0;
$inceleme_metni = trim($_POST["inceleme_metni"]);

if ($kitap_id<=0||empty($inceleme_metni)) 
{
    $_SESSION['inceleme_msg'] = "<div class='error-msg'>Hata: Kitap inceleme Metni boş bırakılamaz.</div>";
    header("Location: kitap_detay.php?id=".$kitap_id);
    exit();
}

$sorgu_metin = $baglanti->
prepare("
INSERT INTO kitap_incelemeleri_metin (kitap_id,kullanici_id,inceleme_metni) 
    VALUES (?,?,?)
    ON DUPLICATE KEY UPDATE inceleme_metni=VALUES(inceleme_metni)");
$sorgu_metin->bind_param("iis", $kitap_id, $kullanici_id, $inceleme_metni);
$sorgu_metin->execute();

$_SESSION['inceleme_msg']="<div class='success-msg'>Kitap inceleme metniniz başarıyla kaydedildi!</div>";

header("Location: kitap_detay.php?id=".$kitap_id);
exit();
?>
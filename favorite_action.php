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

$kullanici_id=$_SESSION["kullanici_id"];
$kitap_id=isset($_GET["kitap_id"]) ? intval($_GET["kitap_id"]):0;

if ($kitap_id <= 0) 
{
    header("Location: kitaplar.php");
    exit();
}

$kontrol = $baglanti->prepare("SELECT id FROM favoriler WHERE kullanici_id=? AND kitap_id=?");
$kontrol->bind_param("ii",$kullanici_id,$kitap_id);
$kontrol->execute();
$sonuc = $kontrol->get_result();

if ($sonuc->num_rows>0) 
{
    $cikar = $baglanti->prepare("DELETE FROM favoriler WHERE kullanici_id = ? AND kitap_id = ?");
    $cikar->bind_param("ii", $kullanici_id, $kitap_id);
    $cikar->execute();
} 
else 
{
    $ekle = $baglanti->prepare("INSERT INTO favoriler (kullanici_id, kitap_id) VALUES (?, ?)");
    $ekle->bind_param("ii", $kullanici_id, $kitap_id);
    $ekle->execute();
}

header("Location: kitap_detay.php?id=" . $kitap_id);
exit();
?>
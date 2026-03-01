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
$alinti_id = isset($_GET["alinti_id"]) ? intval($_GET["alinti_id"]):0;
$anchor = isset($_GET["anchor"]) ? $_GET["anchor"] : ''; 

if ($alinti_id<=0) 
{
    header("Location: alintilar.php");
    exit();
}

$kontrol = $baglanti->prepare("SELECT id FROM begeniler WHERE kullanici_id = ? AND alinti_id = ?");
$kontrol->bind_param("ii",$kullanici_id,$alinti_id);
$kontrol->execute();
$sonuc = $kontrol->get_result();

if ($sonuc->num_rows>0) 
{
    $baglanti->begin_transaction();
    $cikar = $baglanti->prepare("DELETE FROM begeniler WHERE kullanici_id = ? AND alinti_id = ?");
    $cikar->bind_param("ii",$kullanici_id,$alinti_id);
    $cikar->execute();

    $guncelle = $baglanti->prepare("UPDATE alintilar SET begeni_sayisi=begeni_sayisi-1 WHERE id=? AND begeni_sayisi>0");
    $guncelle->bind_param("i",$alinti_id);
    $guncelle->execute();
    $baglanti->commit();
} 
else 
{
    $baglanti->begin_transaction();
    $ekle = $baglanti->prepare("INSERT INTO begeniler (kullanici_id, alinti_id) VALUES (?,?)");
    $ekle->bind_param("ii", $kullanici_id, $alinti_id);
    $ekle->execute();

    $guncelle = $baglanti->prepare("UPDATE alintilar SET begeni_sayisi = begeni_sayisi + 1 WHERE id = ?");
    $guncelle->bind_param("i", $alinti_id);
    $guncelle->execute();
    $baglanti->commit();
}

header("Location: alintilar.php#".$anchor); 
exit();
?>
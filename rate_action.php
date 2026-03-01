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
$kitap_id=isset($_POST["kitap_id"]) ? intval($_POST["kitap_id"]) : 0;
$puan=isset($_POST["puan"]) ? intval($_POST["puan"]) : 0;

if ($kitap_id<=0 || $puan < 1 || $puan > 5) 
{
    header("Location: kitaplar.php");
    exit();
}

$kontrol = $baglanti->prepare("SELECT id FROM kitap_puanlari WHERE kullanici_id = ? AND kitap_id = ?");
$kontrol->bind_param("ii", $kullanici_id, $kitap_id);
$kontrol->execute();
$sonuc = $kontrol->get_result();

if ($sonuc->num_rows > 0) 
{
    $guncelle = $baglanti->prepare("UPDATE kitap_puanlari SET puan = ? WHERE kullanici_id = ? AND kitap_id = ?");
    $guncelle->bind_param("iii", $puan, $kullanici_id, $kitap_id);
    $guncelle->execute();
    $_SESSION['rate_msg'] = "Puanınız başarıyla güncellendi!";
} 
else 
{
    $ekle = $baglanti->prepare("INSERT INTO kitap_puanlari (kitap_id, kullanici_id, puan) VALUES (?, ?, ?)");
    $ekle->bind_param("iii", $kitap_id, $kullanici_id, $puan);
    $ekle->execute();
    $_SESSION['rate_msg'] = "Puanınız başarıyla kaydedildi!";
}

header("Location: kitap_detay.php?id=" . $kitap_id);
exit();
?>
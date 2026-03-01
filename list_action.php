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
$kitap_id = isset($_POST["kitap_id"]) ? intval($_POST["kitap_id"]) : 0;
$liste_id = isset($_POST["liste_id"]) ? intval($_POST["liste_id"]) : 0;
$action = isset($_POST["action"]) ? $_POST["action"] : '';

if ($kitap_id <= 0 || $liste_id <= 0 || !in_array($action, ['add', 'remove'])) 
{
    header("Location: kitap_detay.php?id=" . $kitap_id);
    exit();
}

$kontrol_liste = $baglanti->prepare("SELECT id FROM okuma_listeleri WHERE id = ? AND kullanici_id = ?");
$kontrol_liste->bind_param("ii", $liste_id, $kullanici_id);
$kontrol_liste->execute();

if ($kontrol_liste->get_result()->num_rows == 0) 
{
    header("Location: kitap_detay.php?id=" . $kitap_id);
    $_SESSION['list_error'] = "Yetkisiz işlem veya liste bulunamadı.";
    exit();
}

if ($action == 'add') 
{
    
    try 
    {
        $ekle = $baglanti->prepare("INSERT INTO liste_kitaplari (liste_id, kitap_id) VALUES (?, ?)");
        $ekle->bind_param("ii", $liste_id, $kitap_id);
        $ekle->execute();
        $_SESSION['list_msg'] = "Kitap listeye başarıyla eklendi!";
    } 
    catch (mysqli_sql_exception $e) 
    {
        $_SESSION['list_msg'] = "Kitap zaten bu listede bulunuyor.";
    }
} 
elseif ($action == 'remove') 
{
    $cikar = $baglanti->prepare("DELETE FROM liste_kitaplari WHERE liste_id = ? AND kitap_id = ?");
    $cikar->bind_param("ii", $liste_id, $kitap_id);
    $cikar->execute();
    $_SESSION['list_msg'] = "Kitap listeden başarıyla çıkarıldı!";
}

header("Location: kitap_detay.php?id=" . $kitap_id);
exit();?>
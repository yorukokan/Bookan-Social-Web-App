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

$takip_eden_id = $_SESSION["kullanici_id"];
$takip_edilen_id = isset($_GET["id"]) ? intval($_GET["id"]):0;

if ($takip_edilen_id<=0||$takip_eden_id==$takip_edilen_id) 
{
    header("Location: index.php"); 
    exit();
}

$kontrol = $baglanti->prepare("SELECT id FROM takip WHERE takip_eden_id=? AND takip_edilen_id=?");
$kontrol->bind_param("ii", $takip_eden_id, $takip_edilen_id);
$kontrol->execute();
$sonuc = $kontrol->get_result();

if ($sonuc->num_rows>0) 
{
    $birak = $baglanti->prepare("DELETE FROM takip WHERE takip_eden_id=? AND takip_edilen_id=?");
    $birak->bind_param("ii", $takip_eden_id,$takip_edilen_id);
    $birak->execute();
} 
else 
{
    $ekle = $baglanti->prepare("INSERT INTO takip (takip_eden_id, takip_edilen_id) VALUES (?, ?)");
    $ekle->bind_param("ii", $takip_eden_id,$takip_edilen_id);
    $ekle->execute();
}

header("Location: profil.php?id=" . $takip_edilen_id);
exit();
?>
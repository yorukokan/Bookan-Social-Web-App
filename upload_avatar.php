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
$upload_dir = __DIR__ . "/uploads/avatars/";
$default_avatar = "default_avatar.png";

if (!is_dir($upload_dir)) 
{
    mkdir($upload_dir, 0777, true);
}

if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] === UPLOAD_ERR_OK) 
{
    
    $file_info = pathinfo($_FILES["avatar"]["name"]);
    $file_extension = strtolower($file_info["extension"]);
    
    $allowed_extensions = ["jpg", "jpeg", "png", "webp"];
    if (!in_array($file_extension, $allowed_extensions)) 
        {
        $_SESSION["avatar_msg"] = "Hata: Yalnızca JPG, PNG veya WEBP dosyaları yüklenebilir.";
        header("Location: profil.php");
        exit();
    }
    
    $new_file_name = $kullanici_id . "_" . time() . "." . $file_extension;
    $target_file = $upload_dir . $new_file_name;

    if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) 
    {
        
        $guncelle = $baglanti->prepare("UPDATE kullanicilar SET profil_resmi = ? WHERE id = ?");
        $guncelle->bind_param("si", $new_file_name, $kullanici_id);
        $guncelle->execute();
        
        $_SESSION["avatar_msg"] = "Profil resmi başarıyla güncellendi!";
    } 
    else 
    {
        $_SESSION["avatar_msg"] = "Hata: Dosya yüklenirken bir sorun oluştu.";
    }
} 
else 
{
    $_SESSION["avatar_msg"] = "Hata: Bir dosya seçilmedi veya yükleme hatası oluştu.";
}

header("Location: profil.php");
exit();
?>
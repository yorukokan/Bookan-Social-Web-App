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

if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    
    $kitap_adi = trim($_POST["kitap_adi"]);
    $yazar = trim($_POST["yazar"]);
    $aciklama = trim($_POST["aciklama"]);
    $kitap_turu = trim($_POST["kitap_turu"]);
    

    if (empty($kitap_adi) || empty($yazar) || empty($aciklama) || empty($kitap_turu)) 
    {
        $_SESSION['kitap_ekle_hata'] = "Lütfen tüm zorunlu alanları doldurun.";
        header("Location: kitap_ekle.php");
        exit();
    }

    $kapak_resmi_yolu = null;
    if (isset($_FILES["kapak_resmi"]) && $_FILES["kapak_resmi"]["error"] === UPLOAD_ERR_OK) 
    {
        
        $izin_verilen_tipler = ['image/jpeg', 'image/png', 'image/gif'];
        $dosya_tipi = $_FILES["kapak_resmi"]["type"];
        $dosya_boyutu = $_FILES["kapak_resmi"]["size"];
        $maks_boyut = 5 * 1024 * 1024; 
        
        if (!in_array($dosya_tipi, $izin_verilen_tipler)) {
            $_SESSION['kitap_ekle_hata'] = "Sadece JPEG, PNG ve GIF formatları desteklenmektedir.";
        } 
        elseif ($dosya_boyutu > $maks_boyut) 
        {
             $_SESSION['kitap_ekle_hata'] = "Dosya boyutu 5 MB'ı geçemez.";
        } 
        else 
        {
            $temiz_ad = strtolower(str_replace([' ', 'ı', 'ş', 'ç', 'ö', 'ğ', 'ü', 'İ', 'Ş', 'Ç', 'Ö', 'Ğ', 'Ü'], ['', 'i', 's', 'c', 'o', 'g', 'u', 'i', 's', 'c', 'o', 'g', 'u'], $kitap_adi));
            $uzanti = pathinfo($_FILES["kapak_resmi"]["name"], PATHINFO_EXTENSION);
            $kapak_resmi_yolu = $temiz_ad . "_" . time() . "." . $uzanti;
            $hedef_yol = "uploads/" . $kapak_resmi_yolu;

            if (!move_uploaded_file($_FILES["kapak_resmi"]["tmp_name"], $hedef_yol)) 
            {
                $_SESSION['kitap_ekle_hata'] = "Dosya yüklenirken bir hata oluştu.";
                $kapak_resmi_yolu = null;
            }
        }
        
        if (isset($_SESSION['kitap_ekle_hata'])) 
        {
            header("Location: kitap_ekle.php");
            exit();
        }
    }

    $sorgu = $baglanti->prepare("
        INSERT INTO kitaplar (kitap_adi, yazar, aciklama, kitap_turu, kapak_resmi) 
        VALUES (?, ?, ?, ?, ?)");
    $sorgu->bind_param("sssss", $kitap_adi, $yazar, $aciklama, $kitap_turu, $kapak_resmi_yolu);

    if ($sorgu->execute()) 
        {
        $_SESSION['kitap_ekle_basari'] = "Kitap başarıyla kataloğa eklendi!";
        $yeni_kitap_id = $baglanti->insert_id;
        header("Location: kitap_detay.php?id=" . $yeni_kitap_id);
    } 
    else 
    {
        $_SESSION['kitap_ekle_hata'] = "Veritabanı hatası: Kitap eklenemedi.";
        if ($kapak_resmi_yolu && file_exists($hedef_yol)) 
        {
            unlink($hedef_yol);
        }
        header("Location: kitap_ekle.php");
    }
    exit();
} 
else 
{
    header("Location: index.php");
    exit();
}
?>
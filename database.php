<!--   Ad: Okan
    Soyad: Yörük
       No: 22100011067   -->

<?php
$host = "localhost";
$user = "root";
$pass = "PASSWORD is here";
$dbname = "bookan_db";
$baglanti = new mysqli($host, $user, $pass, $dbname);

if ($baglanti->connect_error) 
{
    die("Veritabanı hatası: " . $baglanti->connect_error);
}

$baglanti->set_charset("utf8mb4");
?>

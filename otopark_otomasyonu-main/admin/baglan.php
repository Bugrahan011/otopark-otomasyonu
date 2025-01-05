<?php 

try {
    $db = new PDO('mysql:host=localhost;dbname=otopark_otomasyon;charset=utf8', 'root', '');
    // echo 'Veri Tabanı Başarılı Bir Şekilde Oluşmuştur';
} catch (Exception $e) {
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
}

// Oturum başlatma işlemi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ob_start();

?>



<?php  
require 'baglan.php'; 
require 'mesaj.php';

// Oturumun başlatılıp başlatılmadığını kontrol et
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['giris'])) {
    $mail = $_POST['mail'];
    $sifre = $_POST['sifre'];

    // Kullanıcı doğrulama sorgusu
    $sorgu = $db->prepare('SELECT * FROM kullanici_giris WHERE mail=:mail AND sifre=:sifre');
    $sorgu->execute([
        'mail' => $mail,
        'sifre' => $sifre
    ]);
    $say = $sorgu->rowCount();

    if ($say == 1) {
        $_SESSION['mail'] = $mail; // Oturumda e-posta bilgilerini sakla

        // Başarılı mesajı göster
        echo '<h1 style="text-align:center; margin-top:45px; color:green; font-weight:bold;">Giriş işleminiz başarıyla gerçekleşti. Anasayfaya yönlendiriliyorsunuz...</h1>';

        // 2 saniye sonra anasayfaya yönlendir
        header('Refresh:2; url=anasayfa.php');
        exit;
    } else {
        // Hata mesajı göster
        echo '<h1 style="text-align:center; margin-top:45px; color:red; font-weight:bold;">Giriş başarısız. Lütfen tekrar deneyiniz.</h1>';

        // 2 saniye sonra tekrar giriş sayfasına yönlendir
        header('Refresh:2; url=index.php');
        exit;
    }
}
?>

<?php require 'header.php'; ?>
<?php date_default_timezone_set('Europe/Istanbul'); ?>

<div class="container" style="max-width: 800px; margin-top: 50px;">
    <div class="card shadow-lg p-4">
        <h2 class="text-center text-primary mb-4">Araç Çıkışı</h2>

        <form action="" method="post">
            <?php 
            // Araç bilgilerini alıyoruz
            $duzenle = $db->prepare("SELECT * FROM arac_kayit WHERE arac_id = :id");
            $duzenle->execute(['id' => (int)$_GET['id']]);
            $sonuc = $duzenle->fetch(PDO::FETCH_ASSOC);

            if ($_POST) {
                if (isset($_POST['gerigel'])) {
                    echo '<div class="alert alert-danger text-center" role="alert">
                        <strong>İŞLEM YAPMADAN GERİ DÖNÜYORSUNUZ</strong>
                    </div>';
                    header('Refresh:1; parkedenarac.php');
                    exit;
                } else {
                    // Çıkış tarihi güncelle ve park yerini boşalt
                    $cikis_tarih = date('Y-m-d H:i:s');
                    $updateQuery = $db->prepare("UPDATE arac_kayit SET arac_cikis_tarih = :cikis_tarih, park_yeri_durumu = 0 WHERE arac_id = :id");
                    $updateResult = $updateQuery->execute([
                        'cikis_tarih' => $cikis_tarih,
                        'id' => (int)$_GET['id']
                    ]);
            
                    if ($updateResult) {
                        echo '<div class="alert alert-primary text-center" role="alert">
                            <strong>ARAÇ ÇIKIŞI BAŞARILI, ANASAYFAYA YÖNLENDİRİLİYORSUNUZ</strong>
                        </div>';
                        header('Refresh:1; parkedenarac.php');
                        exit;
                    } else {
                        echo '<div class="alert alert-danger text-center" role="alert">
                            <strong>ARAÇ ÇIKIŞINDA HATA OLUŞTU!</strong>
                        </div>';
                    }
                }
            }
            ?>

            <div class="form-group">
                <label for="giris_tarih" class="font-weight-bold text-danger">Araç Giriş Tarihi:</label>
                <p id="giris_tarih" class="form-control-plaintext"><?php echo $sonuc['arac_giris_tarih']; ?></p>
            </div>

            <div class="form-group">
                <label for="cikis_tarih" class="font-weight-bold text-danger">Araç Çıkış Tarihi:</label>
                <input type="text" id="cikis_tarih" class="form-control" value="<?php echo date('d-m-Y H:i:s'); ?>" readonly>
                <!-- Çıkış tarihi alanını sadece bilgi amaçlı gösteriyoruz, veritabanına formdan gitmeyecek -->
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" name="gerigel" class="btn btn-danger btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5zM10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5z"/>
                    </svg> GERİ GEL
                </button>
                <button type="submit" class="btn btn-primary btn-lg" name="kaydet">
                    KAYDET
                </button>
            </div>
        </form>
    </div>
</div>

<?php require 'footer.php'; ?>

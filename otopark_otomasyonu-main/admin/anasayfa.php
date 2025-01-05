<?php
require 'header.php';
require 'baglan.php';

// Araç türlerini çekme
$aracTurSorgu = $db->query('SELECT * FROM arac_tur');
$aracTurleri = $aracTurSorgu->fetchAll(PDO::FETCH_ASSOC);

// Araç kaydetme işlemi
$message = ''; // Mesajı tutacak değişken
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plaka = $_POST['plaka'];
    $kat = $_POST['kat'];
    $blok = $_POST['blok'];
    $parkYeri = $_POST['park_yeri'];
    $aracTur = $_POST['arac_tur'];

    // Plaka kontrolü: Çıkış yapılmamışsa aynı plaka ile tekrar giriş yapmayı engelle
    $plakaKontrolSorgu = $db->prepare('SELECT * FROM arac_kayit WHERE arac_plaka = :plaka AND arac_cikis_tarih IS NULL');
    $plakaKontrolSorgu->execute(['plaka' => $plaka]);
    $aracVarMi = $plakaKontrolSorgu->fetch();

    if ($aracVarMi) {
        echo "<script>alert('Hata: Bu plaka ile giriş yapılmış ve çıkış yapılmamıştır. Yeni giriş yapılamaz.');</script>";
    } else {
        // Araç türüne göre tur_ucret'i arac_tur tablosundan çekiyoruz
        $ucretSorgu = $db->prepare('SELECT tur_ucret FROM arac_tur WHERE tur_id = :aracTur');
        $ucretSorgu->execute(['aracTur' => $aracTur]);
        $ucretSonuc = $ucretSorgu->fetch(PDO::FETCH_ASSOC);
        $turUcret = $ucretSonuc['tur_ucret'];

        // Boş park yeri kontrolü
        $doluYerlerSorgu = $db->prepare('SELECT park_yeri FROM arac_kayit WHERE arac_kat = :kat AND arac_blok = :blok AND park_yeri = :parkYeri AND arac_cikis_tarih IS NULL');
        $doluYerlerSorgu->execute(['kat' => $kat, 'blok' => $blok, 'parkYeri' => $parkYeri]);
        $doluYerVarMi = $doluYerlerSorgu->fetch();

        if ($doluYerVarMi) {
            echo "<script>alert('Hata: $kat katındaki $blok blokunda $parkYeri numaralı park yeri dolu. Araç eklenemez!');</script>";
        } else {
            // Araç kaydetme işlemi
            $kaydet = $db->prepare('INSERT INTO arac_kayit (arac_plaka, arac_kat, arac_blok, park_yeri, arac_tur, tur_ucret, arac_giris_tarih) VALUES (:plaka, :kat, :blok, :parkYeri, :aracTur, :turUcret, NOW())');
            $kaydet->execute([
                'plaka' => $plaka,
                'kat' => $kat,
                'blok' => $blok,
                'parkYeri' => $parkYeri,
                'aracTur' => $aracTur,
                'turUcret' => $turUcret
            ]);

            // Başarılı mesajı ayarla
            $message = "Araç başarıyla kaydedildi.";

            // PRG (Post-Redirect-Get) ile sayfayı yeniden yönlendiriyoruz
            header('Location: ' . $_SERVER['PHP_SELF'] . '?message=' . urlencode($message));
            exit;
        }
    }
}

// GET isteği ile başarılı mesajı yakala
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
?>


<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Araç Kaydetme</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .card h2 {
            text-align: center;
            color: #007bff;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-control {
            border-radius: 10px;
        }
        .alert-success {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h2>Araç Kaydetme</h2>

                    <!-- Başarılı mesaj gösterimi -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post">
                        <div class="form-group">
                            <label for="plaka" class="form-label">Plaka</label>
                            <input type="text" name="plaka" class="form-control" id="plaka" onkeyup="upperPlaka()" required>
                        </div>

                        <div class="form-group">
                            <label for="kat" class="form-label">Araç Kat</label>
                            <select name="kat" id="kat" class="form-control" required>
                                <option value="">Kat Seçin</option>
                                <option value="1. Kat">1. Kat</option>
                                <option value="2. Kat">2. Kat</option>
                                <option value="3. Kat">3. Kat</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="blok" class="form-label">Araç Blok</label>
                            <select name="blok" id="blok" class="form-control" disabled required>
                                <option value="">Blok Seçin</option>
                                <option value="A BLOK">A BLOK</option>
                                <option value="B BLOK">B BLOK</option>
                                <option value="C BLOK">C BLOK</option>
                                <option value="D BLOK">D BLOK</option>
                                <option value="E BLOK">E BLOK</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="park_yeri" class="form-label">Park Yeri Numarası</label>
                            <select name="park_yeri" id="park_yeri" class="form-control" disabled required>
                                <option value="">Boş Park Yerleri</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="arac_tur" class="form-label">Araç Türü</label>
                            <select name="arac_tur" class="form-control" required>
                                <option value="">Araç Türünü Seçin</option>
                                <?php foreach ($aracTurleri as $tur): ?>
                                    <option value="<?php echo $tur['tur_id']; ?>"><?php echo $tur['tur_adi']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Araç Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Kat seçildiğinde blok seçeneği aktif olsun
        $('#kat').change(function() {
            var kat = $(this).val();
            if (kat) {
                $('#blok').prop('disabled', false); // Blok seçeneğini aktif yap
            } else {
                $('#blok').prop('disabled', true); // Eğer kat seçilmezse blok seçeneğini pasif yap
            }
        });

        // Blok seçildiğinde park yerlerini getir
        $('#blok').change(function() {
            var kat = $('#kat').val();
            var blok = $(this).val();

            if (kat && blok) {
                // Ajax ile park yerlerini çekiyoruz
                $.ajax({
                    url: 'get_park_yerleri.php',
                    type: 'POST',
                    data: {kat: kat, blok: blok},
                    success: function(response) {
                        $('#park_yeri').html(response); // Park yerlerini doldur
                        $('#park_yeri').prop('disabled', false); // Park yeri seçeneğini aktif yap
                    }
                });
            } else {
                $('#park_yeri').prop('disabled', true); // Eğer blok seçilmezse park yerleri seçeneğini pasif yap
            }
        });

        function upperPlaka(){
            
            var plaka = $('#plaka').val();
            plaka = plaka.toUpperCase(); // Büyük harfe çevirme işlemi
            $('#plaka').val(plaka);

        }
    </script>
</body>
</html>







      

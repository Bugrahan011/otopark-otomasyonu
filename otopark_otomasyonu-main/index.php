<?php 

// Veritabanı bağlantısı
try {
    $db = new PDO('mysql:host=localhost;dbname=otopark_otomasyon', 'root', '');
} catch (Exception $e) {
    echo $e->getMessage();
}

?>

<!doctype html>
<html lang="en">
  <head>
    <title>Otopark Otomasyonu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .table-wrapper {
            margin-top: 50px;
        }
        .table {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .table td {
            vertical-align: middle;
        }
        .navbar {
            margin-bottom: 50px;
        }
    </style>
  </head>
  <body>
  
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="admin/index.php">Giriş Yapmak İçin Tıklayınız</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>

  <div class="container table-wrapper">
    <h2 class="text-center text-primary mb-4">Araç Kayıtları</h2>

    <table class="table table-hover text-center">
      <thead class="thead-dark">
        <tr>
          <th scope="col">SIRA NO</th>
          <th scope="col">PLAKA</th>
          <th scope="col">ARAÇ KAT</th>
          <th scope="col">ARAÇ BLOK</th>
          <th scope="col">ARAÇ TÜRÜ</th>
          <th scope="col">ÜCRET</th>
          <th scope="col">PARK YERİ</th>
          <th scope="col">ARAÇ GİRİŞ TARİHİ</th>
          <th scope="col">ARAÇ ÇIKIŞ TARİHİ</th>
        </tr>
      </thead>
      <tbody>

      <?php  
        // `arac_kayit` ve `arac_tur` tablolarını birleştirerek veri çekiyoruz
        $kaydet = $db->query('
          SELECT ak.*, at.tur_adi, at.tur_ucret 
          FROM arac_kayit ak
          LEFT JOIN arac_tur at ON ak.arac_tur = at.tur_id
        ');

        $sira = 0;
        foreach ($kaydet as $kayit) {
          $sira++;
          $id = $kayit['arac_id'];
          $plaka = $kayit['arac_plaka'];
          $kat = $kayit['arac_kat'];
          $blok = $kayit['arac_blok'];
          $giris_tarihi = $kayit['arac_giris_tarih'];
          $cikis_tarih = $kayit['arac_cikis_tarih'];
          $turAdi = $kayit['tur_adi'];
          $turUcret = $kayit['tur_ucret'];
          $parkYeri = $kayit['park_yeri'];
      ?>
        <tr>
          <th scope="row"><?php echo $sira; ?></th>
          <td><?php echo htmlspecialchars($plaka); ?></td>
          <td><?php echo htmlspecialchars($kat); ?></td>
          <td><?php echo htmlspecialchars($blok); ?></td>
          <td><?php echo htmlspecialchars($turAdi); ?></td>
          <td><?php echo number_format($turUcret, 2) . ' TL'; ?></td>
          <td><?php echo htmlspecialchars($parkYeri); ?></td>
          <td><?php echo htmlspecialchars($giris_tarihi); ?></td>
          <td><?php echo $cikis_tarih ? htmlspecialchars($cikis_tarih) : '<span class="badge badge-danger">Henüz Çıkış Yapılmadı</span>'; ?></td>
        </tr>
      <?php } ?>

      </tbody>
    </table>
  </div>

  <!-- Optional JavaScript -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  
  </body>
</html>







   
   <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
<?php require 'header.php'; ?>

<br>

<style>
    /* Tablo Başlıkları */
    .table th {
        background-color: #3b3f44;
        color: white;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 14px;
    }

    /* Tablo Satırları */
    .table td {
        background-color: #2e3237;
        color: white;
        font-size: 13px;
        vertical-align: middle;
    }

    /* Satır Numarası Renk ve Stil */
    .table th[scope="row"] {
        background-color: #ff4757;
        color: white;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
    }

    /* Butonlar */
    .btn {
        border-radius: 20px;
        padding: 5px 15px;
        font-size: 13px;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    /* Buton Hover Efektleri */
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }
    .btn-success:hover {
        background-color: #28a745;
        border-color: #218838;
    }
    .btn-danger:hover {
        background-color: #dc3545;
        border-color: #c82333;
    }

    /* Responsive Tablo */
    @media only screen and (max-width: 768px) {
        .table th, .table td {
            font-size: 12px;
        }
        .btn {
            padding: 5px 10px;
            font-size: 12px;
        }
    }

    /* Başlıklar */
    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #007bff;
    }
</style>

<div class="container">
    <h2>Mevcut Araç Kayıtları</h2>

    <table class="table table-dark text-center">
        <thead>
            <tr>
                <th scope="col">Sıra No</th>
                <th scope="col">Plaka</th>
                <th scope="col">Araç Kat</th>
                <th scope="col">Araç Blok</th>
                <th scope="col">Araç Giriş Tarihi</th>
                <th scope="col">Araç Çıkış Tarihi</th>
                <th scope="col">Araç Türü</th>
                <th scope="col">Ücret</th>
                <th scope="col">Park Yeri</th>
                
                <th scope="col">Araç Çıkış</th>
                <th scope="col">Sil</th>
            </tr>
        </thead>

        <?php  
            // Veritabanından araç türü, ücret ve park yeri bilgilerini çekiyoruz
            $kaydet = $db->query('
                SELECT ak.*, at.tur_adi, at.tur_ucret 
                FROM arac_kayit ak
                LEFT JOIN arac_tur at ON ak.arac_tur = at.tur_id
            ');
            $sira = 0;
            foreach ($kaydet as $kayit) {
                $sira = ++ $sira;
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
        <tbody>
            <tr>
                <th scope="row"><?php echo $sira ?></th>
                <td><?php echo $plaka ?></td>
                <td><?php echo $kat ?></td>
                <td><?php echo $blok ?></td>
                <td><?php echo $giris_tarihi ?></td>
                <td>
                    <?php 
                    if ($kayit['arac_cikis_tarih'] == "") {
                        echo '<b style="color:red;">Henüz Araç Çıkış Yapmadı</b>';
                    } else {
                        echo $cikis_tarih;
                    } 
                    ?>
                </td>
                <td><?php echo htmlspecialchars($turAdi); ?></td>
                <td><?php echo number_format($turUcret, 2) . ' TL'; ?></td>
                <td><?php echo htmlspecialchars($parkYeri); ?></td>
                
                <td><a class="btn btn-success" href="araccikis.php?id=<?php echo $id ?>" role="button">Araç Çıkış</a></td>
                <td><a class="btn btn-danger" href="sil.php?id=<?php echo $id ?>" role="button">Sil<i class="bi bi-trash"></i></a></td>
            </tr>
        </tbody>
        <?php } ?>
    </table>
</div>

<?php require 'footer.php'; ?>


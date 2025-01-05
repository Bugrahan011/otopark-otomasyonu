<?php
require 'baglan.php';

if (isset($_POST['kat']) && isset($_POST['blok'])) {
    $kat = $_POST['kat'];
    $blok = $_POST['blok'];

    // Seçilen kat ve bloktaki dolu park yerlerini kontrol et (çıkış yapmamış araçlar)
    $doluYerlerSorgu = $db->prepare('SELECT park_yeri FROM arac_kayit WHERE arac_kat = :kat AND arac_blok = :blok AND arac_cikis_tarih IS NULL');
    $doluYerlerSorgu->execute(['kat' => $kat, 'blok' => $blok]);
    $doluYerler = $doluYerlerSorgu->fetchAll(PDO::FETCH_COLUMN);

    // Her blok için 1'den 30'a kadar park yerlerini göster, dolu olanları çıkar
    for ($i = 1; $i <= 30; $i++) {
        if (!in_array($i, $doluYerler)) {
            echo "<option value=\"$i\">$i</option>";
        }
    }
}

<?php  require 'header.php'; ?>

<div class="container mt-5" style="max-width: 800px;">
    <?php
    // Araç bilgilerini veritabanından çekiyoruz
    $duzenle = $db->query("SELECT * FROM arac_kayit WHERE arac_id=" . (int)$_GET['id']);
    $sonuc = $duzenle->fetch(PDO::FETCH_ASSOC);
    ?>

    <div class="card shadow-lg p-4">
        <h2 class="text-center text-primary mb-4">Araç Düzenle</h2>
        <form action="" method="post">
            <?php
            if ($_POST) {
                $plaka = $_POST['arac_plaka'];
                $kat = $_POST['arac_kat'];
                $blok = $_POST['arac_blok'];

                if (isset($_POST['gerigel'])) {
                    echo '<div class="alert alert-danger text-center" role="alert">
                        <strong>İşlem yapmadan geri dönüyorsunuz</strong>
                    </div>';
                    header('Refresh:1; parkedenarac.php');
                } elseif ($plaka != "" && $kat != "" && $blok != "") {
                    $update = $db->prepare("UPDATE arac_kayit SET arac_plaka = ?, arac_kat = ?, arac_blok = ? WHERE arac_id = ?");
                    $updateResult = $update->execute([$plaka, $kat, $blok, $_GET['id']]);
                    if ($updateResult) {
                        echo '<div class="alert alert-success text-center" role="alert">
                            <strong>İşlem başarılı, anasayfaya yönlendiriliyorsunuz.</strong>
                        </div>';
                        header('Refresh:1; parkedenarac.php');
                    } else {
                        echo '<div class="alert alert-danger text-center" role="alert">
                            <strong>İşlem başarısız oldu!</strong>
                        </div>';
                    }
                }
            }
            ?>

            <!-- Araç Plaka Girişi -->
            <div class="form-group">
                <label for="arac_plaka" class="font-weight-bold">Araç Plaka</label>
                <input type="text" name="arac_plaka" class="form-control" value="<?php echo htmlspecialchars($sonuc['arac_plaka']); ?>" required>
            </div>

            <!-- Araç Kat Seçimi -->
            <div class="form-group">
                <label for="arac_kat" class="font-weight-bold">Şuanki Bulunduğu Kat: <span class="text-danger"><?php echo htmlspecialchars($sonuc['arac_kat']); ?></span></label>
                <select name="arac_kat" class="form-control" required>
                    <option value="<?php echo htmlspecialchars($sonuc['arac_kat']); ?>">Değiştirmek İçin Seçin</option>
                    <option value="KAT 1">KAT 1</option>
                    <option value="KAT 2">KAT 2</option>
                    <option value="KAT 3">KAT 3</option>
                </select>
            </div>

            <!-- Araç Blok Seçimi -->
            <div class="form-group">
                <label for="arac_blok" class="font-weight-bold">Şuanki Bulunduğu Blok: <span class="text-danger"><?php echo htmlspecialchars($sonuc['arac_blok']); ?></span></label>
                <select name="arac_blok" class="form-control" required>
                    <option value="<?php echo htmlspecialchars($sonuc['arac_blok']); ?>">Değiştirmek İçin Seçin</option>
                    <option value="A BLOK">A BLOK</option>
                    <option value="B BLOK">B BLOK</option>
                    <option value="C BLOK">C BLOK</option>
                    <option value="D BLOK">D BLOK</option>
                    <option value="E BLOK">E BLOK</option>
                </select>
            </div>

            <!-- İşlem Butonları -->
            <div class="d-flex justify-content-between">
                <button type="submit" name="gerigel" class="btn btn-danger btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5zM10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5z"/>
                    </svg> Geri Gel
                </button>
                <button type="submit" name="kaydet" class="btn btn-primary btn-lg">
                    Kaydet
                </button>
            </div>

        </form>
    </div>
</div>

<?php require 'footer.php'; ?>

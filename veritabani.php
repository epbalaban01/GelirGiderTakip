<!--
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Yedekleme klasörünün var olup olmadığını kontrol et, yoksa oluştur
    $backupDir = 'backup';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0777, true);
    }

    $backupFile = $backupDir . '/backup_' . date('Y-m-d_H-i-s') . '.sql';
    $command = "mysqldump --user={$user} --password={$pass} --host={$host} {$db} > $backupFile";

    // Komutu çalıştır
    exec($command . ' 2>&1', $output, $result);

    if ($result == 0) {
        echo "Yedekleme başarılı! Yedek dosyası: <a href='$backupFile'>$backupFile</a>";
    } else {
        echo "Yedekleme sırasında bir hata oluştu.<br>";
        echo "Hata çıktısı:<br>";
        foreach ($output as $line) {
            echo htmlspecialchars($line) . "<br>";
        }
    }
}
?>

-->


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veritabanı Yedekleme</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="backup-container">
        <h2>Veritabanı Yedekleme</h2>
        <form action="my_backup.php" method="post" enctype="multipart/form-data">
            <button type="submit">Veritabanını Yedekle</button>
        </form>

    </div>
    <br />
    <div class="backup-container">
        <h2>Veritabanı İçe Aktarma</h2>
        <form action="my_restore.php" method="post" enctype="multipart/form-data">
            <label for="backupFile">Yedek Dosyası:</label>
            <input type="file" id="backupFile" name="backupFile" accept=".sql,.sql.gz" required>
            <p>
                <button type="submit">Geri Yükle</button>
        </form>

    </div>

    <br>
    <a href="settings.php" style="display: block; text-align: center; font-size: 18px;">Ayarlar'a Geri Dön</a>



</body>

</html>
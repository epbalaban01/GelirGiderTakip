<?php

/**
 * PHP kullanarak basit ve hızlı MySQL yedekleme / geri yükleme sistemi.
 * @author Savaş Dersim Çelik <admin@webinyo.com>
 * @version 1.0
 */

/**
 * Veritabanı parametrelerini burada tanımlayın
 */
define("DB_USER", 'root'); // Veritabanı kullanıcı adı
define("DB_PASSWORD", '123456'); // Veritabanı şifresi
define("DB_NAME", 'gelir_gider_takip'); // Veritabanı adı
define("DB_HOST", 'localhost'); // Veritabanı sunucusu
define("CHARSET", 'utf8'); // Veritabanı karakter seti

/**
 * Restore_Database sınıfı
 */
class Restore_Database
{
    // Sınıf üyeleri ve metodları burada tanımlanır
    var $host;
    var $username;
    var $passwd;
    var $dbName;
    var $charset;
    var $conn;
    var $backupDir;
    var $backupFile;

    function __construct($host, $username, $passwd, $dbName, $charset = 'utf8')
    {
        $this->host       = $host;
        $this->username   = $username;
        $this->passwd     = $passwd;
        $this->dbName     = $dbName;
        $this->charset    = $charset;
        $this->conn       = $this->initializeDatabase();
        $this->backupDir  = 'backup'; // Yüklenen dosyaların saklanacağı dizin
    }

    protected function initializeDatabase()
    {
        try {
            $conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
            if (mysqli_connect_errno()) {
                throw new Exception('Veritabanına bağlanırken bir hata oluştu: ' . mysqli_connect_error());
                die();
            }
            if (!mysqli_set_charset($conn, $this->charset)) {
                mysqli_query($conn, 'SET NAMES ' . $this->charset);
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            die();
        }

        return $conn;
    }

    public function restoreDb()
    {
        try {
            $sql = '';
            $multiLineComment = false;

            // Yedek dosyasının yolunu al
            $backupFile = $this->backupDir . '/' . $_FILES['backupFile']['name'];

            // Dosyayı yükle
            if (move_uploaded_file($_FILES['backupFile']['tmp_name'], $backupFile)) {
                $backupFileIsGzipped = substr($backupFile, -3) == '.gz';

                if ($backupFileIsGzipped) {
                    if (!$backupFile = $this->gunzipBackupFile($backupFile)) {
                        throw new Exception("HATA: yedek dosyasını gunzip yaparken bir sorun oluştu " . $backupFile);
                    }
                }

                // Yedek dosyasını satır satır oku
                $handle = fopen($backupFile, "r");
                if ($handle) {
                    while (($line = fgets($handle)) !== false) {
                        $line = ltrim(rtrim($line));
                        if (strlen($line) > 1) {
                            $lineIsComment = false;
                            if (preg_match('/^\/\*/', $line)) {
                                $multiLineComment = true;
                                $lineIsComment = true;
                            }
                            if ($multiLineComment or preg_match('/^\/\//', $line)) {
                                $lineIsComment = true;
                            }
                            if (!$lineIsComment) {
                                $sql .= $line;
                                if (preg_match('/;$/', $line)) {
                                    if (mysqli_query($this->conn, $sql)) {
                                        if (preg_match('/^CREATE TABLE `([^`]+)`/i', $sql, $tableName)) {
                                            $this->obfPrint("Tablo başarıyla oluşturuldu: `" . $tableName[1] . "`");
                                        }
                                        $sql = '';
                                    } else {
                                        throw new Exception("HATA: SQL yürütme hatası: " . mysqli_error($this->conn));
                                    }
                                }
                            } else if (preg_match('/\*\/$/', $line)) {
                                $multiLineComment = false;
                            }
                        }
                    }
                    fclose($handle);
                } else {
                    throw new Exception("HATA: yedek dosyasını açarken bir sorun oluştu " . $backupFile);
                }

                if ($backupFileIsGzipped) {
                    unlink($backupFile);
                }

                return true;
            } else {
                throw new Exception("HATA: yedek dosyasını yüklerken bir sorun oluştu.");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    protected function gunzipBackupFile($source)
    {
        $bufferSize = 4096; // 4KB
        $error = false;
        $dest = $this->backupDir . '/' . date("Ymd_His", time()) . '_' . substr($source, strrpos($source, '/') + 1, -3);

        $this->obfPrint('Yedek dosyasını gunzip yapma işlemi: ' . $source . '... ', 0, 0);

        if (file_exists($dest)) {
            if (!unlink($dest)) {
                return false;
            }
        }

        if (!$srcFile = gzopen($source, 'rb')) {
            return false;
        }
        if (!$dstFile = fopen($dest, 'wb')) {
            return false;
        }

        while (!gzeof($srcFile)) {
            if (!fwrite($dstFile, gzread($srcFile, $bufferSize))) {
                return false;
            }
        }

        fclose($dstFile);
        gzclose($srcFile);

        $this->obfPrint('Tamam', 0, 2);
        return str_replace($this->backupDir . '/', '', $dest);
    }

    public function obfPrint($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1)
    {
        if (!$msg) {
            return false;
        }

        $output = '';

        if (php_sapi_name() != "cli") {
            $lineBreak = "<br />";
        } else {
            $lineBreak = "\n";
        }

        if ($lineBreaksBefore > 0) {
            for ($i = 1; $i <= $lineBreaksBefore; $i++) {
                $output .= $lineBreak;
            }
        }

        $output .= $msg;

        if ($lineBreaksAfter > 0) {
            for ($i = 1; $i <= $lineBreaksAfter; $i++) {
                $output .= $lineBreak;
            }
        }

        if (php_sapi_name() == "cli") {
            $output .= "\n";
        }

        echo $output;

        if (php_sapi_name() != "cli") {
            ob_flush();
        }

        flush();
    }
}

/**
 * Restore_Database sınıfını oluştur ve geri yükleme işlemini gerçekleştir
 */
error_reporting(E_ALL);
set_time_limit(900); // 15 dakika

if (php_sapi_name() != "cli") {
    echo '<div style="font-family: monospace;">';
}

$restoreDatabase = new Restore_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$result = $restoreDatabase->restoreDb() ? 'OK' : 'KO';
$restoreDatabase->obfPrint("Geri yükleme sonucu: " . $result, 1);

if (php_sapi_name() != "cli") {
    echo '</div>';
}

?>
<br>
<a href="veritabani.php" style="display: block; text-align: left; font-size: 18px;">Geri Dön</a>
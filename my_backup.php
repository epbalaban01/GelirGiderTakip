<?php

/**
 * PHP kullanarak basit ve hızlı MySQL yedekleme / geri yükleme sistemi. 
 * Tam veritabanı veya belirli tabloların yedeğini alabilirsiniz.
 * @author Eyüp Can Balaban <epbalaban01@gmail.com>
 * @version 1.1
 */

/**
 * Veritabanı parametrelerini buraya tanımlayın
 */
define("DB_USER", 'root'); // Veritabanı kullanıcı adı
define("DB_PASSWORD", '123456'); // Veritabanı şifresi
define("DB_NAME", 'gelir_gider_takip'); // Yedeklenecek veritabanının adı
define("DB_HOST", 'localhost'); // Veritabanı sunucu adresi
define("BACKUP_DIR", 'backup'); // Yedekleme dosyalarının saklanacağı dizin (Boş bırakılırsa script'in bulunduğu dizin kullanılır)
define("TABLES", '*'); // Tüm veritabanı yedeği alır. Belirli tablolar için 'table1, table2, table3' gibi tanımlayın
define("CHARSET", 'utf8'); // Veritabanı karakter seti
define("GZIP_BACKUP_FILE", true);  // Yedekleme dosyasını gzip ile sıkıştır (false ise düz SQL dosyası)

class Backup_Database
{
    /**
     * Veritabanı bağlantı parametreleri
     */
    var $host;
    var $username;
    var $passwd;
    var $dbName;
    var $charset;
    var $conn;
    var $backupDir;
    var $backupFile;
    var $gzipBackupFile;
    var $output;

    /**
     * Yapıcı metod, veritabanı bağlantısını başlatır
     */
    public function __construct($host, $username, $passwd, $dbName, $charset = 'utf8')
    {
        $this->host            = $host;
        $this->username        = $username;
        $this->passwd          = $passwd;
        $this->dbName          = $dbName;
        $this->charset         = $charset;
        $this->conn            = $this->initializeDatabase();
        $this->backupDir       = BACKUP_DIR ? BACKUP_DIR : '.';
        $this->backupFile      = 'backup-' . $this->dbName . '-' . date("dmY_iHs", time()) . '.sql';
        $this->gzipBackupFile  = defined('GZIP_BACKUP_FILE') ? GZIP_BACKUP_FILE : true;
        $this->output          = '';
    }

    /**
     * Veritabanı bağlantısını başlatır
     */
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

    /**
     * Veritabanının tamamını veya belirli tabloları yedekler
     * @param string $tables Yedeklenecek tablolar
     */
    public function backupTables($tables = '*')
    {
        try {
            if ($tables == '*') {
                $tables = array();
                $result = mysqli_query($this->conn, 'SHOW TABLES');
                while ($row = mysqli_fetch_row($result)) {
                    $tables[] = $row[0];
                }
            } else {
                $tables = is_array($tables) ? $tables : explode(',', str_replace(' ', '', $tables));
            }

            $sql = 'CREATE DATABASE IF NOT EXISTS `' . $this->dbName . "`;\n\n";
            $sql .= 'USE `' . $this->dbName . "`;\n\n";

            foreach ($tables as $table) {
                $this->obfPrint("Yedekleniyor `" . $table . "` tablosu..." . str_repeat('.', 50 - strlen($table)), 0, 0);

                $sql .= 'DROP TABLE IF EXISTS `' . $table . '`;';
                $row = mysqli_fetch_row(mysqli_query($this->conn, 'SHOW CREATE TABLE `' . $table . '`'));
                $sql .= "\n\n" . $row[1] . ";\n\n";

                $row = mysqli_fetch_row(mysqli_query($this->conn, 'SELECT COUNT(*) FROM `' . $table . '`'));
                $numRows = $row[0];

                $batchSize = 1000; // Satır başına batch boyutu
                $numBatches = intval($numRows / $batchSize) + 1;
                for ($b = 1; $b <= $numBatches; $b++) {
                    $query = 'SELECT * FROM `' . $table . '` LIMIT ' . ($b * $batchSize - $batchSize) . ',' . $batchSize;
                    $result = mysqli_query($this->conn, $query);
                    $numFields = mysqli_num_fields($result);

                    for ($i = 0; $i < $numFields; $i++) {
                        $rowCount = 0;
                        while ($row = mysqli_fetch_row($result)) {
                            $sql .= 'INSERT INTO `' . $table . '` VALUES(';
                            for ($j = 0; $j < $numFields; $j++) {
                                if (isset($row[$j])) {
                                    $row[$j] = addslashes($row[$j]);
                                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                                    $sql .= '"' . $row[$j] . '"';
                                } else {
                                    $sql .= 'NULL';
                                }

                                if ($j < ($numFields - 1)) {
                                    $sql .= ',';
                                }
                            }

                            $sql .= ");\n";
                        }
                    }

                    $this->saveFile($sql);
                    $sql = '';
                }

                $sql .= "\n\n\n";

                $this->obfPrint(" OK");
            }

            if ($this->gzipBackupFile) {
                $this->gzipBackupFile();
            } else {
                $this->obfPrint('Yedekleme dosyası başarıyla kaydedildi: ' . $this->backupDir . '/' . $this->backupFile, 1, 1);
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * SQL verilerini dosyaya kaydeder
     * @param string $sql SQL verileri
     */
    protected function saveFile(&$sql)
    {
        if (!$sql) return false;

        try {
            if (!file_exists($this->backupDir)) {
                mkdir($this->backupDir, 0777, true);
            }

            file_put_contents($this->backupDir . '/' . $this->backupFile, $sql, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            print_r($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Gzip ile yedekleme dosyasını sıkıştırır
     * @param integer $level GZIP sıkıştırma seviyesi (varsayılan: 9)
     * @return string Yeni dosya adı ('.gz' eklenmiş) veya işlem başarısızsa false
     */
    protected function gzipBackupFile($level = 9)
    {
        if (!$this->gzipBackupFile) {
            return true;
        }

        $source = $this->backupDir . '/' . $this->backupFile;
        $dest =  $source . '.gz';

        $this->obfPrint('Yedekleme dosyasını gzip ile sıkıştırma: ' . $dest . '... ', 1, 0);

        $mode = 'wb' . $level;
        if ($fpOut = gzopen($dest, $mode)) {
            if ($fpIn = fopen($source, 'rb')) {
                while (!feof($fpIn)) {
                    gzwrite($fpOut, fread($fpIn, 1024 * 256));
                }
                fclose($fpIn);
            } else {
                return false;
            }
            gzclose($fpOut);
            if (!unlink($source)) {
                return false;
            }
        } else {
            return false;
        }

        $this->obfPrint('OK');
        return $dest;
    }

    /**
     * Çıktıyı ekrana yazdırır ve çıktı tamponunu temizler
     * @param string $msg Mesaj
     * @param int $lineBreaksBefore Satır araları önce
     * @param int $lineBreaksAfter Satır araları sonra
     */
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

        // Çıktıyı kaydet
        $this->output .= str_replace('<br />', '\n', $output);

        echo $output;

        if (php_sapi_name() != "cli") {
            ob_flush();
        }

        $this->output .= " ";

        flush();
    }

    /**
     * Tam yürütme çıktısını döner
     */
    public function getOutput()
    {
        return $this->output;
    }
}

/**
 * Backup_Database sınıfını başlatır ve yedeklemeyi gerçekleştirir
 */

// Tüm hataları raporla
error_reporting(E_ALL);
// Script'in maksimum çalışma süresini ayarla
set_time_limit(900); // 15 dakika

if (php_sapi_name() != "cli") {
    echo '<div style="font-family: monospace;">';
}

$backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$result = $backupDatabase->backupTables(TABLES) ? 'OK' : 'KO';
$backupDatabase->obfPrint('Yedekleme sonucu: ' . $result, 1);

// $output değişkenini e-posta ile gönderim gibi ek işlemler için kullanabilirsiniz
$output = $backupDatabase->getOutput();

if (php_sapi_name() != "cli") {
    echo '</div>';
}
?>

<br>
<a href="veritabani.php" style="display: block; text-align: left; font-size: 18px;">Geri Dön</a>
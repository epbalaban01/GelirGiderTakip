<?php
$host = 'localhost';  // Veritabanı sunucusu
$db = 'gelir_gider_takip'; // Veritabanı ismi
$user = 'root';  // Veritabanı kullanıcı adı
$pass = '123456'; // Veritabanı şifresi (varsayılan olarak boş)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>

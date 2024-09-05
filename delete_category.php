<?php
include('db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Kategoriyi veritabanından sil
    $sql = "DELETE FROM categories WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    // İşlem tamamlandığında kategori yönetim sayfasına yönlendir
    header('Location: manage_categories.php');
    exit();
} else {
    // ID parametresi eksikse kullanıcıyı geri yönlendir
    header('Location: manage_categories.php');
    exit();
}

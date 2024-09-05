<?php
include('db.php');

// Mesajları saklamak için session kullanımı
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    // Tarihten ayı çıkarmak
    $month = date("F", strtotime($date)); // "F" formatı Ocak, Şubat vb. verir

    $sql = "INSERT INTO income (date, month, category, description, amount) 
            VALUES (:date, :month, :category, :description, :amount)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'date' => $date,
        'month' => $month,
        'category' => $category,
        'description' => $description,
        'amount' => $amount
    ]);

    // Başarı mesajını sakla
    $_SESSION['message'] = "Gelir başarıyla eklendi!";

    // Sayfayı yeniden yükleyerek formu temizle
    header("Location: add_income.php");
    exit();
}

<?php
// update_profile.php

session_start();
require 'db.php'; // Veritabanı bağlantısını içeren dosya

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $currentPassword = $_POST['password'];
    $newPassword = $_POST['new_password'];
    $userId = $_SESSION['user_id']; // Kullanıcının ID'sini almak için oturumu kullanıyoruz

    // Kullanıcının mevcut şifresini veritabanından al
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    // Mevcut şifreyi kontrol et
    if (password_verify($currentPassword, $user['password'])) {
        $updateQuery = "UPDATE users SET username = ?, email = ?";

        // Yeni şifre varsa, şifreyi güncelle
        if (!empty($newPassword)) {
            $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateQuery .= ", password = ?";
        }

        $updateQuery .= " WHERE id = ?";
        $stmt = $pdo->prepare($updateQuery);

        $params = [$username, $email];
        if (!empty($newPassword)) {
            $params[] = $hashedNewPassword;
        }
        $params[] = $userId;

        $stmt->execute($params);

        header("Location: profile.php"); // Güncellenmiş profil bilgileri ile kullanıcıyı yönlendir
        exit;
    } else {
        echo "Mevcut şifre hatalı.";
    }
}

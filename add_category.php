<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];

    $sql = "INSERT INTO categories (name, type) VALUES (:name, :type)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'type' => $type]);

    header('Location: manage_categories.php');
    exit();
}
?>

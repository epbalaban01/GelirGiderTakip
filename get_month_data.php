<?php
include('db.php');

// URL'den gelen parametreleri alın
$month = isset($_GET['month']) ? $_GET['month'] : date('F');
$type = isset($_GET['type']) ? $_GET['type'] : 'expense'; // income veya expense

// Doğru tabloyu seç
$table = ($type === 'income') ? 'income' : 'expense';

// Sorgu hazırlama ve ay bilgisine göre filtreleme
$stmt = $pdo->prepare("SELECT * FROM $table WHERE MONTHNAME(date) = ?");
$stmt->execute([$month]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $formattedDate = date("d.m.Y", strtotime($row['date']));
    $formattedAmount = number_format($row['amount'], 2, ',', '.');
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$formattedDate}</td>
            <td>{$row['category']}</td>
            <td>{$row['description']}</td>
            <td>{$formattedAmount} ₺</td>
          </tr>";
}

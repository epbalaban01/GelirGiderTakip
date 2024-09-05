<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelir Tablosu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
        }

        .table-container {
            max-width: 800px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            text-align: center;
        }

        th:nth-child(4) {
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Gelir Tablosu</h1>

    <?php
    // Toplam tutarı hesapla
    $stmt = $pdo->query("SELECT SUM(amount) as total_amount FROM income");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_amount = $row['total_amount'];
    ?>

    <h2>Toplam Tutar: <?php echo number_format($total_amount, 2, ',', '.') . " ₺"; ?></h2>

    <div class="table-container">
        <table>
            <tr>
                <th>ID</th>
                <th>Tarih</th>
                <th>Kategori</th>
                <th style="width: 40%;">Açıklama</th>
                <th>Tutar</th>
            </tr>
            <?php
            $stmt = $pdo->query("SELECT * FROM income ORDER BY date DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $formattedDate = date("d.m.Y", strtotime($row['date']));
                $formattedAmount = number_format($row['amount'], 2, ',', '.');
                echo "<tr>
                        <td style='text-align: center;'>{$row['id']}</td>
                        <td style='text-align: center;'>{$formattedDate}</td>
                        <td style='text-align: center;'>{$row['category']}</td>
                        <td style='text-align: left;'>{$row['description']}</td>
                        <td style='text-align: center;'>{$formattedAmount} ₺</td>
                      </tr>";
            }
            ?>
        </table>
    </div>

    <br>
    <a href="dashboard.php" style="display: block; text-align: center; font-size: 18px;">Dashboard'a Geri Dön</a>
</body>

</html>
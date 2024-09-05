<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelir ve Gider Tabloları</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1,
        h2 {
            text-align: center;
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
    </style>
</head>

<body>
    <h1>Gelir ve Gider Tabloları</h1>
    <div class="table-container">
        <h2>Gelir Listesi</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Tarih</th>
                <th>Kategori</th>
                <th>Açıklama</th>
                <th>Tutar</th>
            </tr>
            <?php
            $stmt = $pdo->query("SELECT * FROM income ORDER BY date DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['amount']}</td>
                      </tr>";
            }
            ?>
        </table>

        <h2 id="gider">Gider Listesi</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Tarih</th>
                <th>Kategori</th>
                <th>Açıklama</th>
                <th>Tutar</th>
            </tr>
            <?php
            $stmt = $pdo->query("SELECT * FROM expense ORDER BY date DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['amount']}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>

    <br>
    <a href="dashboard.php" style="display: block; text-align: center; font-size: 18px;">Dashboard'a Geri Dön</a>
</body>

</html>
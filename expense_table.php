<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gider Tablosu</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .dataTables_wrapper .dt-buttons {
            margin-bottom: 10px;
            text-align: left;
            display: inline-block;
        }


        .top-bar {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .dataTables_wrapper .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        @media screen and (max-width: 600px) {
            .month-dropdown select {
                width: 100%;
            }

            .dataTables_wrapper .top-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body class="expense-table-page">
    <h1>Gider Tablosu</h1>

    <?php
    // Seçilen ayı al
    $month = isset($_GET['month']) ? $_GET['month'] : date('F');

    // Toplam tutarı hesapla (seçilen ay)
    $stmt = $pdo->prepare("SELECT SUM(amount) as total_amount FROM expense WHERE MONTHNAME(date) = :month");
    $stmt->execute(['month' => $month]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_amount = $row['total_amount'];
    ?>

    <h2>Toplam Tutar (<?php echo $month; ?>): <?php echo number_format($total_amount, 2, ',', '.') . " ₺"; ?></h2>

    <div class="month-buttons">
        <a href="?month=January">Ocak</a>
        <a href="?month=February">Şubat</a>
        <a href="?month=March">Mart</a>
        <a href="?month=April">Nisan</a>
        <a href="?month=May">Mayıs</a>
        <a href="?month=June">Haziran</a>
        <a href="?month=July">Temmuz</a>
        <a href="?month=August">Ağustos</a>
        <a href="?month=September">Eylül</a>
        <a href="?month=October">Ekim</a>
        <a href="?month=November">Kasım</a>
        <a href="?month=December">Aralık</a>
    </div>

    <br />

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tarih</th>
                    <th>Kategori</th>
                    <th style="width: 40%;">Açıklama</th>
                    <th>Tutar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Seçilen ayın verilerini getir
                $stmt = $pdo->prepare("SELECT * FROM expense WHERE MONTHNAME(date) = :month ORDER BY date DESC");
                $stmt->execute(['month' => $month]);
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
            </tbody>
        </table>
    </div>

    <br>
    <a href="dashboard.php" class="back-to-dashboard">Dashboard'a Geri Dön</a>
</body>

</html>
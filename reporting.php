<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Gelir verilerini çek
    $incomeStmt = $pdo->prepare("SELECT date, amount FROM income WHERE date BETWEEN :start_date AND :end_date");
    $incomeStmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
    $incomeData = $incomeStmt->fetchAll(PDO::FETCH_ASSOC);

    // Gider verilerini çek
    $expenseStmt = $pdo->prepare("SELECT date, amount FROM expense WHERE date BETWEEN :start_date AND :end_date");
    $expenseStmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
    $expenseData = $expenseStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Varsayılan tarih aralığı (örneğin, son 30 gün)
    $startDate = date('Y-m-d', strtotime('-30 days'));
    $endDate = date('Y-m-d');

    // Gelir verilerini çek
    $incomeStmt = $pdo->prepare("SELECT date, amount FROM income WHERE date BETWEEN :start_date AND :end_date");
    $incomeStmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
    $incomeData = $incomeStmt->fetchAll(PDO::FETCH_ASSOC);

    // Gider verilerini çek
    $expenseStmt = $pdo->prepare("SELECT date, amount FROM expense WHERE date BETWEEN :start_date AND :end_date");
    $expenseStmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
    $expenseData = $expenseStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raporlama ve Analiz</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .chart-container {
            position: relative;
            margin-bottom: 40px;
        }

        form {
            margin-bottom: 40px;
        }

        label {
            margin-right: 10px;
        }

        input[type="date"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Raporlama ve Analiz</h2>

        <form method="POST" action="">
            <label for="start_date">Başlangıç Tarihi:</label>
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>" required>

            <label for="end_date">Bitiş Tarihi:</label>
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>" required>

            <button type="submit">Raporu Görüntüle</button>
        </form>

        <div class="chart-container">
            <canvas id="incomeChart"></canvas>
        </div>

        <div class="chart-container">
            <canvas id="expenseChart"></canvas>
        </div>
    </div>

    <script>
        const incomeData = <?php echo json_encode($incomeData); ?>;
        const expenseData = <?php echo json_encode($expenseData); ?>;

        function getChartData(data) {
            const labels = [];
            const values = [];
            data.forEach(entry => {
                labels.push(entry.date);
                values.push(parseFloat(entry.amount));
            });
            return {
                labels,
                values
            };
        }

        const incomeChartData = getChartData(incomeData);
        const expenseChartData = getChartData(expenseData);

        new Chart(document.getElementById('incomeChart'), {
            type: 'bar', // 'line' yerine 'bar' tipi
            data: {
                labels: incomeChartData.labels,
                datasets: [{
                    label: 'Gelir',
                    data: incomeChartData.values,
                    backgroundColor: '#28a745', // Bar rengini yeşil yap
                    borderColor: '#1e7e34', // Çizgi rengi
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('expenseChart'), {
            type: 'bar', // 'line' yerine 'bar' tipi
            data: {
                labels: expenseChartData.labels,
                datasets: [{
                    label: 'Gider',
                    data: expenseChartData.values,
                    backgroundColor: '#dc3545', // Bar rengini kırmızı yap
                    borderColor: '#a71d2a', // Çizgi rengi
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelir Gider Takip - Raporlar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        .report-container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <h1>Raporlar</h1>
    <div class="report-container">
        <h2>Gelirler</h2>
        <!-- Gelir raporları burada olacak -->

        <h2>Giderler</h2>
        <!-- Gider raporları burada olacak -->
    </div>
    
    <br>
    <a href="dashboard.php" style="display: block; text-align: center; font-size: 18px;">Dashboard'a Geri Dön</a>
</body>
</html>

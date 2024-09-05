<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelir Ekle</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <!-- 
     <script>
        function formatCurrency(input) {
            if (!input.value.includes("₺")) {
                input.value = input.value + " ₺";
            }
        }
    </script>
    -->

   

</head>

<body>
    <div class="form-container1">
        <h2>Gelir Ekle</h2>
        <form action="insert_income.php" method="POST" onsubmit="cleanAmount(this.amount)">
            <label for=" date">Tarih:</label>
            <input type="date" name="date" required>

            <label for="category">Kategori:</label>
            <select name="category" required>
                <?php
                // Kategorileri veritabanından çekiyoruz
                $stmt = $pdo->query("SELECT * FROM categories WHERE type = 'income'");
                while ($row = $stmt->fetch()) {
                    echo "<option value=\"{$row['name']}\">{$row['name']}</option>";
                }
                ?>
            </select>

            <label for="description">Açıklama:</label>
            <input type="text" name="description" required>


            <label for="amount">Tutar:</label>
            <input type="text" name="amount" required onblur="formatNumber(this)">


            <button type="submit">Gelir Ekle</button>
        </form>
    </div>

    <br>
    <a href="dashboard.php" class="back-to-dashboard">Dashboard'a Geri Dön</a>
</body>

</html>
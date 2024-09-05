<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelir Gider Takip</title>
    <script>
        function formatCurrency(input) {
            // Eğer ₺ işareti zaten yoksa ekler
            if (!input.value.includes("₺")) {
                input.value = input.value + " ₺";
            }
        }
    </script>
</head>

<body>
    <h2>Gelir Ekle</h2>
    <form action="insert_income.php" method="POST">
        <label for="date">Tarih:</label>
        <input type="date" name="date" required><br><br>

        <label for="category">Kategori:</label>
        <input type="text" name="category" required><br><br>

        <label for="description">Açıklama:</label>
        <input type="text" name="description" required><br><br>

        <label for="amount">Tutar:</label>
        <input type="text" name="amount" required onblur="formatCurrency(this)"><br><br>

        <button type="submit">Gelir Ekle</button>
    </form>

    <h2>Gider Ekle</h2>
    <form action="insert_expense.php" method="POST">
        <label for="date">Tarih:</label>
        <input type="date" name="date" required><br><br>

        <label for="category">Kategori:</label>
        <input type="text" name="category" required><br><br>

        <label for="description">Açıklama:</label>
        <input type="text" name="description" required><br><br>

        <label for="amount">Tutar:</label>
        <input type="text" name="amount" required onblur="formatCurrency(this)"><br><br>

        <button type="submit">Gider Ekle</button>
    </form>

    <br>
    <a href="transactions.php">Gelir ve Gider Listesi</a>
</body>

</html>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelir Gider Takip - Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container dashboard-container">
        <h1 class="text-center">Gelir Gider Takip Dashboard</h1>
        <div class="row">
            <div class="col-md-3 dashboard-item">
                <a href="add_income.php">Gelir Ekle</a>
            </div>
            <div class="col-md-3 dashboard-item">
                <a href="add_expense.php">Gider Ekle</a>
            </div>
            <div class="col-md-3 dashboard-item">
                <a href="reporting.php">Raporlar</a>
            </div>
            <div class="col-md-3 dashboard-item">
                <a href="income_table.php">Gelir Tablosu</a>
            </div>
            <div class="col-md-3 dashboard-item">
                <a href="expense_table.php">Gider Tablosu</a>
            </div>
            <div class="col-md-3 dashboard-item">
                <a href="settings.php">Ayarlar</a>
            </div>
            <div class="col-md-3 dashboard-item profile-item">
                <a href="profile.php">Profil Yönetimi</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS ve bağımlılıkları -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
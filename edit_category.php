<?php
include('db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Kategori bilgilerini veritabanından çek
    $sql = "SELECT * FROM categories WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        // Eğer kategori bulunamazsa yönetim sayfasına yönlendir
        header('Location: manage_categories.php');
        exit();
    }
} else {
    // ID parametresi eksikse kullanıcıyı geri yönlendir
    header('Location: manage_categories.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];

    // Kategoriyi güncelle
    $sql = "UPDATE categories SET name = :name, type = :type WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'type' => $type, 'id' => $id]);

    // İşlem tamamlandığında kategori yönetim sayfasına yönlendir
    header('Location: manage_categories.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Düzenle</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="manage-categories">

        <div class="form-container">
            <h2>Kategori Düzenle</h2>

            <!-- Kategori Düzenleme Formu -->
            <form action="" method="POST">
                <label for="name">Kategori Adı:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>

                <label for="type">Kategori Türü:</label>
                <select name="type" required>
                    <option value="income" <?php echo $category['type'] === 'income' ? 'selected' : ''; ?>>Gelir</option>
                    <option value="expense" <?php echo $category['type'] === 'expense' ? 'selected' : ''; ?>>Gider</option>
                </select>

                <button type="submit">Kaydet</button>
            </form>
        </div>
    </div>
    <br>
    <a href="manage_categories.php" style="display: block; text-align: center; font-size: 18px;">Kategori'ye Geri Dön</a>

</body>

</html>
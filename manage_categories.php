<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Yönetimi</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="manage-categories">

        <div class="form-container">
            <h2>Kategori Yönetimi</h2>

            <!-- Kategori Ekleme Formu -->
            <form action="add_category.php" method="POST">
                <label for="name">Kategori Adı:</label>
                <input type="text" name="name" required>

                <label for="type">Kategori Türü:</label>
                <select name="type" required>
                    <option value="income">Gelir</option>
                    <option value="expense">Gider</option>
                </select>

                <button type="submit">Kategori Ekle</button>
            </form>
        </div>

        <div class="category-list">
            <h3>Kategoriler</h3>
            <table id="categoryTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori Adı</th>
                        <th>Kategori Türü</th>
                        <th>Aksiyonlar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM categories");
                    while ($row = $stmt->fetch()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['type']}</td>
                            <td>
                                <a href='edit_category.php?id={$row['id']}'>Düzenle</a> | 
                                <a href='delete_category.php?id={$row['id']}' onclick='return confirm(\"Bu kategoriyi silmek istediğinizden emin misiniz?\")'>Sil</a>
                            </td>
                          </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <br>
    <a href="settings.php" style="display: block; text-align: center; font-size: 18px;">Ayarlar'a Geri Dön</a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#categoryTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
                }
            });
        });
    </script>

</body>

</html>
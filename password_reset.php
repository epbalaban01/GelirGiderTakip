<?php
// Database bağlantı kodları ve şifre sıfırlama kodları
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $token = $_GET['token'];

    // Token doğrulaması yap ve yeni şifreyi veritabanında güncelle
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Şifre Yenileme</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container">
        <h1>Yeni Şifre Belirle</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="password">Yeni Şifre</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Yeni şifrenizi girin" required>
            </div>
            <button type="submit" class="btn btn-primary">Şifreyi Güncelle</button>
        </form>
    </div>
</body>

</html>

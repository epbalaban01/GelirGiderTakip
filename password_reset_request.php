<?php
// Database bağlantı kodları ve e-posta gönderim kodları
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // E-posta adresi veritabanında var mı kontrol et
    // Eğer varsa, şifre sıfırlama bağlantısı oluştur ve kullanıcıya e-posta gönder
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
        <h1>Şifre Yenileme</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">E-Posta Adresiniz</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="E-Posta adresinizi girin" required>
            </div>
            <button type="submit" class="btn btn-primary">Şifre Yenileme Talebi Gönder</button>
        </form>
    </div>
</body>

</html>
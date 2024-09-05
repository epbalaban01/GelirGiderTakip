<?php
// Veritabanı bağlantı ayarlarını tanımla
define("DB_USER", 'root');
define("DB_PASSWORD", '123456');
define("DB_NAME", 'gelir_gider_takip');
define("DB_HOST", 'localhost');

// Veritabanı bağlantısını oluştur
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Bağlantıyı kontrol et
if ($mysqli->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
}

// Form gönderildiyse işlemleri yap
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini al
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verilerin boş olmadığını kontrol et
    if (!empty($username) && !empty($email) && !empty($password)) {
        // Şifreyi güvenli bir şekilde hashle
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Kullanıcıyı veritabanına ekle
        $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<p>Kayıt başarılı! <a href='login.php'>Giriş Yap</a></p>";
        } else {
            echo "<p>Kayıt sırasında bir hata oluştu: " . $stmt->error . "</p>";
        }

        // Hazırlanmış ifadeyi kapat
        $stmt->close();
    } else {
        echo "<p>Lütfen tüm alanları doldurunuz.</p>";
    }
}

// Bağlantıyı kapat
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kayıt Ol</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="text-center">Kayıt Ol</h1>
                        <p class="text-center">Zaten bir hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="username">Kullanıcı Adı</label>
                                <input id="username" name="username" type="text" class="form-control" placeholder="Kullanıcı adı" required>
                            </div>

                            <div class="form-group">
                                <label for="email">E-Mail</label>
                                <input id="email" name="email" type="email" class="form-control" placeholder="E-Mail" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Şifre</label>
                                <input id="password" name="password" type="password" class="form-control" placeholder="Şifre" required>
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">Hüküm ve koşulları kabul ediyorum</label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Kayıt Ol</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
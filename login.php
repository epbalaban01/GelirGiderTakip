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
    $password = $_POST['password'];

    // Verilerin boş olmadığını kontrol et
    if (!empty($username) && !empty($password)) {
        // Kullanıcıyı veritabanından al
        $stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $db_username, $hashed_password);

        if ($stmt->fetch()) {
            // Şifreyi kontrol et
            if (password_verify($password, $hashed_password)) {
                // Giriş başarılı
                session_start();
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $db_username;
                header("Location: dashboard.php"); // Kullanıcıyı yönetim paneline yönlendir
                exit();
            } else {
                $error_message = "Geçersiz şifre.";
            }
        } else {
            $error_message = "Kullanıcı bulunamadı.";
        }

        // Hazırlanmış ifadeyi kapat
        $stmt->close();
    } else {
        $error_message = "Lütfen tüm alanları doldurunuz.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Giriş Yap</h3>
                        <p class="text-center">Devam etmek için hesabınıza giriş yapın.</p>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="username">Kullanıcı Adı</label>
                                <input id="username" name="username" type="text" class="form-control" placeholder="" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Şifre</label>
                                <input id="password" name="password" type="password" class="form-control" placeholder="" required>
                                <small class="form-text text-right">
                                    <a href="auth_pass_recovery_boxed.html" class="text-secondary">Parolanızı mı unuttunuz?</a>
                                </small>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Giriş Yap</button>

                            <?php if (!empty($error_message)): ?>
                                <p class="text-danger text-center mt-3"><?php echo $error_message; ?></p>
                            <?php endif; ?>

                            <p class="text-center mt-3">Kaydınız yok mu? <a href="register.php">Hesap Oluşturun</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
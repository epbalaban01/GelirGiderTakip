 <?php
    session_start();
    require 'db.php'; // Veritabanı bağlantısını içeren dosya

    $userId = $_SESSION['user_id'];
    $query = "SELECT profile_photo, username, email, password, first_name, last_name FROM users WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    $profilePhoto = $user['profile_photo'];
    $username = $user['username'];
    $email = $user['email'];
    $password = $user['password'];
    $first_name = $user['first_name'];
    $last_name = $user['last_name'];

    ?>


 <!DOCTYPE html>
 <html lang="tr">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Kullanıcı Profili - Dashboard</title>
     <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="style.css">
 </head>

 <body>
     <div class="container profile-container">
         <h1 class="text-center">Kullanıcı Profili</h1>
         <div class="text-center profile-photo">

             <!-- Profil Fotoğrafı -->
             <img src="<?php echo htmlspecialchars($profilePhoto); ?>" alt="Profil Fotoğrafı">
             <form action="upload_profile_photo.php" method="POST" enctype="multipart/form-data">
                 <input type="file" name="profile_photo" class="btn btn-primary ">
                 <p></p>
                 <button type="submit" class="btn btn-primary">Profil Fotoğrafını Yükle</button>
             </form>
         </div>

         <form action="update_profile.php" method="POST">
             <div class="form-group">
                 <label for="username">Kullanıcı Adı</label>
                 <input type="text" class="form-control" id="username" name="username" placeholder="Kullanıcı Adı" value="<?php echo htmlspecialchars($username); ?>">
             </div>
             <div class="form-group">
                 <label for="first_name">Adı</label>
                 <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Adı" value="<?php echo htmlspecialchars($first_name); ?>">
             </div>
             <div class="form-group">
                 <label for="last_name">Soyadı</label>
                 <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Soyadı" value="<?php echo htmlspecialchars($last_name); ?>">
             </div>
             <div class="form-group">
                 <label for="email">E-Posta</label>
                 <input type="email" class="form-control" id="email" name="email" placeholder="E-Posta" value="<?php echo htmlspecialchars($email); ?>">
             </div>
             <div class="form-group">
                 <label for="password">Mevcut Şifre</label>
                 <input type="password" class="form-control" id="password" name="password" placeholder="Mevcut Şifre" value="<?php echo htmlspecialchars($password); ?>">
             </div>
             <div class="form-group">
                 <label for="new_password">Yeni Şifre (isteğe bağlı)</label>
                 <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Yeni Şifre">
             </div>
             <button type="submit" class="btn btn-primary">Profili Güncelle</button>
         </form>
     </div>

     <!-- Bootstrap JS ve bağımlılıkları -->
     <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 </body>

 </html>
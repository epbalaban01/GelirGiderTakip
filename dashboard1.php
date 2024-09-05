     <?php
        session_start();
        require 'db.php'; // Veritabanı bağlantısını içeren dosya

        $userId = $_SESSION['user_id'];
        $query = "SELECT profile_photo, username, email, password FROM users WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        $profilePhoto = $user['profile_photo'];
        $username = $user['username'];
        $email = $user['email'];
        $password = $user['password'];
        ?>

     <!DOCTYPE html>
     <html lang="tr">

     <head>
         <meta name="viewport" content="width=device-width, initial-scale=1">
         <title>Panod-Portal</title>
         <link rel="icon" type="image/png" href="/img/favicon.png" />
         <link rel="stylesheet" media="all" href="deneme/style.css" data-turbolinks-track="reload" />
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
         <script src="deneme/script.js" data-turbolinks-track="reload"></script>
         <script type="text/javascript">
             var resizeIframe = function(obj) {
                 obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
                 if (obj.contentWindow.document.body.scrollHeight == 0 && document.readyState == "complete") {
                     obj.contentWindow.document.location.reload(true);
                 }
             }
         </script>

         <style type="text/css">
             .usernameCont {
                 display: inline;
                 width: auto;
                 height: 40px;
                 border: 0px solid red;
                 padding: 0px;
                 margin-top: 10px;
                 line-height: 30px;
                 vertical-align: top;


             }

             .userimgCont {
                 display: inline-block;
                 width: 40px;
                 height: 40px;
                 -webkit-border-radius: 20px;
                 -moz-border-radius: 20px;
                 border-radius: 20px;
                 overflow: hidden;
                 border: 0px solid #ffffff;
             }

             .userimage {
                 height: auto;
                 width: 100%;
             }

             ::-moz-selection {
                 background-color: #000000;
                 color: #fff;
             }

             ::selection {
                 background-color: #000000;
                 color: #fff;
             }

             .yenilist {
                 color: #ffffff;
                 background-color: red;
                 padding: 2px 3px;
                 margin-left: 5px;
                 -webkit-border-radius: 3px;
                 -moz-border-radius: 3px;
                 border-radius: 3px;
             }

             .dot {
                 height: 10px;
                 width: 10px;
                 border-radius: 50%;
                 display: inline-block;
             }
         </style>
     </head>

     <body>
         <div id="wrapper">
             <!-- Navigation -->
             <nav class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
                 <div class="navbar-header">
                     <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                         <span class="sr-only">Toggle navigation</span>
                         <span class="icon-bar"></span>
                         <span class="icon-bar"></span>
                         <span class="icon-bar"></span>
                     </button>
                     <a class="navbar-brand" href="/">Gelir Gider Takip v1.0</a>
                 </div>

                 <ul class="nav navbar-top-links navbar-right">
                     <li class="dropdown">
                         <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="padding:5px 15px 0px 15px;">
                             <div class="usernameCont"><?php echo htmlspecialchars($username); ?><i class="fa fa-caret-down"></i></div>
                             <div class="userimgCont">
                                 <img src="<?php echo htmlspecialchars($profilePhoto); ?>" class="userimage" alt="Profil Fotoğrafı">
                             </div>

                         </a>
                         <ul class="dropdown-menu dropdown-user">
                             <li><a href="/profile"><i class="fa fa-user fa-fw"></i> Profil Yönetimi</a></li>
                             <li><a href="/settings"><i class="fa fa-gear fa-fw"></i> Ayarlar</a></li>
                             <li class="divider"></li>
                             <li><a href="/logout"><i class="fa fa-sign-out fa-fw"></i> Çıkış</a></li>
                         </ul>
                     </li>
                 </ul>

                 <div class="navbar-inverse sidebar" role="navigation">
                     <div class="sidebar-nav navbar-collapse">
                         <ul class="nav" id="side-menu">
                             <li class="sidebar-search">
                                 <form class="form-inline" method="get" action="/users/search">
                                     <div class="form-group">
                                         <input type="text" class="form-control" name="query" id="query" placeholder="Ara...">
                                     </div>
                                 </form>
                             </li>
                             <li><a href="/"><i class="fa fa-dashboard fa-fw"></i> Ana Sayfa</a></li>
                             <li><a href="/add_income"><i class="fa fa-plus-circle fa-fw"></i> Gelir Ekle</a></li>
                             <li><a href="/add_expense"><i class="fa fa-minus-circle fa-fw"></i> Gider Ekle</a></li>
                             <li><a href="/income_report"><i class="fa fa-line-chart fa-fw"></i> Gelir Raporu</a></li>
                             <li><a href="/expense_report"><i class="fa fa-bar-chart fa-fw"></i> Gider Raporu</a></li>
                             <li><a href="/income_table"><i class="fa fa-table fa-fw"></i> Gelir Tablosu</a></li>
                             <li><a href="/expense_table"><i class="fa fa-table fa-fw"></i> Gider Tablosu</a></li>
                             <li><a href="/profile_management"><i class="fa fa-user fa-fw"></i> Profil Yönetimi</a></li>
                             <li><a href="/settings"><i class="fa fa-gear fa-fw"></i> Ayarlar</a></li>
                         </ul>
                     </div>
                 </div>
             </nav>

             <div id="page-wrapper">
                 <div class="row">
                     <div class="col-lg-12">
                         <div class="page-header clearfix">
                             <div class="pull-left">
                                 <h1>Panel Yönetim</h1>
                             </div>
                             <div class="pull-right">
                                 <a href="/reports" class="btn btn-default">Raporlar</a>
                                 <a href="/merge_records" class="btn btn-default">Kayıt Birleştir</a>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

     </body>

     </html>
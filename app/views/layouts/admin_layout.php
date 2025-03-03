<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-title" content="TedeShop">
    <meta name="application-name" content="TedeShop">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="<?php echo _WEB_ROOT; ?>/public/assets/site/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="<?php echo _WEB_ROOT; ?>/public/assets/admin/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.0.0/ckeditor5.css">

    <title><?php echo (!empty($page_title))?$page_title:'Shop-PHP2'?></title>
</head>

<body>
    <nav class="navbar">
        <?php $this->render('menus/menu_admin'); ?>
    </nav>
    <main class="main">
        <div class="topbar container-fluid">
            <div class="toggle">
                <ion-icon name="menu-outline"></ion-icon>
            </div>
            
            <div class="clock" id="clock"></div>
            <?php
            if(isset($_SESSION['user']) && $_SESSION['user'] == true){
            ?>
            <div class="logout">
                <a class="btn btn-danger" href="<?php echo _WEB_ROOT?>/log-out">Logout</a>
            </div>
            <?php
            }
            ?>
            

        </div>
        <div class="container-fluid">
            <?php $this->render($content,$sub_content); ?>
        </div>
    </main>

    <!-- =========== Scripts =========  -->
    <script src="<?php echo _WEB_ROOT; ?>/public/assets/site/js/jquery.min.js"></script>
    <script src="<?php echo _WEB_ROOT; ?>/public/assets/site/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo _WEB_ROOT; ?>/public/assets/admin/js/main.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript">
    </script>

    <script src="<?php echo _WEB_ROOT; ?>/public/assets/admin/js/validated.js"></script>


    <!-- ====== ionicons ======= -->
     
    <script src="https://cdn.ckeditor.com/ckeditor5/44.0.0/ckeditor5.umd.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    

</body>

</html>
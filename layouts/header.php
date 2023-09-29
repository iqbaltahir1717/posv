<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title><?= $title_apl;?></title>
    <link rel="icon" href="<?= $baseURL.'assets/uploads/toko/'.$toko->logo;?>">

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= $baseURL;?>assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?= $baseURL;?>assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= $baseURL;?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= $baseURL;?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= $baseURL;?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= $baseURL;?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="<?= $baseURL;?>assets/plugins/ekko-lightbox/ekko-lightbox.css">
    <link rel="stylesheet" href="<?= $baseURL;?>assets/dist/css/adminlte.min.css">
    <!-- Sweet Alert -->
    <link rel="stylesheet" href="<?= $baseURL;?>assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.css">
    <!-- jQuery -->
    <script src="<?= $baseURL;?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?= $baseURL;?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="<?= $baseURL;?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="<?= $baseURL;?>assets/plugins/chart.js/Chart.min.js"></script>
    <script src="<?= $baseURL;?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
</head>
<?php 
    if(!empty(getSegments($baseURL, '1'))){
        if(getSegments($baseURL, '1').'/'.getSegments($baseURL, '2') == 'barang/index.php'){
            $sidebar = "sidebar-collapse";
        }
        else if(getSegments($baseURL, '1').'/'.getSegments($baseURL, '2') == 'transaksi/index.php'){
            $sidebar = "sidebar-collapse";
        }
        else if(getSegments($baseURL, '1').'/'.getSegments($baseURL, '2') == 'transaksi_beli/index.php'){
            $sidebar = "sidebar-collapse";
        }
        else{
            $sidebar = "";
        }
    }else{
        $sidebar = "";
    }
?>

<body <?php if(in_array(getSegments($baseURL, '1'),['','index.php'])){?>onload="jam()" <?php }?>
    class="hold-transition sidebar-mini layout-fixed <?= $sidebar;?>">
    <div class="wrapper">
        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?= $baseURL;?>assets/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
</div> -->

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <!-- <i class="far fa-user-circle text-maroon fa-lg mr-2"></i>  -->
                        <img class="rounded-circle mr-2" style="width:35px;margin-top:-5px;"
                            src="<?= $baseURL.'assets/uploads/users/'.$users->avatar;?>">
                        <span class="text-uppercase">
                            <?= $users->name;?>
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <!-- Button trigger modal -->
                        <a href="#" data-toggle="modal" data-target="#modelIdLogout" class="dropdown-item">
                            <span class="fas fa-power-off mr-2 text-danger"></span>Keluar
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
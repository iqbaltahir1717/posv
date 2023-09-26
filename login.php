<?php 
    // session
    session_start();

    include 'setting.php';
    include 'helper.php';
    error_reporting(0);
    // echo password_hash('123', PASSWORD_DEFAULT);
    if(!empty($_POST['user']) && !empty($_POST['pass'])) {
        // tangkap username dan password 
        $user = htmlentities($_POST['user']);
        $pass = htmlentities($_POST['pass']);

        $sql = "SELECT * FROM users WHERE user=?";
        $row = $connectdb->prepare($sql);
        $row->execute(array($user));
        $count = $row->rowCount();
        
        if($count > 0)
        {
            // jika benar
            $hsl = $row->fetch();
            if(password_verify($pass,$hsl['pass']))
            {
                if($hsl['active'] == '1')
                {
                    $_SESSION['codekop_session'] = $hsl;
                    // echo "<script>window.location='index.php';</script>";
                    redirect("index.php");
                }else{
                    // jika salah
                    // echo "<script>alert('Login di Banned !');window.location='login.php';</script>";

                    // set_flashdata("Gagal","Login di Banned !","danger");
                    redirect("login.php?act=lb");
                }
            }else{
                // set_flashdata("Gagal","Login periksa password anda !","danger");
                redirect("login.php?act=lp");
            }
        }else{
            // jika salah
            //set_flashdata("Gagal","Login periksa username anda !","danger");
            redirect("login.php?act=lu");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in </title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=$baseURL;?>assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?=$baseURL;?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=$baseURL;?>assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <?php if(!empty($_GET['act'] == 'lb')){?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Gagal Login di Banned !</strong>
        </div>
        <?php }?>
        <?php if(!empty($_GET['act'] == 'lp')){?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Gagal Login periksa password anda !</strong>
        </div>
        <?php }?>
        <?php if(!empty($_GET['act'] == 'lu')){?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Gagal Login periksa username anda !</strong>
        </div>
        <?php }?>
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="<?=$baseURL;?>" class="h3"><b><?= $title_apl;?></b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" autofocus name="user" required placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="pass" required placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8"></div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?=$baseURL;?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?=$baseURL;?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?=$baseURL;?>assets/dist/js/adminlte.min.js"></script>
    <script type="application/javascript">
    //angka 500 dibawah ini artinya pesan akan muncul dalam 0,5 detik setelah document ready
    $(document).ready(function() {
        setTimeout(function() {
            $(".alert-danger").slideUp('slow');
        }, 3000);
    })
    //angka 3000 dibawah ini artinya pesan akan hilang dalam 3 detik setelah muncul
    setTimeout(function() {
        $(".alert-danger").slideUp('slow');
    }, 3000);
    $(document).ready(function() {
        setTimeout(function() {
            $(".alert-success").slideUp('slow');
        }, 3000);
    })
    setTimeout(function() {
        $(".alert-success").slideUp('slow');
    }, 3000);
    $(document).ready(function() {
        setTimeout(function() {
            $(".alert-info").slideUp('slow');
        }, 3000);
    })
    setTimeout(function() {
        $(".alert-info").slideUp('slow');
    }, 3000);
    $(document).ready(function() {
        setTimeout(function() {
            $(".alert-error").slideUp('slow');
        }, 3000);
    })
    setTimeout(function() {
        $(".alert-warning").slideUp('slow');
    }, 3000);
    </script>
</body>

</html>
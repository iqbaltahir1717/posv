<?php
@ob_start();
@session_start();
/*
  |--------------------------------------------------------------------------
  | POS Codekop
  |--------------------------------------------------------------------------
  |
  | @package   : pos codekop
  | @version   : v.1.0
  | @author    : fauzan1892
  | @copyright : Copyright (c) 2021 Codekop.com (https://www.codekop.com)
  |
  | free for everyone to development, a pos codekop premium project
  | recommended php version for running is 7.3+
  |
 */
    
require 'setting.php';
require 'helper.php';
require 'vendor/autoload.php';

if (!empty($_SESSION['codekop_session'])) {
    global $uid;
    global $users;
    global $toko;
    $uid =  (int)$_SESSION['codekop_session']['id'];
    $sql_users = "SELECT hak_akses.hak_akses, users.* FROM users LEFT JOIN hak_akses ON users.akses=hak_akses.id WHERE users.id = ?";
    $row_users = $connectdb->prepare($sql_users);
    $row_users->execute(array($uid));
    $users = $row_users->fetch(PDO::FETCH_OBJ);

    $sql_toko =  "SELECT * FROM toko WHERE id = 1";
    $row_toko = $connectdb->prepare($sql_toko);
    $row_toko->execute();
    $toko = $row_toko->fetch(PDO::FETCH_OBJ);
} else {
    redirect($baseURL.'login.php');
    exit;
}

$explode_url = explode('/', $_SERVER['REQUEST_URI']);
$rowurl = count(explode('/', $baseURL));
$row_url = count(parse_url($baseURL));
$parseUrl = parse_url($baseURL);
$row_url = count($parseUrl);
$urlc = $rowurl-$row_url;
if (isset($parseUrl['port'])) {
    $rurl = $urlc;
} else {
    $rurl = $urlc-1;
}
$explode_url = array_slice($explode_url, $rurl);
    
require 'layouts/header.php';
require 'layouts/sidebar.php';

if (!empty($explode_url[1])) {
    if (!empty($explode_url[2])) {
        if (!empty($explode_url[3])) {
            $fl = explode('.php', $explode_url[3]);
            if (empty($fl[0])) {
                require 'views/errors/404.php';
            } else {
                $files = explode('?', $explode_url[3]);
                if (file_exists('views/'.$explode_url[1].'/'.$explode_url[2].'/'.$files[0])) {
                    require 'views/'.$explode_url[1].'/'.$explode_url[2].'/'.$files[0];
                } else {
                    require 'views/errors/404.php';
                }
            }
        } else {
            $files = explode('?', $explode_url[2]);
            if (file_exists('views/'.$explode_url[1].'/'.$files[0])) {
                require 'views/'.$explode_url[1].'/'.$files[0];
            } else {
                require 'views/errors/404.php';
            }
        }
    } else {
        if ($explode_url[1] != 'index.php') {
            $files = explode('?', $explode_url[1]);
            if (file_exists('views/home/'.$files[0])) {
                include 'views/home/'.$files[0];
            } else {
                if (file_exists('views/'.$explode_url[1].'/index.php')) {
                    include 'views/'.$explode_url[1].'/index.php';
                } else {
                    include 'views/errors/404.php';
                }
            }
        } else {
            include 'views/home/index.php';
        }
    }
} else {
    include 'views/home/index.php';
}
    
include 'layouts/footer.php';

// ob_flush sends all the data to the browser
@ob_flush();

<?php
/*
|--------------------------------------------------------------------------
| Connect DB PHP MySQL
|--------------------------------------------------------------------------
|
*/
    $host     = "localhost";
    $user     = "root";
    $pass     = "";
    $dbname   = "codekop_premium_posv1";

    $connectdb = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
/*
|--------------------------------------------------------------------------
| Project Title Name
|--------------------------------------------------------------------------
|
*/
    $title_apl = 'AA-VAPESHOP';

/*
|--------------------------------------------------------------------------
| BASE SITE URL
|--------------------------------------------------------------------------
|
*/
    global $baseURL;
    $baseURL = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
    $baseURL .= "://" . $_SERVER['HTTP_HOST'];
    $baseURL .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
    
    // $baseURL  = "http://localhost/testing/crud-php-generator/";

/*
|--------------------------------------------------------------------------
| Date Default Timezone SET
|--------------------------------------------------------------------------
|
*/
    date_default_timezone_set("Asia/Makassar");

/*
|--------------------------------------------------------------------------
| Error Reporting
|--------------------------------------------------------------------------
|
*/
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
ini_set('log_errors', 0);
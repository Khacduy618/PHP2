<?php
session_start();
require_once 'bootstrap.php';
$app = new App();
// if (!empty($_SERVER['PATH_INFO'])) {
//     $url = $_SERVER['PATH_INFO'];
// }else{
//     $url = '/';
// }
// echo $url;
// echo '<pre>';
// print_r($_SERVER);
// echo '</pre>';
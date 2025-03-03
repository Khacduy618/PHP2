<?php


$path = str_replace('\\', '/', __DIR__);
define('_DIR_ROOT', $path);

// Xử lý http root 
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $web_root = 'https://' . $_SERVER['HTTP_HOST'];
} else {
    $web_root = 'http://' . $_SERVER['HTTP_HOST'];
}

$folder = str_replace(strtolower($_SERVER['DOCUMENT_ROOT']), '', strtolower(_DIR_ROOT));
$web_root = $web_root . $folder;
define('_WEB_ROOT', $web_root);

// Load composer autoloader first
require_once _DIR_ROOT . '/vendor/autoload.php';

// Load configs
$configs_dir = scandir('configs');
if (!empty($configs_dir)) {
    foreach ($configs_dir as $item) {
        if ($item != '.' && $item != '..' && file_exists('configs/' . $item)) {
            require_once 'configs/' . $item;
        }
    }
}



//kiem tra config va load db
if (!empty($config['database'])) {
    $db_config = array_filter($config['database']);
    if (!empty($db_config)) {
        require_once 'core/Connection.php';
        require_once 'core/Database.php';
    }
}

// echo '<pre>';
// print_r($db_config);
// echo '</pre>';



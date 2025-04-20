<?php

define('APP_NAME', 'Cafe Explorer');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/cafe-review');
define('APP_ENV', 'development');

define('DEBUG_MODE', true);
define('DISPLAY_ERRORS', true);
ini_set('display_errors', DISPLAY_ERRORS);
error_reporting(DEBUG_MODE ? E_ALL : 0);

date_default_timezone_set('Asia/Ho_Chi_Minh');

define('SESSION_LIFETIME', 3600);

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH.'/app');
define('CONTROLLERS_PATH', APP_PATH.'/controllers');
define('MODELS_PATH', APP_PATH.'/models');
define('VIEWS_PATH', APP_PATH.'/views');
define('PUBLIC_PATH', ROOT_PATH.'/public');
define('UPLOADS_PATH', PUBLIC_PATH.'/uploads');

require_once 'database.php';

define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
define('MAX_FILE_SIZE', 2097152); // 2MB in bytes

define('ITEMS_PER_PAGE', 10);

define('CSRF_TOKEN_NAME', 'csrf_token');

if (! isset($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

function base_url($path = '')
{
    return APP_URL.($path ? '/'.ltrim($path, '/') : '');
}

function asset_url($path = '')
{
    return base_url('public/'.ltrim($path, '/'));
}

function redirect($path)
{
    header('Location: '.base_url($path));
    exit;
}

function view($view, $data = [])
{
    extract($data);
    require_once VIEWS_PATH.'/'.$view.'.php';
}

function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    exit();
}

function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

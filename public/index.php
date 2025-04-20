<?php

require __DIR__.'/../public/libs/bootstrap.php';
require_once __DIR__.'/../config/config.php';
$is_guest = ! is_user_logged_in();

/* $is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin']; */
/**/
/* if (! $is_guest && $is_admin) { */
/*    redirect('admin.php'); */
/*    exit; */
/* } */

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$page = str_replace('.php', '', $page);

$valid_pages = ['home', 'about', 'saves', 'contact'];

if (! in_array($page, $valid_pages)) {
    $page = 'home';
}

require_once __DIR__.'/../config/database.php';
$conn = Database::getConnection();

require_once __DIR__.'/../app/views/layouts/header.php';
require_once __DIR__."/../app/views/{$page}.php";
require_once __DIR__.'/../app/views/layouts/footer.php';

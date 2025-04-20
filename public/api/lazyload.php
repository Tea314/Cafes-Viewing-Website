
<?php
require_once __DIR__.'/../../app/controllers/LazyLoadController.php';

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$controller = new LazyLoadController;
echo $controller->fetchCafes($page);
?>

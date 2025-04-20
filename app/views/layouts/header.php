<?php
define('BASE_URL', '/mywebsite/public');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <title><?= $title ?? 'Home' ?></title>

    <link href="<?= BASE_URL ?>/../node_modules/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
<?php flash() ?>
<header>
    <div class="menu-icon" onclick="toggleMenu()">☰</div>
    <h1>What is your taste?</h1>

            <?php
    $current_page = basename($_SERVER['PHP_SELF']);
if ($current_page !== 'admin.php') {
    include __DIR__.'/navbar.php';
}
?>
            
</header>

<div id="overlay" class="overlay"></div>




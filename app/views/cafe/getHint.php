<?php

require_once __DIR__ . '/../../controllers/HintController.php';

$q = isset($_REQUEST['q']) ? $_REQUEST['q'] : '';

$controller = new HintController;
$hint = $controller->getHint($q);

echo empty($hint) ? 'no suggestion' : $hint;

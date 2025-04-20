
<?php

require_once __DIR__.'/../models/Cafe.php';

class HintController
{
    public function getHint($query)
    {
        if (empty($query)) {
            return '';
        }

        $cafeModel = new Cafe;
        $results = $cafeModel->searchCafeNames($query);

        return empty($results) ? '' : implode(', ', $results);
    }
}

if (isset($_GET['q'])) {
    $controller = new HintController;
    echo $controller->getHint($_GET['q']);
    exit;
}

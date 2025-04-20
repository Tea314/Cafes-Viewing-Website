
<?php
require_once __DIR__.'/../models/Cafe.php';

class SearchController
{
    public function searchCafes()
    {
        $district = isset($_GET['district']) && $_GET['district'] !== 'Districts' ? intval($_GET['district']) : null;
        $name = isset($_GET['name']) ? trim($_GET['name']) : '';

        $cafeModel = new Cafe;
        $cafes = $cafeModel->getCafesCard($district, $name);

        header('Content-Type: application/json');
        echo json_encode($cafes);
    }
}
$search = new SearchController;
$search->searchCafes();

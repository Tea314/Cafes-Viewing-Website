
<?php

require_once __DIR__.'/../models/Cafe.php';

class HomeController
{
    private $cafeModel;

    public function __construct()
    {
        $this->cafeModel = new Cafe;
    }

    public function getCafes($offset = 0, $limit = 3)
    {
        return $this->cafeModel->getCafes($offset, $limit);
    }
}

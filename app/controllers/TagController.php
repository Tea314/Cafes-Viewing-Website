
<?php
require_once __DIR__.'/../models/Tag.php';

class TagController
{
    private $tagModel;

    public function __construct()
    {
        $this->tagModel = new Tag;
    }

    public function getAllTags()
    {
        return $this->tagModel->getAllTags();
    }

    public function getTagsByCafeId($cafeId)
    {
        return $this->tagModel->getTagsByCafeId($cafeId);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetch_tags' && ! isset($_SESSION['is_admin'])) {
    $controller = new TagController;
    echo json_encode($controller->getAllTags());
}

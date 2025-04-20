<?php

require_once __DIR__.'/../../config/config.php';
require_once __DIR__.'/TagController.php';
require_once __DIR__.'/../../public/libs/bootstrap.php';

class LazyLoadController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function fetchCafes()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 3;
        $offset = ($page - 1) * $limit;

        $stmt = $this->conn->prepare('
            SELECT cafes.*, districts.name AS district_name 
            FROM cafes 
            JOIN districts ON cafes.district_id = districts.id 
            WHERE cafes.status = "approved"
            ORDER BY cafes.id ASC 
            LIMIT :limit OFFSET :offset
        ');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $cafes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tagController = new TagController;

        $response = [];
        foreach ($cafes as $index => &$cafe) {
            $folder = __DIR__.'/../../public/images/'.$cafe['folder_name'];
            $imageFiles = [];

            if (is_dir($folder)) {
                $files = scandir($folder);
                foreach ($files as $file) {
                    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                        $imageFiles[] = "images/{$cafe['folder_name']}/$file";
                    }
                }
            }
            /* echo implode(', ', $imageFiles); */
            if (empty($imageFiles)) {
                $imageFiles[] = 'assets/default.png';
            }

            $tags = $tagController->getTagsByCafeId($cafe['id']);
            require_once __DIR__.'/../../public/api/translate_district.php';
            $globalIndex = $offset + $index;
            $processedCafe = [
                'id' => $cafe['id'],
                'name' => htmlspecialchars($cafe['name']),
                'description' => htmlspecialchars($cafe['description']),
                'address' => htmlspecialchars($cafe['address']),
                'price_range' => htmlspecialchars($cafe['price_range']),
                'folder_name' => htmlspecialchars($cafe['folder_name']),
                'district_name_en' => htmlspecialchars($cafe['district_name']),
                'district_name_vn' => translateDistrictName(htmlspecialchars($cafe['district_name'])),

                'images' => $imageFiles,
                'tags' => $tags,
            ];
            ob_start();
            $cafe = $processedCafe;
            include __DIR__.'/../views/partials/cafeModal.php';
            $modalHtml = ob_get_clean();

            $response[] = [
                'id' => $processedCafe['id'],
                'name' => $processedCafe['name'],
                'description' => $processedCafe['description'],
                'address' => $processedCafe['address'],
                'price_range' => $processedCafe['price_range'],
                'folder_name' => $processedCafe['folder_name'],
                'district_name_en' => $processedCafe['district_name_en'],
                'district_name_vn' => $processedCafe['district_name_vn'],
                'images' => $processedCafe['images'],
                'tags' => $tags,
                'modalHtml' => $modalHtml,
                'index' => $processedCafe['id'],
                /* 'index' => $globalIndex, */
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
$controller = new LazyLoadController;
$controller->fetchCafes();

<?php

/* session_start(); */
require_once __DIR__.'/../models/Cafe.php';
require_once __DIR__.'/../models/Tag.php';
class CafeController
{
    public function fetchCafes()
    {
        $district = isset($_GET['district']) ? intval($_GET['district']) : 0;
        $name = isset($_GET['name']) ? trim($_GET['name']) : '';

        $cafeModel = new Cafe;
        $cafes = $cafeModel->getCafesCard($district, $name);

        $formattedCafes = array_map(function ($cafe) {
            $folder = __DIR__.'/../../public/images/'.$cafe['folder_name'];
            $imagePath = 'default.png';

            if (is_dir($folder)) {
                $files = scandir($folder);
                foreach ($files as $file) {
                    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                        $imagePath = "images/{$cafe['folder_name']}/$file";
                        break;
                    }
                }
            }

            return [
                'id' => $cafe['id'],
                'name' => $cafe['name'],
                'description' => $cafe['description'],
                'district' => $cafe['district_name'],
                'image' => $imagePath,
            ];
        }, $cafes);

        header('Content-Type: application/json');
        echo json_encode($formattedCafes);
        exit;
    }

    public function fetchCafesById($cafeId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('
                SELECT c.id, c.name, c.address, c.price_range, c.description,c.district_id, d.name as district_name
            FROM cafes c 
            LEFT JOIN districts d ON c.district_id = d.id
            WHERE c.id = :id
            ');
        $stmt->bindValue(':id', $cafeId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function fetchCafesForAdmin()
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('
            SELECT c.id, c.name, c.address, c.price_range, c.status, d.name as district_name
            FROM cafes c
            JOIN districts d ON c.district_id = d.id
            WHERE c.status = :status
        ');
        $stmt->execute([':status' => 'approved']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCafe()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        if (! isset($_SESSION['is_admin']) || ! $_SESSION['is_admin']) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (! $input || ! isset($input['cafe_id'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid input data']);
            exit;
        }

        try {
            $cafeModel = new Cafe;

            if ($cafeModel->existsWithNameExceptId($input['name'], $input['cafe_id'])) {
                echo json_encode(['success' => false, 'message' => 'Cafe name already exists']);
                exit;
            }

            $cafeData = [
                'cafe_id' => $input['cafe_id'],
                'name' => $input['name'],
                'description' => $input['description'],
                'address' => $input['address'],
                'district_id' => $input['district_id'],
                'price_range' => $input['price_range'],
            ];

            $cafeModel->updateCafe($cafeData);

            // Update tags nếu có
            if (isset($input['tags']) && is_array($input['tags'])) {
                $cafeModel->updateCafeTags($input['cafe_id'], $input['tags']);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Cafe updated successfully',
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error updating cafe: '.$e->getMessage(),
            ]);
        }
        exit;
    }

    public function deleteCafe()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        if (! isset($_SESSION['is_admin']) || ! $_SESSION['is_admin']) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (! isset($input['cafe_id']) || ! is_numeric($input['cafe_id'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid cafe ID']);
            exit;
        }

        $cafe_id = (int) $input['cafe_id'];

        try {
            $conn = Database::getConnection();

            $checkStmt = $conn->prepare('SELECT COUNT(*) FROM cafes WHERE id = :id');
            $checkStmt->execute([':id' => $cafe_id]);
            $exists = $checkStmt->fetchColumn();

            if (! $exists) {
                echo json_encode(['success' => false, 'message' => 'Cafe not found']);
                exit;
            }

            $stmt = $conn->prepare('DELETE FROM cafes WHERE id = :id');
            $stmt->execute([':id' => $cafe_id]);

            echo json_encode(['success' => true, 'message' => 'Cafe deleted successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'An error occurred: '.$e->getMessage()]);
        }
        exit;
    }

    public function fetchPendingCafes()
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('
            SELECT c.id, c.name, c.address, c.price_range, c.status, d.name as district_name
            FROM cafes c
            JOIN districts d ON c.district_id = d.id
            WHERE c.status = :status
        ');
        $stmt->execute([':status' => 'pending']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CafeController;
    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'update') {
            $controller->updateCafe();
            exit;
        } elseif ($_GET['action'] === 'delete') {
            $controller->deleteCafe();
            exit;
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new CafeController;

    if (! isset($_SESSION['is_admin']) && ! isset($_GET['action'])) {
        $controller->fetchCafes();
        exit;
    }

    if (isset($_GET['action']) && $_GET['action'] === 'fetchById' && isset($_GET['id'])) {
        $cafeId = intval($_GET['id']);
        $cafe = $controller->fetchCafesById($cafeId);

        if ($cafe) {
            $tag = new Tag;
            $tagReturn = $tag->getTagsByCafeId($cafeId);
            $cafe['tags'] = array_map(function ($tag) {
                return $tag['name'];
            }, $tagReturn);
            echo json_encode(['success' => true, 'cafe' => $cafe]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cafe not found']);
        }
        exit;
    }

}

<?php

require_once __DIR__.'/../../config/database.php';
require_once __DIR__.'/../controllers/TagController.php';
require_once __DIR__.'/../../public/libs/bootstrap.php';
class Cafe
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function seedData()
    {
        $stmt = $this->conn->query('SELECT COUNT(*) FROM cafes');
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $sql = "
                INSERT INTO cafes (name, address, district_id, price_range, description, folder_name) VALUES
                ('Yen', '98 Nguyen Dinh Chieu', 1, 'Medium', 'A serene green space.', 'yen'),
                ('Centro Bean', '25AB Nguyen Binh Khiem', 1, 'Medium', 'Cozy place for work.', 'centro_bean'),
                ('Delab', '193a Hai Ba Trung', 3, 'Low', 'Beautiful dusk.', 'delab'),
                ('Po', '160/27 Bui Dinh Tuy', 13, 'Low', 'Old design with vintage vibes.', 'po'),
                ('Aramour', '7 Le Van Mien', 2, 'High', 'Nature and working vibes.', 'aramour');
            ";
            $this->conn->exec($sql);
        }
    }

    public function getCafes($offset = 0, $limit = 3)
    {
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

        // Khởi tạo TagController để lấy tags
        $tagController = new TagController;

        // Chuẩn bị dữ liệu trả về
        $response = [];
        foreach ($cafes as $index => &$cafe) {
            // Lấy danh sách ảnh
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
            if (empty($imageFiles)) {
                $imageFiles[] = 'assets/default.png';
            }
            $tags = $tagController->getTagsByCafeId($cafe['id']);
            require_once __DIR__.'/../../public/api/translate_district.php';
            /* $globalIndex = $offset + $index; */
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
            // Tạo HTML cho modal từ cafeModal.php
            ob_start();
            /* $index = $globalIndex; // Gán index cho modal */
            $cafe = $processedCafe;
            include __DIR__.'/../views/partials/cafeModal.php';

            $modalHtml = ob_get_clean();

            // Thêm dữ liệu vào response
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

        return $response;
    }

    public function searchCafeNames($query)
    {
        $query = strtolower($query);
        $stmt = $this->conn->prepare('
            SELECT name FROM cafes 
            WHERE LOWER(name) REGEXP :pattern AND status = "approved" 
            ORDER BY name ASC 
            LIMIT 10
        ');
        $pattern = '(^'.$query.'| [[:space:]]'.$query.')';
        $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getCafesCard($district = 0, $name = '')
    {
        $query = 'SELECT cafes.*, districts.name AS district_name 
                  FROM cafes 
                    JOIN districts ON cafes.district_id = districts.id 
        ';
        $params = [];
        $conditions = ['cafes.status = "approved"'];

        if ($district > 0) {
            $conditions[] = 'cafes.district_id = :district';
            $params[':district'] = $district;
        }

        if (! empty($name)) {
            $namePattern = '(^'.preg_quote(strtolower($name), '/').'| [[:space:]]'.preg_quote(strtolower($name), '/').')';
            $conditions[] = 'LOWER(cafes.name) REGEXP :pattern';
            $params[':pattern'] = $namePattern;
        }

        if (! empty($conditions)) {
            $query .= ' WHERE '.implode(' AND ', $conditions);
        }

        $query .= ' ORDER BY district_name ASC LIMIT 10';

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $cafes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tagController = new TagController;

        // Chuẩn bị dữ liệu trả về
        $response = [];
        foreach ($cafes as $index => &$cafe) {
            // Lấy danh sách ảnh
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

            // Nếu không có ảnh, gán ảnh mặc định
            if (empty($imageFiles)) {
                $imageFiles[] = 'assets/default.png';
            }

            // Lấy tags cho café
            $tags = $tagController->getTagsByCafeId($cafe['id']);

            // Tạo index toàn cục (dựa trên offset)
            require_once __DIR__.'/../../public/api/translate_district.php';
            /* $globalIndex = $index; */
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
            // Tạo HTML cho modal từ cafeModal.php
            ob_start();
            /* $index = $globalIndex; // Gán index cho modal */
            $cafe = $processedCafe;
            include __DIR__.'/../views/partials/cafeModal.php';
            $modalHtml = ob_get_clean();

            // Thêm dữ liệu vào response
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

        return $response;

    }

    public function existsWithNameExceptId($name, $id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT COUNT(*) FROM cafes WHERE name = :name AND id != :id');
        $stmt->execute([':name' => $name, ':id' => $id]);

        return $stmt->fetchColumn() > 0;
    }

    public function updateCafe($inputs)
    {
        $conn = Database::getConnection();
        $folder_name = preg_replace('/[^a-zA-Z0-9]/', '_', strtolower($inputs['name']));
        $stmt = $conn->prepare('
            UPDATE cafes
            SET name = :name, 
                folder_name = :folder_name, 
                description = :description, 
                address = :address, 
                district_id = :district_id, 
                price_range = :price_range
            WHERE id = :id
        ');

        $stmt->execute([
            ':id' => $inputs['cafe_id'],
            ':name' => $inputs['name'],
            ':folder_name' => $folder_name,
            ':description' => $inputs['description'],
            ':address' => $inputs['address'],
            ':district_id' => $inputs['district_id'],
            ':price_range' => $inputs['price_range'],
        ]);
    }

    public function updateCafeTags($cafe_id, $tags)
    {
        $conn = Database::getConnection();

        $stmt = $conn->prepare('DELETE FROM cafe_tags WHERE cafe_id = :cafe_id');
        $stmt->execute([':cafe_id' => $cafe_id]);

        foreach ($tags as $tag) {
            if (! empty($tag)) {
                $checkStmt = $conn->prepare('SELECT id FROM tags WHERE name = :name');
                $checkStmt->execute([':name' => $tag]);
                $existingTag = $checkStmt->fetch(PDO::FETCH_ASSOC);

                if ($existingTag) {
                    $tag_id = $existingTag['id'];
                } else {
                    $insertStmt = $conn->prepare('INSERT INTO tags (name) VALUES (:name)');
                    $insertStmt->execute([':name' => $tag]);
                    $tag_id = $conn->lastInsertId();
                }

                $linkStmt = $conn->prepare('INSERT INTO cafe_tags (cafe_id, tag_id) VALUES (:cafe_id, :tag_id)');
                $linkStmt->execute([':cafe_id' => $cafe_id, ':tag_id' => $tag_id]);
            }
        }
    }

    public function updateCafeFromReport($reportId)
    {
        $conn = Database::getConnection();

        $stmt = $conn->prepare('SELECT * FROM reports WHERE id = :id');
        $stmt->bindParam(':id', $reportId, PDO::PARAM_INT);
        $stmt->execute();
        $report = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $report) {
            return false;
        }

        $field = $report['report_type'];
        $value = $report['proposed_value'];
        $cafeId = $report['cafe_id'];

        $allowedFields = ['address', 'price_range', 'name', 'description'];
        if (! in_array($field, $allowedFields)) {
            return false;
        }

        $query = "UPDATE cafes SET $field = :value WHERE id = :cafe_id";
        $updateStmt = $conn->prepare($query);
        $updateStmt->bindParam(':value', $value);
        $updateStmt->bindParam(':cafe_id', $cafeId, PDO::PARAM_INT);

        return $updateStmt->execute();
    }
}

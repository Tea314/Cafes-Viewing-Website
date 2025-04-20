
<?php
require_once __DIR__.'/../../config/database.php';

class Tag
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function seedData()
    {
        $stmt = $this->conn->query('SELECT COUNT(*) FROM tags');
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $sql = "
                INSERT INTO tags (name) VALUES 
                ('Studying Space'), ('Vibing'), ('Yapping'), ('Nature');
            ";
            $this->conn->exec($sql);
        }
    }

    public function getAllTags()
    {
        $stmt = $this->conn->query('SELECT * FROM tags');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagsByCafeId($cafeId)
    {
        $stmt = $this->conn->prepare('
        SELECT t.name 
        FROM tags t
        JOIN cafe_tags ct ON t.id = ct.tag_id
        WHERE ct.cafe_id = :cafe_id
    ');
        $stmt->execute(['cafe_id' => $cafeId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

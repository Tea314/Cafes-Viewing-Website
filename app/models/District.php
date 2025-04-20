
<?php
require_once __DIR__.'/../../config/database.php';

class District
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function seedData()
    {
        $stmt = $this->conn->query('SELECT COUNT(*) FROM districts');
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $sql = "
                INSERT INTO districts (id, name) VALUES
                (1, 'District 1'), (2, 'District 2'), (3, 'District 3'),
                (4, 'District 4'), (5, 'District 5'), (6, 'District 6'),
                (7, 'District 7'), (8, 'District 8'), (9, 'District 9'),
                (10, 'District 10'), (11, 'District 11'), (12, 'District 12'),
                (13, 'Binh Thanh District'), (14, 'Go Vap District'),
                (15, 'Phu Nhuan District'), (16, 'Tan Binh District'),
                (17, 'Tan Phu District');
            ";
            $this->conn->exec($sql);
        }
    }
}
?>

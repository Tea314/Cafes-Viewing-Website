
<?php
class Database
{
    private static $instance = null;

    private $conn;

    private $host = 'localhost';

    private $dbname = 'cafe_review';

    private $username = 'root';

    private $password = '';

    private function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit('Database connection error: '.$e->getMessage());
        }
    }

    public static function getConnection()
    {
        if (self::$instance == null) {
            self::$instance = new Database;
        }

        return self::$instance->conn;
    }
}
?>

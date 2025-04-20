<?php
require_once __DIR__ . '/../../config/database.php';

class Save
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function alreadyPinned($user_id, $cafe_id)
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM cafe_likes WHERE user_id = ? AND cafe_id = ?');
        $stmt->execute([$user_id, $cafe_id]);
        return $stmt->fetchColumn() > 0; 
    }

    public function saveCafe($user_id, $cafe_id)
    {
        $alreadyPinned = $this->alreadyPinned($user_id, $cafe_id);
        if ($alreadyPinned) {
            $stmt = $this->conn->prepare('DELETE FROM cafe_likes WHERE user_id = ? AND cafe_id = ?');
            $stmt->execute([$user_id, $cafe_id]);
            return ['success' => true, 'pinned' => false, 'message' => 'Cafe unpinned'];
        } else {
            $stmt = $this->conn->prepare('INSERT INTO cafe_likes (user_id, cafe_id) VALUES (?, ?)');
            $stmt->execute([$user_id, $cafe_id]);
            return ['success' => true, 'pinned' => true, 'message' => 'Cafe pinned'];
        }
    }

    public function saveStatus($user_id, $cafe_id)
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM cafe_likes WHERE user_id = ? AND cafe_id = ?');
        $stmt->execute([$user_id, $cafe_id]);
        $pinned = $stmt->fetchColumn() > 0;
        return ['pinned' => $pinned];
    }
}

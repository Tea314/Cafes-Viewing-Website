<?php

require __DIR__.'/../public/libs/bootstrap.php';
require_once __DIR__.'/../config/config.php';

if (! is_user_logged_in() || ! isset($_SESSION['is_admin']) || ! $_SESSION['is_admin']) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'You do not have permission to perform this action.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $cafe_id = $data['cafe_id'] ?? null;

    if (! $cafe_id) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid café ID.']);
        exit;
    }

    $conn = Database::getConnection();
    $stmt = $conn->prepare('UPDATE cafes SET status = :status WHERE id = :id');
    $stmt->execute([':status' => 'approved', ':id' => $cafe_id]);

    if ($stmt->rowCount() > 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Café approved successfully!']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to approve café.']);
    }
    exit;
}

header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Invalid request method.']);

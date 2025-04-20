<?php

require_once __DIR__.'/../models/Save.php';

class SaveController
{
    private $saveModel;

    public function __construct()
    {
        $this->saveModel = new Save;
    }

    public function handleSave()
    {
        session_start();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $user_id = $_SESSION['user_id'] ?? null;
        $cafe_id = $_POST['cafe_id'] ?? null;

        if (! $user_id) {
            echo json_encode(['success' => false, 'message' => 'Please log in to pin this cafe']);
            exit;
        }

        if (! $cafe_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid cafe ID']);
            exit;
        }

        $result = $this->saveModel->saveCafe($user_id, $cafe_id);
        echo json_encode($result);
        exit;
    }

    public function checkSaveStatus()
    {
        session_start();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $user_id = $_SESSION['user_id'] ?? null;
        $cafe_id = $_POST['cafe_id'] ?? null;

        if (! $user_id || ! $cafe_id) {
            echo json_encode(['pinned' => false]);
            exit;
        }

        $result = $this->saveModel->saveStatus($user_id, $cafe_id);
        echo json_encode($result);
        exit;
    }
}

$controller = new SaveController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'save'; 

    if ($action === 'save') {
        $controller->handleSave();
    } elseif ($action === 'check') {
        $controller->checkSaveStatus();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }
}

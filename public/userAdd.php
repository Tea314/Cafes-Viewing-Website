<?php

require __DIR__.'/../public/libs/bootstrap.php';
require_once __DIR__.'/../config/config.php';

if (! is_user_logged_in()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => ['required' => 'Please log in to add a café.']]);
    exit;
}

$errors = [];
$inputs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'name' => 'string | required | between:1,100',
        'description' => 'string | required | between:10,500',
        'address' => 'string | required | between:5,255',
        'price_range' => 'string | required | in:Low,Medium,High',
        'district_id' => 'int | required',
        'tags' => 'string | between:0,255',
    ];

    $sanitizeFields = [];
    $validationRules = [];
    foreach ($fields as $field => $rule) {
        $rulesArray = array_map('trim', explode('|', $rule));
        $sanitizeFields[$field] = array_shift($rulesArray);
        $validationRules[$field] = $rulesArray;
    }

    $inputs = sanitize($_POST, $sanitizeFields);

    foreach ($validationRules as $field => $rules) {
        $value = $inputs[$field] ?? '';

        foreach ($rules as $rule) {
            if ($rule === 'required' && empty($value)) {
                $errors[$field] = ucfirst($field).' is required.';
            }

            if (strpos($rule, 'between:') === 0) {
                $limits = explode(',', str_replace('between:', '', $rule));
                $min = (int) $limits[0];
                $max = (int) $limits[1];
                $length = strlen($value);
                if ($length < $min || $length > $max) {
                    $errors[$field] = ucfirst($field)." must be between $min and $max characters.";
                }
            }

            if (strpos($rule, 'in:') === 0) {
                $options = array_map('trim', explode(',', str_replace('in:', '', $rule)));
                if (! in_array(trim($value), $options)) {
                    $errors[$field] = ucfirst($field).' must be one of: '.implode(', ', $options).'.';
                }
            }
        }
    }

    if (empty($inputs['name']) || empty($inputs['address']) || empty($inputs['district_id'])) {
        $errors['required'] = 'Café name, address, and district are required.';
    }

    if (empty($errors)) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT COUNT(*) FROM cafes WHERE name = :name');
        $stmt->execute([':name' => $inputs['name']]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $errors['name'] = 'Café with this name already exists.';
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        $folder_name = preg_replace('/[^a-zA-Z0-9]/', '_', strtolower($inputs['name']));
        $conn = Database::getConnection();
        $stmt = $conn->prepare('
            INSERT INTO cafes (name, folder_name, description, address, district_id, price_range, status)
            VALUES (:name, :folder_name, :description, :address, :district_id, :price_range, :status)
        ');
        $stmt->execute([
            ':name' => $inputs['name'],
            ':folder_name' => $folder_name,
            ':description' => $inputs['description'],
            ':address' => $inputs['address'],
            ':district_id' => $inputs['district_id'],
            ':price_range' => $inputs['price_range'],
            ':status' => 'pending',
        ]);

        if (! empty($inputs['tags'])) {
            $cafe_id = $conn->lastInsertId();
            $tags = array_map('trim', explode(',', $inputs['tags']));

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
                    $checkLinkStmt = $conn->prepare('SELECT COUNT(*) FROM cafe_tags WHERE cafe_id = :cafe_id AND tag_id = :tag_id');
                    $checkLinkStmt->execute([':cafe_id' => $cafe_id, ':tag_id' => $tag_id]);
                    $linkExists = $checkLinkStmt->fetchColumn();

                    if (! $linkExists) {
                        $linkStmt = $conn->prepare('INSERT INTO cafe_tags (cafe_id, tag_id) VALUES (:cafe_id, :tag_id)');
                        $linkStmt->execute([':cafe_id' => $cafe_id, ':tag_id' => $tag_id]);
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Café submitted for approval!']);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }
}

header('Content-Type: application/json');
echo json_encode(['success' => false, 'errors' => ['required' => 'Invalid request method.']]);

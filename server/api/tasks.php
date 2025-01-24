<?php
// CRUD
include '../config.php';

header("Content-Type: application/json");

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        getTasks($pdo);
        break;
    case 'POST':
        createTask($pdo);
        break;
    case 'PUT':
        editTask($pdo);
        break;
    case 'DELETE':
        deleteTask($pdo);
        break;
    
    default:
        echo json_encode(["error" => "405 Method Not Allowed"]);
        break;
}

function getTasks($pdo) {
    $user_id = $_GET['user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(['error' => '400 Bad Request. User_id must be not null']);
        return;
    }
    $request = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
    $request->execute([$user_id]);
    echo json_encode($request->fetchAll(PDO::FETCH_ASSOC));
}

function createTask($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['user_id']) || empty($data['title']) || empty($data['end_date'])) {
        echo json_encode(['error' => '400 Bad Request. Not enough data']);
        return;
    }
    $request = $pdo->prepare("INSERT INTO tasks (user_id, title, description, end_date) VALUES (?, ?, ?, ?)");
    $request->execute([$data['user_id'], $data['title'], $data['description'] ?? null, $data['end_date']]);
    echo json_encode(["success" => "201 Created. Task Created", "task_id" => $pdo->lastInsertId()]);
}

function editTask($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['id']) || empty($data['user_id']) || empty($data['title']) || empty($data['end_date'])) {
        echo json_encode(['error' => '400 Bad Request. Not enough data']);
        return;
    }
    $request = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, end_date = ?, status = ? WHERE id = ? AND user_id = ?");
    $request->execute([$data['title'], $data['description'] ?? null, $data['end_date'], $data['status'] ?? 'in process', $data['id'], $data['user_id']]);
    echo json_encode(["success" => "200 OK. Task updated"]);
}

function deleteTask($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['id']) || empty($data['user_id'])) {
        echo json_encode(['error' => '400 Bad Request. Not enough data']);
        return;
    }
    $request = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $request->execute([$data['id'], $data['user_id']]);
    echo json_encode(["success" => "200 OK. Task deleted"]);
}
<?php
require_once __DIR__ . '/../controllers/TestController.php';

$controller = new TestController();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';


if ($method === 'GET' && $action === 'test') {
    $controller->getAllTest();
} else {
    echo json_encode(['error' => 'Invalid request']);
}
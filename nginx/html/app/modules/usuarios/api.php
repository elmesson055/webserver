<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/database.php';
require_once __DIR__ . '/UsuariosController.php';

header('Content-Type: application/json');

$controller = new UsuariosController();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $filters = [
                'search' => $_GET['search'] ?? '',
                'status' => $_GET['status'] ?? '',
                'role' => $_GET['role'] ?? '',
                'department' => $_GET['department'] ?? ''
            ];
            echo json_encode($controller->getUsers($page, $filters));
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'])) {
                echo json_encode($controller->updateUser($data['id'], $data));
            } else {
                echo json_encode($controller->createUser($data));
            }
            break;

        case 'PUT':
            if ($action === 'toggle-status') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($controller->toggleStatus($data['id']));
            } elseif ($action === 'reset-password') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($controller->resetPassword($data['id']));
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

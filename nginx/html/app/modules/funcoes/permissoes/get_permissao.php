<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/modules/auth/session.php';

// Verifica a sessão
check_session();

// Verifica se o usuário tem permissão
if (!check_user_permission('config.funcoes')) {
    http_response_code(403);
    exit('Acesso negado');
}

// Verifica se o ID foi fornecido
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit('ID não fornecido');
}

$id = intval($_GET['id']);

// Conexão com o banco
$conn = get_database_connection();

// Consulta SQL
$sql = "SELECT id, nome, descricao, modulo FROM permissoes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Permissão não encontrada']);
}

$stmt->close();
$conn->close();
?>

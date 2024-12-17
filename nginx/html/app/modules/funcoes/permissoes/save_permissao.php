<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/modules/auth/session.php';

// Configura o header para retornar JSON
header('Content-Type: application/json');

// Verifica a sessão
check_session();

// Verifica se o usuário tem permissão
if (!check_user_permission('config.funcoes')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acesso negado']);
    exit;
}

// Verifica se os dados foram enviados
if (!isset($_POST['id']) || !isset($_POST['nome']) || !isset($_POST['descricao']) || !isset($_POST['modulo'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

// Sanitiza os dados
$id = intval($_POST['id']);
$nome = trim($_POST['nome']);
$descricao = trim($_POST['descricao']);
$modulo = trim($_POST['modulo']);

// Valida os dados
if (empty($nome) || empty($descricao) || empty($modulo)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Campos obrigatórios não preenchidos']);
    exit;
}

// Conexão com o banco
$conn = get_database_connection();

// Verifica se o nome já existe (exceto para o mesmo ID)
$sql = "SELECT id FROM permissoes WHERE nome = ? AND id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $nome, $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    $conn->close();
    echo json_encode(['success' => false, 'message' => 'Já existe uma permissão com este nome']);
    exit;
}

// Atualiza a permissão
$sql = "UPDATE permissoes SET nome = ?, descricao = ?, modulo = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $descricao, $modulo, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

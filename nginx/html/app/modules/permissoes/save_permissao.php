<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/modules/auth/session.php';

// Configura o header para retornar JSON
header('Content-Type: application/json');

// Verifica a sessão
check_session();

// Verifica se o usuário tem permissão
if (!check_user_permission('funcoes.edit')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acesso negado']);
    exit;
}

// Verifica se os dados foram enviados
if (!isset($_POST['id']) || !isset($_POST['descricao']) || !isset($_POST['funcoes'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

// Sanitiza os dados
$id = intval($_POST['id']);
$descricao = trim($_POST['descricao']);
$funcoes = is_array($_POST['funcoes']) ? array_map('intval', $_POST['funcoes']) : [];

// Valida os dados
if (empty($descricao)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'A descrição é obrigatória']);
    exit;
}

// Conexão com o banco
$conn = get_database_connection();

try {
    // Inicia a transação
    $conn->begin_transaction();

    // Atualiza a descrição da permissão
    $sql = "UPDATE permissoes SET descricao = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $descricao, $id);
    $stmt->execute();

    // Remove todas as associações existentes desta permissão
    $sql = "DELETE FROM funcao_permissoes WHERE permissao_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Insere as novas associações
    if (!empty($funcoes)) {
        $sql = "INSERT INTO funcao_permissoes (funcao_id, permissao_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($funcoes as $funcao_id) {
            $stmt->bind_param("ii", $funcao_id, $id);
            $stmt->execute();
        }
    }

    // Commit da transação
    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // Rollback em caso de erro
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>

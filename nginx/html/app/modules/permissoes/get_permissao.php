<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/modules/auth/session.php';

// Verifica a sessão
check_session();

// Verifica se o usuário tem permissão
if (!check_user_permission('funcoes.view')) {
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

// Busca os dados da permissão
$sql = "SELECT id, nome, descricao, modulo FROM permissoes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $permissao = $result->fetch_assoc();
    
    // Busca todas as funções e marca as que têm essa permissão
    $sql = "SELECT f.id, f.nome, 
            CASE WHEN fp.funcao_id IS NOT NULL THEN 1 ELSE 0 END as tem_permissao
            FROM funcoes f
            LEFT JOIN funcao_permissoes fp ON f.id = fp.funcao_id AND fp.permissao_id = ?
            WHERE f.ativo = 1
            ORDER BY f.nome";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $funcoes = array();
    while ($row = $result->fetch_assoc()) {
        $funcoes[] = array(
            'id' => $row['id'],
            'nome' => $row['nome'],
            'tem_permissao' => $row['tem_permissao']
        );
    }
    
    $permissao['funcoes'] = $funcoes;
    
    echo json_encode($permissao);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Permissão não encontrada']);
}

$stmt->close();
$conn->close();
?>

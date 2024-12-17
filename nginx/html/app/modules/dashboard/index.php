<?php
// Definir caminho base se ainda não estiver definido
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:/webserver/nginx/html');
}

// Carregar configurações globais
require_once BASE_PATH . '/config/config.php';

// Carregar autoloader
require_once BASE_PATH . '/app/autoload.php';

// Verificar se usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: /app/modules/auth/views/login.php');
    exit();
}

try {
    // Testar conexão com o banco
    $db = \App\Core\Database::getInstance();
    
    // Buscar informações do usuário
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT nome, sobrenome, funcao_id FROM usuarios WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception('Usuário não encontrado');
    }

    // Definir variáveis para a view
    $nome_completo = $user['nome'] . ' ' . $user['sobrenome'];
    $funcao_id = $user['funcao_id'];

    // Buscar estatísticas do dashboard
    $stmt = $db->prepare("
        SELECT 
            (SELECT COUNT(*) FROM usuarios WHERE status = 'ativo') as total_usuarios,
            (SELECT COUNT(*) FROM veiculos WHERE status = 'ativo') as total_veiculos,
            (SELECT COUNT(*) FROM documentos_transporte WHERE status = 'pendente') as docs_pendentes
    ");
    $stmt->execute();
    $stats = $stmt->fetch();

} catch (Exception $e) {
    error_log("Erro no dashboard: " . $e->getMessage());
    $_SESSION['error'] = "Ocorreu um erro ao carregar o dashboard. Por favor, tente novamente.";
    header('Location: /app/modules/auth/views/login.php');
    exit();
}

// Carregar a view do dashboard
require_once __DIR__ . '/views/dashboard.php';
?>

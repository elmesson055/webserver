<?php
// Incluir configurações
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/session.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/database.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/config.php';
require_once dirname(dirname(dirname(__FILE__))) . '/Core/Database.php';
require_once dirname(dirname(dirname(__FILE__))) . '/functions.php';

use App\Core\Database;

error_log("Dashboard - Verificando sessão...");
error_log("Session ID: " . session_id());
error_log("Session Data: " . print_r($_SESSION, true));

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user'])) {
    error_log("Usuário não está logado. Redirecionando para login...");
    header('Location: /app/modules/auth/views/login.php');
    exit();
}

// Buscar informações do usuário
try {
    $db = new Database();
    $stmt = $db->prepare("
        SELECT u.*, f.nome as funcao_nome
        FROM usuarios u
        LEFT JOIN funcoes f ON u.funcao_id = f.id
        WHERE u.id = :user_id
    ");
    
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        error_log("Usuário não encontrado no banco de dados.");
        session_unset();
        session_destroy();
        header('Location: /app/modules/auth/views/login.php');
        exit();
    }
} catch (Exception $e) {
    error_log("Erro ao buscar informações do usuário: " . $e->getMessage());
    $error = "Erro ao carregar informações do usuário.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Logística</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Bem-vindo, <?php echo htmlspecialchars($user['nome_usuario']); ?>!</h1>
            <div class="user-info">
                <span>Função: <?php echo htmlspecialchars($user['funcao_nome'] ?? 'N/A'); ?></span>
                <a href="/app/modules/auth/logout.php">Sair</a>
            </div>
        </header>

        <main>
            <div class="dashboard-content">
                <!-- Conteúdo do dashboard aqui -->
                <h2>Painel de Controle</h2>
                <div class="dashboard-widgets">
                    <!-- Widgets e estatísticas aqui -->
                </div>
            </div>
        </main>
    </div>
</body>
</html>

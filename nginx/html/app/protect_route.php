<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/Middleware/AuthorizationMiddleware.php';

function protectRoute($requiredPermission = null) {
    global $db;  
    $authMiddleware = new \App\Middleware\AuthorizationMiddleware($db);
    
    // Log de depuração
    error_log("Protegendo rota. Permissão necessária: " . ($requiredPermission ?? 'Nenhuma'));
    error_log("Caminho atual: " . $_SERVER['PHP_SELF']);
    error_log("Dados da sessão: " . json_encode($_SESSION));
    
    // Verificar se usuário está logado
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user_id'])) {
        error_log("Redirecionando para login - Usuário não logado");
        header('Location: /login.php');
        exit();
    }

    // Se uma permissão específica for necessária
    if ($requiredPermission !== null) {
        try {
            $authMiddleware->restrictAccess($requiredPermission);
        } catch (Exception $e) {
            error_log("Erro ao verificar permissão: " . $e->getMessage());
            header('Location: /access_denied.php');
            exit();
        }
    }
}

// Exemplos de uso em outras páginas:
// require_once 'protect_route.php';
// protectRoute('dashboard_view');  // Para dashboard
// protectRoute('cost_view');       // Para página de custos
// protectRoute('user_management'); // Para gerenciamento de usuários
?>

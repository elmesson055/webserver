<?php
require_once dirname(dirname(dirname(__DIR__))) . '/app/config/config.php';

use App\Core\Database;

// Iniciar sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    // Configurações de segurança da sessão
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 1);
    
    session_start();
    session_regenerate_id();
}

/**
 * Verifica se o usuário está logado
 * Se não estiver, redireciona para a página de login
 */
function check_session() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /app/modules/auth/login.php");
        exit;
    }
}

/**
 * Verifica se o usuário tem uma determinada permissão
 * @param string $permissao Nome da permissão a ser verificada
 * @return bool True se tem permissão, False caso contrário
 */
function check_user_permission($permissao) {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    try {
        $db = Database::getInstance();
        
        // Busca as permissões do usuário através das funções associadas
        $sql = "SELECT COUNT(*) as tem_permissao 
                FROM usuarios u
                JOIN funcoes f ON u.funcao_id = f.id
                JOIN funcao_permissao fp ON f.id = fp.funcao_id
                JOIN permissoes p ON fp.permissao_id = p.id
                WHERE u.id = :user_id 
                AND p.nome = :permissao 
                AND u.status = 'Ativo' 
                AND f.ativo = TRUE";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], \PDO::PARAM_INT);
        $stmt->bindValue(':permissao', $permissao, \PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int)$result['tem_permissao'] > 0;
        
    } catch (\Exception $e) {
        error_log("Erro ao verificar permissão: " . $e->getMessage());
        return false;
    }
}

/**
 * Obtém todas as permissões de um usuário
 * @param int|null $userId ID do usuário (opcional, usa o usuário da sessão se não informado)
 * @return array Lista de permissões
 */
function get_user_permissions($userId = null) {
    $userId = $userId ?? $_SESSION['user_id'] ?? null;
    
    if (!$userId) {
        return [];
    }

    try {
        $db = Database::getInstance();
        
        $sql = "SELECT DISTINCT p.nome
                FROM usuarios u
                JOIN funcoes f ON u.funcao_id = f.id
                JOIN funcao_permissao fp ON f.id = fp.funcao_id
                JOIN permissoes p ON fp.permissao_id = p.id
                WHERE u.id = :user_id 
                AND u.status = 'Ativo' 
                AND f.ativo = TRUE";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    } catch (\Exception $e) {
        error_log("Erro ao obter permissões: " . $e->getMessage());
        return [];
    }
}

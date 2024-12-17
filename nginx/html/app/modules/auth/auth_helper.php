<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';

/**
 * Verifica se o usuário tem uma determinada permissão
 * @param string $permission_code Código da permissão a ser verificada
 * @return bool True se o usuário tem a permissão, False caso contrário
 */
function check_user_permission($permission_code) {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    $user_id = $_SESSION['user_id'];
    $conn = get_database_connection();
    
    $sql = "SELECT COUNT(*) as has_permission 
            FROM usuarios u
            JOIN funcoes f ON u.funcao_id = f.id
            JOIN funcao_permissao fp ON f.id = fp.funcao_id
            JOIN permissoes p ON fp.permissao_id = p.id
            WHERE u.id = ? AND p.codigo = ? AND u.status = 'Ativo' AND f.status = 'Ativo' AND p.status = 'Ativo'";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $user_id, $permission_code);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stmt->close();
        $conn->close();
        
        return $row['has_permission'] > 0;
    } catch (Exception $e) {
        error_log("Erro ao verificar permissão: " . $e->getMessage());
        return false;
    }
}

/**
 * Verifica se o usuário está logado, caso contrário redireciona para o login
 */
function check_session() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /app/modules/auth/login.php');
        exit();
    }
}

/**
 * Verifica se o usuário tem permissão para acessar um módulo, caso contrário redireciona
 * @param string $permission_code Código da permissão necessária
 */
function check_module_permission($permission_code) {
    if (!check_user_permission($permission_code)) {
        header('Location: /app/modules/auth/unauthorized.php');
        exit();
    }
}

/**
 * Obtém todas as permissões de um usuário
 * @param int $user_id ID do usuário
 * @return array Lista de permissões do usuário
 */
function get_user_permissions($user_id) {
    $conn = get_database_connection();
    
    $sql = "SELECT DISTINCT p.codigo
            FROM usuarios u
            JOIN funcoes f ON u.funcao_id = f.id
            JOIN funcao_permissao fp ON f.id = fp.funcao_id
            JOIN permissoes p ON fp.permissao_id = p.id
            WHERE u.id = ? AND u.status = 'Ativo' AND f.status = 'Ativo' AND p.status = 'Ativo'";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $permissions = [];
        while ($row = $result->fetch_assoc()) {
            $permissions[] = $row['codigo'];
        }
        
        $stmt->close();
        $conn->close();
        
        return $permissions;
    } catch (Exception $e) {
        error_log("Erro ao obter permissões: " . $e->getMessage());
        return [];
    }
}

<?php
// Verificar se a sessão já está ativa
if (session_status() === PHP_SESSION_NONE) {
    // Configurações de segurança da sessão (antes de iniciar a sessão)
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 1);
    
    // Iniciar a sessão
    session_start();
    // Regenerar ID da sessão após iniciá-la
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

    $conn = get_database_connection();
    
    // Busca as permissões do usuário através das funções associadas
    $sql = "SELECT COUNT(*) as tem_permissao 
            FROM usuarios u
            JOIN funcoes f ON u.funcao_id = f.id
            JOIN funcao_permissoes fp ON f.id = fp.funcao_id
            JOIN permissoes p ON fp.permissao_id = p.id
            WHERE u.id = ? AND p.nome = ? AND u.ativo = 1 AND f.ativo = 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $_SESSION['user_id'], $permissao);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $row['tem_permissao'] > 0;
}
?>

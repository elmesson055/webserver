<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';

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
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $user_id, $permission_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $row['has_permission'] > 0;
}

function check_session() {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: /app/modules/auth/login.php');
        exit();
    }
}

<?php
require_once dirname(__DIR__) . '/app/Core/Database.php';

use App\Core\Database;

/**
 * Retorna uma conexão com o banco de dados
 * @return PDO
 */
function connectDB() {
    return Database::getInstance()->getConnection();
}

/**
 * Verifica se o usuário tem um determinado papel
 * @param string $roleName Nome do papel a ser verificado
 * @return bool True se o usuário tem o papel, False caso contrário
 */
function hasRole($roleName) {
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['funcao_id'])) {
        error_log('Usuário não autenticado ou sem funcao_id');
        return false;
    }

    try {
        $db = connectDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM funcoes WHERE id = ? AND nome = ?");
        $stmt->execute([$_SESSION['user']['funcao_id'], $roleName]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log('Erro ao verificar função do usuário: ' . $e->getMessage());
        return false;
    }
}

/**
 * Redireciona para uma URL específica
 * @param string $url URL para redirecionamento
 * @param array $params Parâmetros opcionais para adicionar à URL
 * @return void
 */
if (!function_exists('redirect')) {
    function redirect($url, $params = []) {
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        header("Location: $url");
        exit;
    }
}

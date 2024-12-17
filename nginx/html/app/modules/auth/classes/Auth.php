<?php
namespace App\Modules\Auth\Classes;

use App\Core\Database;
use Exception;
use PDO;

class Auth {
    private static $instance = null;
    private $db;

    private function __construct() {
        $this->db = Database::getInstance();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // Configurações de segurança da sessão
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', 1);
            
            session_start();
            session_regenerate_id();
        }
    }

    public function checkSession() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /app/modules/auth/login.php");
            exit;
        }
    }

    public function checkPermission($permissao) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        try {
            $sql = "SELECT COUNT(*) as tem_permissao 
                    FROM usuarios u
                    JOIN funcoes f ON u.funcao_id = f.id
                    JOIN funcao_permissao fp ON f.id = fp.funcao_id
                    JOIN permissoes p ON fp.permissao_id = p.id
                    WHERE u.id = ? AND p.nome = ? 
                    AND u.status = 'Ativo' 
                    AND f.ativo = TRUE";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$_SESSION['user_id'], $permissao]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return (int)$row['tem_permissao'] > 0;
        } catch (Exception $e) {
            error_log("Erro ao verificar permissão: " . $e->getMessage());
            return false;
        }
    }

    public function getUserPermissions($userId = null) {
        $userId = $userId ?? $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            return [];
        }

        try {
            $sql = "SELECT DISTINCT p.nome
                    FROM usuarios u
                    JOIN funcoes f ON u.funcao_id = f.id
                    JOIN funcao_permissao fp ON f.id = fp.funcao_id
                    JOIN permissoes p ON fp.permissao_id = p.id
                    WHERE u.id = ? 
                    AND u.status = 'Ativo' 
                    AND f.ativo = TRUE";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("Erro ao obter permissões: " . $e->getMessage());
            return [];
        }
    }
}

<?php
namespace App\Modules\Auth\Models;

use App\Core\Database;
use PDO;
use Exception;

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function authenticate($username, $password) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, nome_usuario, email, password_hash, nome, sobrenome, 
                       ativo, ultimo_login, funcao_id, status
                FROM usuarios 
                WHERE (email = :email OR nome_usuario = :username)
                AND status = 'ativo'
            ");
            
            $stmt->execute([
                'email' => $username,
                'username' => $username
            ]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                throw new Exception('Usuário não encontrado ou inativo.');
            }
            
            if (!password_verify($password, $user['password_hash'])) {
                throw new Exception('Senha incorreta.');
            }
            
            // Remover dados sensíveis antes de retornar
            unset($user['password_hash']);
            
            // Atualizar último login
            $this->updateLastLogin($user['id']);
            
            return $user;
            
        } catch (Exception $e) {
            error_log("Erro na autenticação: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateLastLogin($userId) {
        try {
            $stmt = $this->db->prepare("
                UPDATE usuarios 
                SET ultimo_login = NOW(),
                    atualizado_em = NOW()
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $userId]);
        } catch (Exception $e) {
            error_log("Erro ao atualizar último login: " . $e->getMessage());
            throw $e;
        }
    }
}

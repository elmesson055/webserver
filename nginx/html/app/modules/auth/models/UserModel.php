<?php
namespace App\Modules\Auth\Models;

use App\Core\Database;
use PDO;
use Exception;

class UserModel {
    private $db;

    public function __construct() {
        try {
            $this->db = Database::getInstance();
            error_log("Database connection established successfully");
        } catch (Exception $e) {
            error_log("Error connecting to database: " . $e->getMessage());
            throw $e;
        }
    }

    public function authenticate($username, $password) {
        try {
            error_log("Attempting authentication for username: " . $username);
            
            $sql = "SELECT id, nome_usuario, email, password_hash, sobrenome, 
                           funcao_id, status, ultimo_login
                    FROM usuarios 
                    WHERE (email = :username OR nome_usuario = :username)
                    AND status = 'Ativo'";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                error_log("User not found or inactive: " . $username);
                throw new Exception('Usuário não encontrado ou inativo.');
            }
            
            error_log("User found, verifying password");
            if (!password_verify($password, $user['password_hash'])) {
                error_log("Invalid password for user: " . $username);
                $this->registerFailedLogin($username);
                throw new Exception('Senha incorreta.');
            }
            
            error_log("Password verified successfully");
            
            // Remover dados sensíveis antes de retornar
            unset($user['password_hash']);
            
            // Atualizar último login
            $this->updateLastLogin($user['id']);
            error_log("Last login updated for user: " . $username);
            
            return $user;
            
        } catch (Exception $e) {
            error_log("Authentication error: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateLastLogin($userId) {
        try {
            $sql = "UPDATE usuarios SET ultimo_login = NOW() WHERE id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $result = $stmt->execute();
            error_log("Update last login result: " . ($result ? 'success' : 'failed'));
            return $result;
        } catch (Exception $e) {
            error_log("Error updating last login: " . $e->getMessage());
            throw $e;
        }
    }

    private function registerFailedLogin($username) {
        try {
            $ip = $_SERVER['REMOTE_ADDR'];
            error_log("Registering failed login attempt for user: " . $username . " from IP: " . $ip);
            
            $sql = "INSERT INTO tentativas_login (usuario_id, ip_address, tentativas) 
                    SELECT id, :ip, 1 FROM usuarios WHERE email = :username OR nome_usuario = :username
                    ON DUPLICATE KEY UPDATE 
                    tentativas = tentativas + 1,
                    bloqueado_ate = CASE 
                        WHEN tentativas >= 5 THEN DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                        ELSE NULL 
                    END";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ip', $ip, PDO::PARAM_STR);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $result = $stmt->execute();
            error_log("Failed login registration result: " . ($result ? 'success' : 'failed'));
        } catch (Exception $e) {
            error_log("Error registering failed login: " . $e->getMessage());
            // Don't throw the exception as this is a secondary operation
        }
    }
}

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
            error_log("Database connection established successfully in UserModel");
        } catch (Exception $e) {
            error_log("Error connecting to database in UserModel: " . $e->getMessage());
            throw new Exception("Erro ao conectar com o banco de dados. Por favor, tente novamente mais tarde.");
        }
    }

    public function authenticate($username, $password) {
        try {
            error_log("Attempting authentication for username: " . $username);
            
            // Primeiro, verificar se o usuário está bloqueado
            if ($this->isUserBlocked($username)) {
                throw new Exception('Conta temporariamente bloqueada devido a múltiplas tentativas de login. Tente novamente mais tarde.');
            }
            
            $sql = "SELECT u.id, u.nome_usuario, u.email, u.password_hash, u.sobrenome, 
                           u.funcao_id, u.status, u.ultimo_login
                    FROM usuarios u
                    WHERE (u.email = :username OR u.nome_usuario = :username)
                    AND u.status = 'Ativo'";
            
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
            
            // Limpar tentativas de login após sucesso
            $this->clearFailedLogins($username);
            
            // Remover dados sensíveis antes de retornar
            unset($user['password_hash']);
            
            // Atualizar último login
            $this->updateLastLogin($user['id']);
            error_log("Last login updated for user: " . $username);
            
            return $user;
            
        } catch (Exception $e) {
            error_log("Authentication error in UserModel: " . $e->getMessage());
            throw $e;
        }
    }

    private function isUserBlocked($username) {
        try {
            $sql = "SELECT bloqueado_ate 
                    FROM tentativas_login t
                    JOIN usuarios u ON t.usuario_id = u.id
                    WHERE (u.email = :username OR u.nome_usuario = :username)
                    AND bloqueado_ate > NOW()";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (Exception $e) {
            error_log("Error checking if user is blocked: " . $e->getMessage());
            return false; // Em caso de erro, permitir o login
        }
    }

    private function clearFailedLogins($username) {
        try {
            $sql = "DELETE t FROM tentativas_login t
                    JOIN usuarios u ON t.usuario_id = u.id
                    WHERE u.email = :username OR u.nome_usuario = :username";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Error clearing failed logins: " . $e->getMessage());
            // Não lançar exceção pois esta é uma operação secundária
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
            // Não lançar exceção pois esta é uma operação secundária
            return false;
        }
    }

    private function registerFailedLogin($username) {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            error_log("Registering failed login attempt for user: " . $username . " from IP: " . $ip);
            
            $sql = "INSERT INTO tentativas_login (usuario_id, ip_address, tentativas, data_tentativa) 
                    SELECT id, :ip, 1, NOW() 
                    FROM usuarios 
                    WHERE email = :username OR nome_usuario = :username
                    ON DUPLICATE KEY UPDATE 
                    tentativas = tentativas + 1,
                    data_tentativa = NOW(),
                    bloqueado_ate = CASE 
                        WHEN tentativas >= 4 THEN DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                        ELSE bloqueado_ate 
                    END";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ip', $ip, PDO::PARAM_STR);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $result = $stmt->execute();
            error_log("Failed login registration result: " . ($result ? 'success' : 'failed'));
        } catch (Exception $e) {
            error_log("Error registering failed login: " . $e->getMessage());
            // Não lançar exceção pois esta é uma operação secundária
        }
    }
}

<?php
namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Models\UserModel;
use Exception;

class AuthController {
    private $userModel;

    public function __construct() {
        try {
            // Garantir que as configurações foram carregadas
            if (!defined('BASE_PATH')) {
                require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/config/config.php';
            }
            $this->userModel = new UserModel();
        } catch (Exception $e) {
            error_log("Erro no construtor do AuthController: " . $e->getMessage());
            throw $e;
        }
    }

    public function login($username, $password) {
        try {
            // Validação básica
            if (empty($username) || empty($password)) {
                throw new Exception('Por favor, preencha todos os campos.');
            }

            // Autenticar usuário
            $user = $this->userModel->authenticate($username, $password);

            // Iniciar sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nome_usuario'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['sobrenome'] = $user['sobrenome'];
            $_SESSION['funcao_id'] = $user['funcao_id'];
            $_SESSION['last_activity'] = time();

            // Atualizar último login
            $this->userModel->updateLastLogin($user['id']);

            return [
                'success' => true,
                'message' => 'Login realizado com sucesso!',
                'redirect' => '/app/modules/dashboard/'
            ];

        } catch (Exception $e) {
            error_log("Erro no login: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function logout() {
        // Destruir todas as variáveis de sessão
        $_SESSION = array();

        // Destruir a sessão
        session_destroy();

        // Limpar cookie de sessão
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Redirecionar para a página de login
        header('Location: /app/modules/auth/views/login.php');
        exit();
    }

    public function isAuthenticated() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public function checkSession() {
        // Verificar se usuário está logado
        if (!$this->isAuthenticated()) {
            return false;
        }

        // Verificar tempo de inatividade (30 minutos)
        $inactiveTime = 1800; // 30 minutos em segundos
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactiveTime)) {
            $this->logout();
            return false;
        }

        // Atualizar último acesso
        $_SESSION['last_activity'] = time();
        return true;
    }

    public function requireAuth() {
        if (!$this->isAuthenticated()) {
            header('Location: /app/modules/auth/views/login.php');
            exit();
        }
    }
}

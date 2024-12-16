<?php
namespace App\Modules\Auth\Middleware;

use App\Modules\Auth\Controllers\AuthController;

class AuthMiddleware {
    private $auth;
    private $loginUrl = '/app/modules/auth/views/login.php';

    public function __construct() {
        $this->auth = new AuthController();
    }

    public function handle() {
        // Verificar se está autenticado
        if (!$this->auth->checkSession()) {
            // Salvar URL atual para redirecionamento após login
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            
            header('Location: ' . $this->loginUrl);
            exit();
        }

        return true;
    }

    public function requireRole($requiredRole) {
        // Primeiro verifica autenticação
        $this->handle();

        // Depois verifica papel do usuário
        if ($_SESSION['user_type'] !== $requiredRole) {
            header('HTTP/1.1 403 Forbidden');
            include dirname(__DIR__) . '/views/errors/403.php';
            exit();
        }

        return true;
    }
}

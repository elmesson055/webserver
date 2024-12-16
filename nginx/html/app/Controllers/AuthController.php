<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\User;
use PDO;

class AuthController extends Controller
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Exibe o formulário de login
     */
    public function loginForm()
    {
        // Se já estiver autenticado, redireciona para o dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        
        require_once VIEWS_DIR . '/auth/login.php';
    }

    /**
     * Processa o login
     */
    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['message'] = "Por favor, preencha todos os campos.";
            $_SESSION['message_type'] = "error";
            header('Location: /login');
            exit;
        }

        $user = User::findByEmail($email);
        
        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_email'] = $user->email;
            
            // Redireciona para a URL pretendida ou dashboard
            $redirect = $_SESSION['intended_url'] ?? '/dashboard';
            unset($_SESSION['intended_url']);
            
            header("Location: $redirect");
            exit;
        }

        $_SESSION['message'] = "Email ou senha inválidos.";
        $_SESSION['message_type'] = "error";
        header('Location: /login');
        exit;
    }

    /**
     * Processa o logout
     */
    public function logout()
    {
        // Limpa todas as variáveis de sessão
        $_SESSION = array();
        
        // Destrói a sessão
        session_destroy();
        
        // Limpa o cookie da sessão
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        header('Location: /login');
        exit;
    }

    /**
     * Exibe o formulário de recuperação de senha
     */
    public function forgotPasswordForm()
    {
        require_once VIEWS_DIR . '/auth/forgot-password.php';
    }

    /**
     * Processa a solicitação de recuperação de senha
     */
    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /forgot-password');
            exit;
        }

        $email = $_POST['email'] ?? '';

        if (empty($email)) {
            $_SESSION['error'] = 'E-mail é obrigatório';
            header('Location: /forgot-password');
            exit;
        }

        try {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $this->db->prepare("
                UPDATE users 
                SET password_reset_token = ?, 
                    password_reset_expires_at = ? 
                WHERE email = ?
            ");
            $stmt->execute([$token, $expires, $email]);

            if ($stmt->rowCount() > 0) {
                // Aqui você implementaria o envio do email
                // Por enquanto, apenas mostra o link
                $_SESSION['success'] = "Link de recuperação: " . baseUrl() . "/reset-password?token=" . $token;
            } else {
                $_SESSION['error'] = 'E-mail não encontrado';
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error'] = 'Erro ao processar solicitação. Tente novamente.';
        }

        header('Location: /forgot-password');
        exit;
    }

    /**
     * Exibe o formulário de redefinição de senha
     */
    public function resetPasswordForm()
    {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            header('Location: /login');
            exit;
        }

        try {
            $stmt = $this->db->prepare("
                SELECT id 
                FROM users 
                WHERE password_reset_token = ? 
                AND password_reset_expires_at > NOW()
            ");
            $stmt->execute([$token]);

            if (!$stmt->fetch()) {
                $_SESSION['error'] = 'Token inválido ou expirado';
                header('Location: /login');
                exit;
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error'] = 'Erro ao verificar token. Tente novamente.';
            header('Location: /login');
            exit;
        }

        require_once VIEWS_DIR . '/auth/reset-password.php';
    }

    /**
     * Processa a redefinição de senha
     */
    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (empty($token) || empty($password) || empty($password_confirm)) {
            $_SESSION['error'] = 'Todos os campos são obrigatórios';
            header('Location: /reset-password?token=' . $token);
            exit;
        }

        if ($password !== $password_confirm) {
            $_SESSION['error'] = 'As senhas não conferem';
            header('Location: /reset-password?token=' . $token);
            exit;
        }

        try {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET password = ?,
                    password_reset_token = NULL,
                    password_reset_expires_at = NULL
                WHERE password_reset_token = ? 
                AND password_reset_expires_at > NOW()
            ");
            
            $stmt->execute([
                password_hash($password, PASSWORD_DEFAULT),
                $token
            ]);

            if ($stmt->rowCount() > 0) {
                $_SESSION['success'] = 'Senha alterada com sucesso';
            } else {
                $_SESSION['error'] = 'Token inválido ou expirado';
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error'] = 'Erro ao alterar senha. Tente novamente.';
        }

        header('Location: /login');
        exit;
    }
}

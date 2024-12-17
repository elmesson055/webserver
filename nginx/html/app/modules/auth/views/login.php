<?php
// Definir caminho base se ainda não estiver definido
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:/webserver/nginx/html');
}

// Carregar configurações do módulo auth
require_once(__DIR__ . '/../config/config.php');

// Carregar classes necessárias
use App\Core\Database;
use App\Modules\Auth\Controllers\AuthController;
use App\Core\Exceptions\SecurityException;
use App\Core\Exceptions\ValidationException;

// Inicializar variáveis
$username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
$error = isset($_SESSION['error']) ? htmlspecialchars($_SESSION['error']) : '';
$success = isset($_SESSION['success']) ? htmlspecialchars($_SESSION['success']) : '';

// Limpar mensagens da sessão após uso
unset($_SESSION['error'], $_SESSION['success']);

// Regenerar ID da sessão para prevenir fixação de sessão
if (!isset($_SESSION['initialized'])) {
    session_regenerate_id(true);
    $_SESSION['initialized'] = true;
}

// Gerar token CSRF se não existir
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Inicializar controlador
try {
    $auth = new AuthController();

    // Se já estiver autenticado, redirecionar para dashboard
    if ($auth->isAuthenticated()) {
        header('Location: /app/modules/dashboard/');
        exit();
    }

    // Processar tentativa de login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar token CSRF
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new SecurityException('Token de segurança inválido');
        }

        // Validar campos obrigatórios
        if (empty($_POST['username']) || empty($_POST['password'])) {
            throw new ValidationException('Todos os campos são obrigatórios');
        }

        // Tentar realizar o login
        $result = $auth->login($_POST['username'], $_POST['password']);
        
        if ($result['success']) {
            // Regenerar ID da sessão após login bem-sucedido
            session_regenerate_id(true);
            header('Location: /app/modules/dashboard/');
            exit();
        } else {
            $error = $result['message'];
        }
    }
} catch (ValidationException $e) {
    $error = $e->getMessage();
} catch (SecurityException $e) {
    $error = $e->getMessage();
} catch (Exception $e) {
    error_log("Erro no login: " . $e->getMessage());
    $error = "Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fluentui/style-utilities/dist/css/fabric.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --dynamics-primary: #0078D4;
            --dynamics-secondary: #605E5C;
            --dynamics-background: #FFFFFF;
            --dynamics-surface: #F8F9FA;
        }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--dynamics-surface);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: var(--dynamics-background);
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header img {
            height: 60px;
            margin-bottom: 1rem;
        }

        .login-header h1 {
            color: var(--dynamics-secondary);
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .form-control {
            border: 1px solid #E1E1E1;
            padding: 0.75rem 1rem;
            height: auto;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--dynamics-primary);
            box-shadow: 0 0 0 2px rgba(0, 120, 212, 0.1);
        }

        .dynamics-btn {
            background: var(--dynamics-primary);
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .dynamics-btn:hover {
            background-color: #106EBE;
        }

        .form-floating > label {
            padding: 0.75rem 1rem;
        }

        .alert {
            border: none;
            border-radius: 2px;
        }

        .alert-danger {
            background-color: #FDE7E9;
            color: #D83B01;
        }

        .form-check-input:checked {
            background-color: var(--dynamics-primary);
            border-color: var(--dynamics-primary);
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            background-color: rgba(255, 255, 255, 0.7);
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="/app/assets/images/logo.png" alt="Logo">
            <h1><?php echo APP_NAME; ?></h1>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Usuário" required>
                <label for="username">Usuário</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required>
                <label for="password">Senha</label>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                    Lembrar-me
                </label>
            </div>

            <button type="submit" class="dynamics-btn position-relative overflow-hidden">
                Entrar
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Adicionar efeito de ripple ao botão
        document.querySelector('.dynamics-btn').addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const ripple = document.createElement('span');
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 1000);
        });

        // Animação suave ao carregar
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.login-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';

            setTimeout(() => {
                container.style.transition = 'all 0.5s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>

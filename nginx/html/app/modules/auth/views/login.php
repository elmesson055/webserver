<?php
// Definir caminho base se ainda não estiver definido
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:/webserver/nginx/html');
}

// Carregar autoloader personalizado
require_once BASE_PATH . '/app/autoload.php';

// Carregar configurações globais primeiro
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/config/session.php';

// Carregar classes necessárias
use App\Core\Database;
use App\Modules\Auth\Controllers\AuthController;

// Inicializar controlador
try {
    // Testar conexão com o banco antes de prosseguir
    $db = Database::getInstance();
    $db->query("SELECT 1"); // Teste simples de conexão
    
    $auth = new AuthController();

    // Se já estiver autenticado, redirecionar para dashboard
    if ($auth->isAuthenticated()) {
        header('Location: /app/modules/dashboard/');
        exit();
    }

    // Gerar token CSRF
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    $error = '';
    $username = '';

    // Processar login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $error = 'Erro de validação do formulário. Por favor, tente novamente.';
        } else {
            $username = $_POST['username'] ?? '';
            $result = $auth->login($username, $_POST['password'] ?? '');
            
            if ($result['success']) {
                header('Location: ' . $result['redirect']);
                exit();
            } else {
                $error = $result['message'];
            }
        }
    }
} catch (Exception $e) {
    error_log("Erro na página de login: " . $e->getMessage());
    $error = "Erro ao processar a requisição. Por favor, tente novamente.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Logística</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        .btn-primary {
            background-color: #2c3e50;
            border-color: #2c3e50;
            width: 100%;
            padding: 0.8rem;
        }
        .btn-primary:hover {
            background-color: #34495e;
            border-color: #34495e;
        }
        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Sistema de Logística</h1>
            <p style="color: #605e5c;">Faça login para continuar</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="mb-3">
                <label for="username" class="form-label">Usuário ou E-mail</label>
                <input type="text" class="form-control" id="username" name="username" 
                       value="<?php echo htmlspecialchars($username); ?>" required 
                       autocomplete="username">
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" 
                       required autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn btn-primary" id="loginButton">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                Entrar
            </button>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            var button = document.getElementById('loginButton');
            var spinner = button.querySelector('.spinner-border');
            button.disabled = true;
            spinner.classList.remove('d-none');
        });
    </script>
</body>
</html>

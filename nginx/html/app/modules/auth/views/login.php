<?php
namespace App\Modules\Auth\Views;

use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Auth\Classes\Auth;

// Definir caminho base se ainda não estiver definido
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(dirname(dirname(__DIR__)))));
}

// Carregar autoloader e configurações
require_once BASE_PATH . '/app/autoload.php';
require_once BASE_PATH . '/config/app.php';
require_once BASE_PATH . '/config/database.php';

// Iniciar sessão se ainda não estiver iniciada
$auth = Auth::getInstance();
$auth->startSession();

// Inicializar variáveis
$error = '';
$success = '';

// Processar tentativa de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $authController = new AuthController();
        
        // Validar campos obrigatórios
        if (empty($_POST['username']) || empty($_POST['password'])) {
            $error = 'Todos os campos são obrigatórios';
        } else {
            // Tentar realizar o login
            $result = $authController->login($_POST['username'], $_POST['password']);
            
            if ($result['success']) {
                // Regenerar ID da sessão após login bem-sucedido
                session_regenerate_id(true);
                header('Location: ' . $result['redirect']);
                exit();
            } else {
                $error = $result['message'];
            }
        }
    } catch (Exception $e) {
        error_log("Erro no login: " . $e->getMessage());
        $error = "Erro ao processar login. Por favor, tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Logística</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Login</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuário</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

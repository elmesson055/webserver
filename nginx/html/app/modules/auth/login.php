<?php
// Definir caminho base
define('BASE_PATH', dirname(dirname(dirname(__DIR__))));

// Carregar autoloader e configurações básicas
require_once BASE_PATH . '/app/autoload.php';
require_once BASE_PATH . '/config/app.php';
require_once BASE_PATH . '/config/database.php';

// Iniciar sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se já estiver logado, redirecionar para o dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: /app/modules/dashboard/index.php');
    exit();
}

// Processar tentativa de login
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        error_log("Iniciando tentativa de login...");
        
        // Validar campos obrigatórios
        if (empty($_POST['username']) || empty($_POST['password'])) {
            $error = 'Todos os campos são obrigatórios';
            error_log("Erro: campos obrigatórios faltando");
        } else {
            error_log("Campos validados, tentando autenticar usuário: " . $_POST['username']);
            
            // Tentar realizar o login
            $authController = new \App\Modules\Auth\Controllers\AuthController();
            $result = $authController->login($_POST['username'], $_POST['password']);
            
            error_log("Resultado do login: " . json_encode($result));
            
            if ($result['success']) {
                // Regenerar ID da sessão após login bem-sucedido
                session_regenerate_id(true);
                error_log("Login bem-sucedido, redirecionando para o dashboard...");
                header('Location: /app/modules/dashboard/index.php');
                exit();
            } else {
                $error = $result['message'];
                error_log("Erro no login: " . $error);
            }
        }
    } catch (\Exception $e) {
        error_log("Exceção no login: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
        $error = defined('DEBUG_MODE') && DEBUG_MODE ? 
                "Erro: " . $e->getMessage() : 
                "Erro ao processar login. Por favor, tente novamente.";
    }
}

// Gerar token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo defined('APP_NAME') ? APP_NAME : 'Sistema de Logística'; ?></title>
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
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuário</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                                       required>
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
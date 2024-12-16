<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir configurações na ordem correta
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/session.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/database.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/config.php';
require_once dirname(dirname(dirname(__FILE__))) . '/Core/Database.php';
require_once dirname(dirname(dirname(__FILE__))) . '/functions.php';

use App\Core\Database;

error_log("Dashboard - Verificando sessão...");
error_log("Session ID: " . session_id());
error_log("Session Data: " . print_r($_SESSION, true));

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user'])) {
    error_log("Usuário não está logado. Redirecionando para login...");
    header('Location: /app/modules/auth/login.php');
    exit();
}

// Obter informações do usuário
$user_id = $_SESSION['user_id'];
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se o usuário não for encontrado
    if (!$user) {
        error_log("Usuário não encontrado no banco de dados.");
        session_destroy();
        header('Location: /app/modules/auth/login.php');
        exit();
    }
    
    error_log("Usuário encontrado no banco: " . print_r($user, true));
    
} catch (Exception $e) {
    error_log("Erro ao buscar usuário: " . $e->getMessage());
    session_destroy();
    header('Location: /app/modules/auth/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Logística</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            padding-top: 1rem;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            padding: 2rem;
        }
        .welcome-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <?php include dirname(dirname(dirname(__FILE__))) . '/modules/common/sidebar.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content">
                <div class="welcome-card">
                    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                    <p>Papel: <?php echo htmlspecialchars($user['role_name']); ?></p>
                    <p>Último acesso: <?php echo $user['last_login'] ? date('d/m/Y H:i:s', strtotime($user['last_login'])) : 'Primeiro acesso'; ?></p>
                </div>

                <!-- Dashboard Content -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Módulos Disponíveis</h5>
                                <p class="card-text">Acesse os módulos através do menu lateral.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Add more dashboard cards as needed -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

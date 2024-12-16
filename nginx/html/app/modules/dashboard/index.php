<?php
// Definir caminho base se ainda não estiver definido
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:/webserver/nginx/html');
}

// Carregar autoloader e configurações
require_once BASE_PATH . '/app/autoload.php';
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/config/session.php';

// Verificar se usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: /app/modules/auth/views/login.php');
    exit();
}

try {
    // Testar conexão com o banco
    $db = \App\Modules\Dashboard\Core\Database::getInstance();
    $db->query("SELECT 1"); // Teste simples de conexão

    // Buscar informações do dashboard
    $stmt = $db->prepare("
        SELECT 
            (SELECT COUNT(*) FROM usuarios WHERE status = 'ativo') as total_usuarios,
            (SELECT COUNT(*) FROM veiculos WHERE status = 'ativo') as total_veiculos,
            (SELECT COUNT(*) FROM documentos_transporte WHERE status = 'pendente') as docs_pendentes
    ");
    $stmt->execute();
    $dashboard_info = $stmt->fetch();

} catch (Exception $e) {
    error_log("Erro no dashboard: " . $e->getMessage());
    $error = "Erro ao carregar informações do dashboard. Por favor, tente novamente.";
}

// Incluir o cabeçalho
require_once BASE_PATH . '/app/common/header.php';
?>

<h1>Dashboard</h1>
<p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php else: ?>
    <!-- Cards de resumo -->
    <div class="row dashboard-summary">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total de Usuários</h5>
                    <p class="card-text"><?php echo $dashboard_info['total_usuarios']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Veículos Ativos</h5>
                    <p class="card-text"><?php echo $dashboard_info['total_veiculos']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Documentos Pendentes</h5>
                    <p class="card-text"><?php echo $dashboard_info['docs_pendentes']; ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/common/footer.php'; ?>

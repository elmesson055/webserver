<?php
// Definir caminho base se ainda não estiver definido
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:/webserver/nginx/html');
}

// Carregar configurações globais
require_once BASE_PATH . '/app/config/config.php';

// Carregar módulo de autenticação
require_once BASE_PATH . '/app/modules/auth/auth_helper.php';

// Verificar sessão
check_session();

try {
    // Testar conexão com o banco
    $conn = get_database_connection();
    
    // Buscar informações do usuário
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT nome_usuario, sobrenome, funcao_id FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        throw new Exception('Usuário não encontrado');
    }

    // Definir variáveis para a view
    $nome_completo = $user['nome_usuario'] . ' ' . $user['sobrenome'];
    $funcao_id = $user['funcao_id'];

    // Buscar estatísticas do dashboard
    $sql = "SELECT 
        (SELECT COUNT(*) FROM usuarios WHERE status = 'Ativo') as total_usuarios,
        (SELECT COUNT(*) FROM funcoes WHERE ativo = TRUE) as total_funcoes,
        (SELECT COUNT(*) FROM permissoes) as total_permissoes";
    
    $result = $conn->query($sql);
    $stats = $result->fetch_assoc();

    // Incluir o template do dashboard
    $page_title = "Dashboard";
    require_once BASE_PATH . '/app/templates/header.php';
} catch (Exception $e) {
    error_log("Erro no dashboard: " . $e->getMessage());
    $_SESSION['error'] = "Ocorreu um erro ao carregar o dashboard. Por favor, tente novamente.";
    header('Location: /app/modules/auth/login.php');
    exit();
}
?>

<!-- Conteúdo do Dashboard -->
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    
    <!-- Cards de Estatísticas -->
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4 class="mb-0"><?php echo $stats['total_usuarios']; ?></h4>
                    <div>Usuários Ativos</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4 class="mb-0"><?php echo $stats['total_funcoes']; ?></h4>
                    <div>Funções Ativas</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h4 class="mb-0"><?php echo $stats['total_permissoes']; ?></h4>
                    <div>Permissões Cadastradas</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/app/templates/footer.php'; ?>

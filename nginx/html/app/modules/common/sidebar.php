<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/database.php';
require_once dirname(dirname(dirname(__FILE__))) . '/Core/Database.php';

// Buscar módulos baseado nas permissões do usuário
function getUserModules($pdo, $role_id) {
    // Debug
    error_log("Buscando módulos para role_id: " . $role_id);
    
    $query = "
        SELECT DISTINCT m.* 
        FROM modules m
        LEFT JOIN module_permissions mp ON m.id = mp.module_id AND mp.role_id = :role_id
        WHERE m.is_active = 1 
        AND (mp.can_view = 1 OR mp.role_id IS NULL)
        ORDER BY m.order_index, m.name";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
        $stmt->execute();
        $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Debug
        error_log("Módulos encontrados: " . count($modules));
        
        // Adicionar route se não existir
        foreach ($modules as &$module) {
            if (empty($module['route'])) {
                $module['route'] = strtolower(str_replace(' ', '_', $module['name']));
            }
            if (empty($module['icon'])) {
                $module['icon'] = 'fas fa-circle';
            }
            // Debug
            error_log("Módulo: {$module['name']}, Route: {$module['route']}, Icon: {$module['icon']}");
        }
        
        return $modules;
    } catch (PDOException $e) {
        error_log("Erro ao buscar módulos: " . $e->getMessage());
        return [];
    }
}

// Verificar se o usuário está logado e tem role_id
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    error_log("Usuário não está logado ou não tem role_id");
    header('Location: /app/modules/auth/login.php');
    exit;
}

// Debug
error_log("Session role_id: " . ($_SESSION['role_id'] ?? 'não definido'));

// Obter módulos permitidos
try {
    $pdo = Database::getConnection();
    $modules = getUserModules($pdo, $_SESSION['role_id']);
    error_log("Total de módulos carregados: " . count($modules));
} catch (Exception $e) {
    error_log("Erro ao conectar ao banco: " . $e->getMessage());
    $modules = [];
}

// Agrupar módulos por categoria
$moduleCategories = [
    'Principal' => ['dashboard', 'painel'],
    'Gestão' => ['admin', 'usuarios', 'configuracoes', 'cadastros'],
    'Logística' => ['logistica', 'transportes', 'tms', 'wms', 'wms-mobile', 'embarcadores', 'custos', 'custos_extras'],
    'Monitoramento' => ['monitoring', 'auditoria'],
    'Financeiro' => ['financeiro'],
    'Relatórios' => ['relatorios'],
    'Comunicação' => ['notificacoes', 'portal']
];

// Função para verificar se um módulo pertence a uma categoria
function moduleInCategory($module, $categoryModules) {
    $moduleRoute = strtolower(str_replace(' ', '_', $module['route'] ?? $module['name']));
    return in_array($moduleRoute, $categoryModules);
}

// Função para obter o caminho do módulo
function getModulePath($module) {
    $route = strtolower(str_replace(' ', '_', $module['route'] ?? $module['name']));
    return "/app/modules/{$route}/index.php";
}

// Debug - Listar todos os módulos antes de renderizar
error_log("Módulos disponíveis antes da renderização:");
foreach ($modules as $module) {
    error_log("Nome: {$module['name']}, Route: {$module['route']}, Ativo: {$module['is_active']}");
}
?>

<!-- Sidebar -->
<nav id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h3>Sistema Logística</h3>
        <!-- Debug - Mostrar role_id -->
        <small class="text-muted">Role ID: <?php echo htmlspecialchars($_SESSION['role_id'] ?? 'N/A'); ?></small>
    </div>

    <ul class="list-unstyled components">
        <?php if (!empty($modules)): ?>
            <?php foreach ($moduleCategories as $category => $categoryModules): ?>
                <?php
                // Verificar se existem módulos nesta categoria
                $hasModules = false;
                foreach ($modules as $module) {
                    if (moduleInCategory($module, $categoryModules)) {
                        $hasModules = true;
                        break;
                    }
                }
                ?>
                <?php if ($hasModules): ?>
                    <li class="sidebar-category">
                        <span class="category-name"><?php echo htmlspecialchars($category); ?></span>
                        <ul class="list-unstyled">
                            <?php foreach ($modules as $module): ?>
                                <?php if (moduleInCategory($module, $categoryModules)): ?>
                                    <li>
                                        <a href="<?php echo htmlspecialchars(getModulePath($module)); ?>">
                                            <i class="<?php echo htmlspecialchars($module['icon']); ?>"></i>
                                            <?php echo htmlspecialchars($module['name']); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Fallback para quando não há módulos ou ocorreu erro -->
            <li>
                <a href="/app/Views/dashboard/index.php">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <!-- Debug - Mostrar mensagem de erro -->
            <li class="text-danger">
                <small>Nenhum módulo encontrado</small>
            </li>
        <?php endif; ?>
        
        <!-- Link de logout -->
        <li class="mt-auto">
            <a href="/app/modules/auth/logout.php" class="text-danger">
                <i class="fas fa-sign-out-alt"></i>
                Sair
            </a>
        </li>
    </ul>
</nav>

<style>
.sidebar {
    min-height: 100vh;
    background-color: #343a40;
    padding-top: 1rem;
    color: #fff;
}

.sidebar-header {
    padding: 1rem;
    border-bottom: 1px solid #4b545c;
}

.sidebar-header h3 {
    margin: 0;
    font-size: 1.2rem;
    color: #fff;
}

.sidebar-category {
    margin-top: 1rem;
}

.category-name {
    display: block;
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
    color: #adb5bd;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.sidebar a {
    color: #fff;
    text-decoration: none;
    padding: 0.5rem 1rem;
    display: block;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.sidebar a:hover {
    background-color: #495057;
    padding-left: 1.5rem;
}

.sidebar i {
    margin-right: 0.5rem;
    width: 20px;
    text-align: center;
}

.sidebar .text-danger {
    color: #dc3545 !important;
}

.sidebar .text-danger:hover {
    background-color: #dc3545;
    color: #fff !important;
}

.list-unstyled {
    padding-left: 0;
    list-style: none;
}

/* Debug styles */
.text-muted {
    color: #6c757d !important;
    font-size: 0.8rem;
}
</style>

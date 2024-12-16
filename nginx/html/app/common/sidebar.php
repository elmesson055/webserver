<?php
// Definir caminho base se ainda não estiver definido
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:/webserver/nginx/html');
}

// Carregar configurações se ainda não foram carregadas
if (!isset($userRole)) {
    require_once BASE_PATH . '/app/autoload.php';
    require_once BASE_PATH . '/config/config.php';
    require_once BASE_PATH . '/config/database.php';
    require_once BASE_PATH . '/config/session.php';
    
    // Get user role from session
    $userRole = $_SESSION['user_role'] ?? 'user';
}
?>

<nav class="sidebar">
    <div class="sidebar-header">
        <h3>Sistema Logística</h3>
        <?php if (isset($_SESSION['username'])): ?>
            <p class="user-info">
                <i class="fas fa-user"></i>
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </p>
        <?php endif; ?>
    </div>

    <ul class="components">
        <li>
            <a href="/app/modules/dashboard/">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        
        <li>
            <a href="/app/modules/usuarios/">
                <i class="fas fa-users"></i>
                Usuários
            </a>
        </li>
        
        <li>
            <a href="/app/modules/veiculos/">
                <i class="fas fa-truck"></i>
                Veículos
            </a>
        </li>
        
        <li>
            <a href="/app/modules/documentos/">
                <i class="fas fa-file-alt"></i>
                Documentos
            </a>
        </li>
        
        <?php if ($userRole === 'admin'): ?>
            <li>
                <a href="/app/modules/configuracoes/">
                    <i class="fas fa-cogs"></i>
                    Configurações
                </a>
            </li>
        <?php endif; ?>
        
        <li>
            <a href="/app/modules/auth/logout.php">
                <i class="fas fa-sign-out-alt"></i>
                Sair
            </a>
        </li>
    </ul>
</nav>

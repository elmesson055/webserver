<?php
$modules = require_once __DIR__ . '/../../config/modules.php';
$currentModule = getCurrentModule();
$currentPath = getCurrentPath();

function getCurrentModule() {
    $path = $_SERVER['REQUEST_URI'];
    $parts = explode('/', trim($path, '/'));
    return $parts[0] ?? '';
}

function getCurrentPath() {
    return $_SERVER['REQUEST_URI'];
}

function isActiveModule($moduleKey, $currentModule) {
    return $moduleKey === $currentModule ? 'active' : '';
}

function isActiveRoute($path, $currentPath) {
    return $path === $currentPath ? 'active' : '';
}
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="/assets/img/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Custo Extras</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php
                // Ordenar módulos
                $orderedModules = $modules['modules'];
                uasort($orderedModules, function($a, $b) {
                    return $a['order'] - $b['order'];
                });

                foreach ($orderedModules as $moduleKey => $module):
                    $hasSubmenu = count($module['routes']) > 1;
                ?>
                    <li class="nav-item <?php echo $hasSubmenu ? 'has-treeview' : ''; ?> <?php echo isActiveModule($moduleKey, $currentModule); ?>">
                        <a href="<?php echo $hasSubmenu ? '#' : $module['routes']['index']['path']; ?>" class="nav-link">
                            <i class="nav-icon <?php echo $module['icon']; ?>"></i>
                            <p>
                                <?php echo $module['name']; ?>
                                <?php if ($hasSubmenu): ?>
                                    <i class="right fas fa-angle-left"></i>
                                <?php endif; ?>
                            </p>
                        </a>
                        <?php if ($hasSubmenu): ?>
                            <ul class="nav nav-treeview">
                                <?php foreach ($module['routes'] as $route): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo $route['path']; ?>" class="nav-link <?php echo isActiveRoute($route['path'], $currentPath); ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p><?php echo $route['name']; ?></p>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<!-- Notificações em tempo real -->
<div id="notifications-container"></div>

<script>
// Inicializar conexão WebSocket para notificações em tempo real
const ws = new WebSocket('ws://localhost:8080');
ws.onmessage = function(event) {
    const notification = JSON.parse(event.data);
    showNotification(notification);
};

function showNotification(notification) {
    const container = document.getElementById('notifications-container');
    const notificationElement = document.createElement('div');
    notificationElement.className = 'notification-toast';
    notificationElement.innerHTML = `
        <div class="notification-header">
            <strong>${notification.titulo}</strong>
            <button onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
        <div class="notification-body">
            ${notification.mensagem}
        </div>
    `;
    container.appendChild(notificationElement);
    
    // Auto-remover após 5 segundos
    setTimeout(() => {
        notificationElement.remove();
    }, 5000);
}
</script>

<style>
#notifications-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.notification-toast {
    background: #fff;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    margin-bottom: 10px;
    min-width: 300px;
    max-width: 400px;
    animation: slideIn 0.3s ease-in-out;
}

.notification-header {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 4px 4px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header button {
    border: none;
    background: none;
    font-size: 20px;
    cursor: pointer;
    padding: 0 5px;
}

.notification-body {
    padding: 10px;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>

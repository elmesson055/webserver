<?php
require_once __DIR__ . '/../../app/functions.php';
$current_page = $_SERVER['REQUEST_URI'];
?>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand">
        <img src="/assets/images/logo.png" alt="Logo" class="sidebar-logo">
        <span class="sidebar-brand-text">Custo Extras</span>
    </div>

    <div class="sidebar-menu">
        <!-- Dashboard -->
        <div class="menu-section">
            <a href="/dashboard" class="menu-item <?= strpos($current_page, 'dashboard') !== false ? 'active' : '' ?>">
                <div class="menu-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Cadastros -->
        <div class="menu-section">
            <div class="menu-header">
                <i class="fas fa-database"></i>
                <span>Cadastros</span>
            </div>
            <a href="/cadastros/embarcadores" class="menu-item <?= strpos($current_page, 'embarcadores') !== false ? 'active' : '' ?>">
                <div class="menu-icon">
                    <i class="fas fa-building"></i>
                </div>
                <span>Embarcadores</span>
            </a>
            <a href="/cadastros/fornecedores" class="menu-item <?= strpos($current_page, 'fornecedores') !== false ? 'active' : '' ?>">
                <div class="menu-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <span>Fornecedores</span>
            </a>
            <a href="/cadastros/clientes" class="menu-item <?= strpos($current_page, 'clientes') !== false ? 'active' : '' ?>">
                <div class="menu-icon">
                    <i class="fas fa-users"></i>
                </div>
                <span>Clientes</span>
            </a>
            <a href="/cadastros/motoristas" class="menu-item <?= strpos($current_page, 'motoristas') !== false ? 'active' : '' ?>">
                <div class="menu-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <span>Motoristas</span>
            </a>
        </div>

        <!-- Configurações -->
        <?php if (isset($_SESSION['user']) && (hasRole('admin') || hasPermission('view_users'))): ?>
        <div class="menu-section">
            <div class="menu-header">
                <i class="fas fa-cogs"></i>
                <span>Configurações</span>
            </div>
            <a href="/config/usuarios" class="menu-item <?= strpos($current_page, 'usuarios') !== false ? 'active' : '' ?>">
                <div class="menu-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <span>Usuários</span>
            </a>
            <a href="/config/perfis" class="menu-item <?= strpos($current_page, 'perfis') !== false ? 'active' : '' ?>">
                <div class="menu-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <span>Perfis</span>
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- User Info -->
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-details">
                <span class="user-name"><?= $_SESSION['user_name'] ?? 'Usuário' ?></span>
                <span class="user-role"><?= $_SESSION['user_role'] ?? 'Sem perfil' ?></span>
            </div>
            <a href="/logout" class="logout-btn" title="Sair">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</div>

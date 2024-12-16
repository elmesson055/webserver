<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth_check.php';

// Get user role from session
$userRole = $_SESSION['user_role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Logística</title>
    
    <!-- Fluent UI Icons -->
    <link rel="stylesheet" href="https://static2.sharepointonline.com/files/fabric/assets/icons/fabric-icons-inline.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --sidebar-bg: #2D2D31;
            --sidebar-hover: #3D3D41;
            --sidebar-active: #0078D4;
            --text-primary: #FFFFFF;
            --text-secondary: #A0A0A0;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }
        
        /* Layout */
        .dynamics-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .dynamics-sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            color: var(--text-primary);
            padding: 0;
            flex-shrink: 0;
            transition: width 0.3s;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 16px 20px;
            background: rgba(0,0,0,0.2);
            margin-bottom: 8px;
        }
        
        .sidebar-header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }
        
        .sidebar-header .role-id {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 4px;
        }
        
        .sidebar-section {
            margin-bottom: 16px;
        }
        
        .sidebar-section-title {
            padding: 8px 20px;
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-secondary);
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .menu-item {
            padding: 8px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        
        .menu-item:hover {
            background: var(--sidebar-hover);
        }
        
        .menu-item.active {
            background: var(--sidebar-active);
        }
        
        .menu-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .dynamics-main {
            flex: 1;
            background: #F8F9FA;
            overflow-y: auto;
        }
        
        /* Header */
        .dynamics-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            background: white;
            padding: 16px 24px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Summary Cards */
        .dynamics-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Form Styles */
        .dynamics-form {
            background: white;
            padding: 24px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .dynamics-input,
        .dynamics-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #E1E1E1;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .dynamics-button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .dynamics-button.primary {
            background: #0078D4;
            color: white;
        }
        
        .dynamics-button.secondary {
            background: #F3F3F3;
            color: #333;
        }
        
        /* Loading Spinner */
        .dynamics-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #0078D4;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Notifications */
        .dynamics-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        }
        
        .dynamics-notification.success {
            background-color: #107C10;
        }
        
        .dynamics-notification.error {
            background-color: #D83B01;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
    </style>
</head>
<body>
    <div class="dynamics-layout">
        <!-- Sidebar -->
        <nav class="dynamics-sidebar">
            <div class="sidebar-header">
                <h2>Sistema Logística</h2>
                <div class="role-id">Role ID: <?php echo htmlspecialchars($_SESSION['role_id'] ?? ''); ?></div>
            </div>

            <!-- Principal Section -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">Principal</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/dashboard" class="menu-item">
                            <i class="ms-Icon ms-Icon--ViewDashboard"></i>
                            <span>dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/painel" class="menu-item">
                            <i class="ms-Icon ms-Icon--GridViewMedium"></i>
                            <span>painel</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Gestão Section -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">Gestão</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/usuarios" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'usuarios') !== false ? 'active' : ''; ?>">
                            <i class="ms-Icon ms-Icon--People"></i>
                            <span>usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="/configuracoes" class="menu-item">
                            <i class="ms-Icon ms-Icon--Settings"></i>
                            <span>configuracoes</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin" class="menu-item">
                            <i class="ms-Icon ms-Icon--SecurityGroup"></i>
                            <span>admin</span>
                        </a>
                    </li>
                    <li>
                        <a href="/cadastros" class="menu-item">
                            <i class="ms-Icon ms-Icon--ContactList"></i>
                            <span>cadastros</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Logística Section -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">Logística</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/logistica" class="menu-item">
                            <i class="ms-Icon ms-Icon--Delivery"></i>
                            <span>logistica</span>
                        </a>
                    </li>
                    <li>
                        <a href="/tms" class="menu-item">
                            <i class="ms-Icon ms-Icon--Truck"></i>
                            <span>tms</span>
                        </a>
                    </li>
                    <li>
                        <a href="/transportes" class="menu-item">
                            <i class="ms-Icon ms-Icon--Car"></i>
                            <span>transportes</span>
                        </a>
                    </li>
                    <li>
                        <a href="/wms" class="menu-item">
                            <i class="ms-Icon ms-Icon--Package"></i>
                            <span>wms</span>
                        </a>
                    </li>
                    <li>
                        <a href="/wms-mobile" class="menu-item">
                            <i class="ms-Icon ms-Icon--CellPhone"></i>
                            <span>wms-mobile</span>
                        </a>
                    </li>
                    <li>
                        <a href="/embarcadores" class="menu-item">
                            <i class="ms-Icon ms-Icon--Ship"></i>
                            <span>embarcadores</span>
                        </a>
                    </li>
                    <li>
                        <a href="/custos" class="menu-item">
                            <i class="ms-Icon ms-Icon--Money"></i>
                            <span>custos</span>
                        </a>
                    </li>
                    <li>
                        <a href="/custos_extras" class="menu-item">
                            <i class="ms-Icon ms-Icon--Calculator"></i>
                            <span>custos_extras</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Monitoramento Section -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">Monitoramento</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/monitoring" class="menu-item">
                            <i class="ms-Icon ms-Icon--ActivityFeed"></i>
                            <span>monitoring</span>
                        </a>
                    </li>
                    <li>
                        <a href="/auditoria" class="menu-item">
                            <i class="ms-Icon ms-Icon--Audit"></i>
                            <span>auditoria</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="dynamics-main">

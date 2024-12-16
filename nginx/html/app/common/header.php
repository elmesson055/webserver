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

// Get user role from session
$userRole = $_SESSION['user_role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Logística</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        /* Layout */
        body {
            background-color: #f8f9fa;
        }
        
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        /* Sidebar */
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
        }

        .sidebar .sidebar-header {
            padding: 20px;
            background: #2c3136;
        }

        .sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid #47748b;
        }

        .sidebar ul li a {
            padding: 10px;
            font-size: 1.1em;
            display: block;
            color: #fff;
            text-decoration: none;
        }

        .sidebar ul li a:hover {
            color: #7386D5;
            background: #fff;
        }

        /* Content */
        .content {
            width: 100%;
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Cards */
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            margin-bottom: 15px;
            color: #343a40;
        }

        .card-text {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 0.2em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border .75s linear infinite;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>Sistema Logística</h2>
                <div class="role-id">Role ID: <?php echo htmlspecialchars($_SESSION['role_id'] ?? ''); ?></div>
            </div>

            <!-- Principal Section -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">Principal</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="/app/modules/dashboard" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : ''; ?>">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span>dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/app/modules/painel" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'painel') !== false ? 'active' : ''; ?>">
                            <i class="fa-solid fa-table-columns"></i>
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
                        <a href="/app/modules/usuarios" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'usuarios') !== false ? 'active' : ''; ?>">
                            <i class="fa-solid fa-users"></i>
                            <span>usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="/app/modules/configuracoes" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'configuracoes') !== false ? 'active' : ''; ?>">
                            <i class="fa-solid fa-gear"></i>
                            <span>configuracoes</span>
                        </a>
                    </li>
                    <li>
                        <a href="/app/modules/admin" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'admin') !== false ? 'active' : ''; ?>">
                            <i class="fa-solid fa-lock"></i>
                            <span>admin</span>
                        </a>
                    </li>
                    <li>
                        <a href="/app/modules/cadastros" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'cadastros') !== false ? 'active' : ''; ?>">
                            <i class="fa-solid fa-address-book"></i>
                            <span>cadastros</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="content">
            <!-- Content will be injected here -->

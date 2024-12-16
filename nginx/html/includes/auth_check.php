<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Você precisa estar logado para acessar esta página.";
    $_SESSION['message_type'] = "warning";
    
    // Salva a URL atual para redirecionar depois do login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    header('Location: /login');
    exit;
}

// Function to check if user has access to a module
function hasModuleAccess($module) {
    $userRole = $_SESSION['user_role'] ?? 'user';
    
    // Admin has access to everything
    if ($userRole === 'admin') {
        return true;
    }
    
    // Module access mapping
    $moduleAccess = [
        'user' => [
            'dashboard',
            'painel'
        ],
        'manager' => [
            'dashboard',
            'painel',
            'usuarios',
            'cadastros',
            'logistica',
            'tms',
            'transportes',
            'wms',
            'wms-mobile',
            'monitoring'
        ]
    ];
    
    return in_array($module, $moduleAccess[$userRole] ?? []);
}

// Get current module from URL
$currentUrl = $_SERVER['REQUEST_URI'];
$module = trim(parse_url($currentUrl, PHP_URL_PATH), '/');

// If user doesn't have access to current module, redirect to dashboard
if (!hasModuleAccess($module) && $module !== 'dashboard') {
    header('Location: /dashboard');
    exit();
}

// Verifica se o usuário está ativo
global $db;
if (isset($db)) {
    $stmt = $db->prepare("SELECT active FROM users WHERE id = ? AND deleted_at IS NULL");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user || !$user['active']) {
        session_destroy();
        $_SESSION['message'] = "Sua conta está inativa. Entre em contato com o administrador.";
        $_SESSION['message_type'] = "warning";
        header('Location: /login');
        exit;
    }
}
?>

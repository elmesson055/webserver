<?php
/**
 * Verificação de autenticação
 * Este arquivo verifica se o usuário está autenticado e tem permissão para acessar a página
 */

// Inicia a sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['message_type'] = "warning";
    header("Location: /login");
    exit;
}

// Verifica se o usuário tem permissão para acessar a página
$current_page = $_SERVER['REQUEST_URI'];
$user_role = $_SESSION['user_role'] ?? null;

// Lista de páginas que requerem permissões específicas
$restricted_pages = [
    '/cadastros/embarcadores' => ['admin', 'gerente'],
    '/cadastros/motoristas' => ['admin', 'gerente'],
    '/cadastros/veiculos' => ['admin', 'gerente'],
    '/config/usuarios' => ['admin'],
    '/config/perfis' => ['admin']
];

// Verifica se a página atual requer permissão específica
foreach ($restricted_pages as $page => $allowed_roles) {
    if (strpos($current_page, $page) !== false) {
        if (!in_array($user_role, $allowed_roles)) {
            $_SESSION['message'] = "Você não tem permissão para acessar esta página.";
            $_SESSION['message_type'] = "danger";
            header("Location: /dashboard");
            exit;
        }
        break;
    }
}

// Log de acesso
error_log("Usuário {$_SESSION['user_id']} ({$_SESSION['user_name']}) acessou a página {$current_page}");

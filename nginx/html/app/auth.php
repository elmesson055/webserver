<?php
require_once __DIR__ . '/init.php';

/**
 * Verifica se o usuário está autenticado
 * @return bool
 */
function isAuthenticated() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

/**
 * Verifica se o usuário é admin
 * @return bool
 */
function isAdmin() {
    return isset($_SESSION['user']['role_name']) && $_SESSION['user']['role_name'] === 'admin';
}

// Log de depuração
error_log('=== INÍCIO DA VERIFICAÇÃO DE AUTENTICAÇÃO ===');
error_log('REQUEST_URI: ' . $_SERVER['REQUEST_URI']);
error_log('SESSION: ' . json_encode($_SESSION));

// Verificar autenticação
if (!isAuthenticated()) {
    error_log('Usuário não autenticado - redirecionando para login');
    redirect('login');
    exit;
}

error_log('Usuário autenticado - ID: ' . $_SESSION['user']['id']);
error_log('Role: ' . ($_SESSION['user']['role_name'] ?? 'N/A'));
error_log('Permissões: ' . implode(', ', $_SESSION['user']['permissions'] ?? []));
error_log('=== FIM DA VERIFICAÇÃO DE AUTENTICAÇÃO ===');

// Verificar se é admin
if (!isAdmin()) {
    error_log('Acesso negado - usuário não é admin');
    redirect('', ['error' => 'access_denied']);
    exit;
}

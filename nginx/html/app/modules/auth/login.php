<?php
// Definir caminho base
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:/webserver/nginx/html');
}

// Carregar configurações
require_once BASE_PATH . '/app/config/config.php';

// Iniciar sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se já estiver logado, redirecionar para o dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: /app/modules/dashboard/index.php');
    exit();
}

// Redirecionar para a view de login
header('Location: /app/modules/auth/views/login.php');
exit();
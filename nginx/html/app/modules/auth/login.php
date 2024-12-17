<?php
// Definir caminho base
define('BASE_PATH', dirname(dirname(dirname(__DIR__))));

// Carregar autoloader e configurações básicas
require_once BASE_PATH . '/app/autoload.php';
require_once BASE_PATH . '/config/app.php';
require_once BASE_PATH . '/config/database.php';

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se já estiver logado, redirecionar para o dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: /app/modules/dashboard/index.php');
    exit();
}

// Incluir a view de login diretamente
require_once __DIR__ . '/views/login.php';
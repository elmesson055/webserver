<?php
// Configurações globais do sistema

// Definir caminho base se ainda não estiver definido
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:/webserver/nginx/html');
}

// Configurações de ambiente
define('APP_ENV', 'development'); // ou 'production' em ambiente de produção
define('APP_DEBUG', true);        // false em produção

// Configurações de URL
define('APP_URL', 'http://localhost');
define('APP_NAME', 'Sistema de Logística');

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de sessão
ini_set('session.gc_maxlifetime', 3600); // 1 hora
ini_set('session.cookie_lifetime', 3600); // 1 hora

// Configurações de erro (ajustar conforme ambiente)
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Carregar outros arquivos de configuração necessários
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/database.php';
}

// Iniciar sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    require_once __DIR__ . '/session.php';
}

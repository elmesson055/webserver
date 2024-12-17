<?php
// Configurações específicas do módulo

// Definir caminho base se ainda não estiver definido
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:/webserver/nginx/html');
}

// Carregar configurações globais
require_once BASE_PATH . '/config/app.php';

// Carregar autoloader
require_once BASE_PATH . '/app/autoload.php';

// Incluir configurações globais
if (!defined('DB_HOST')) {
    require_once BASE_PATH . '/config/database.php';
}

// Iniciar sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    require_once(__DIR__ . '/../session.php');
}

// Incluir outros arquivos necessários
require_once BASE_PATH . '/app/Core/Database.php';

<?php
// Configurações específicas do módulo

// Definir caminho base se ainda não estiver definido
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:/webserver/nginx/html');
}

// Incluir configurações globais usando caminho absoluto
require_once BASE_PATH . '/config/session.php';
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/app/Core/Database.php';

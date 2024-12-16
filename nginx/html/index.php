<?php
// Definir constantes do sistema
define('BASE_PATH', __DIR__);
define('APP_PATH', __DIR__ . '/app');
define('CONFIG_PATH', __DIR__ . '/config');
define('PUBLIC_PATH', __DIR__ . '/public');

// Carregar configurações essenciais
require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';
require_once APP_PATH . '/Core/Database.php';

// Inicia a sessão se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de erro
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Carregar funções globais
require_once APP_PATH . '/functions.php';

// Carregar e processar rotas
require_once __DIR__ . '/routes/routes.php';

// Obter a URL atual
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Processar a rota
processRoute($url);

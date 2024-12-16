<?php
// Iniciar output buffering antes de qualquer coisa
if (ob_get_level() == 0) ob_start();

// Evitar qualquer output antes das configurações
if (headers_sent($filename, $linenum)) {
    error_log("Headers já foram enviados em $filename:$linenum");
}

// Definir caminho base
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:\webserver\nginx\html');
} else {
    error_log("Constante BASE_PATH já definida. Valor atual: " . BASE_PATH);
}

// Carregar configurações de sessão antes de qualquer coisa
require_once __DIR__ . '/session.php';

// Configurações de ambiente
define('DEBUG_MODE', true);
error_reporting(DEBUG_MODE ? E_ALL : 0);
ini_set('display_errors', DEBUG_MODE ? '1' : '0');

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações da aplicação
define('APP_NAME', 'Sistema de Gestão de Logistica e Transportes');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development');
define('COMPANY_NAME', 'Elmesson Analytics');
define('DEVELOPER_NAME', 'Elmesson');
define('SUPPORT_EMAIL', 'elmesson@outlook.com');
define('SUPPORT_PHONE', '(38) 98824-9631');
define('DOCUMENTATION_URL', 'https://docs.elmessontech.com');

// Definir caminhos da aplicação
define('ROOT_DIR', BASE_PATH);
define('APP_DIR', ROOT_DIR . '/app');
define('PUBLIC_DIR', ROOT_DIR . '/public');
define('VIEWS_DIR', ROOT_DIR . '/public/views');
define('CONFIG_DIR', ROOT_DIR . '/config');
define('STORAGE_DIR', ROOT_DIR . '/storage');

// Configurações de segurança
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_LENGTH', 32);
define('PASSWORD_MIN_LENGTH', 8);
define('PASSWORD_HASH_ALGO', PASSWORD_DEFAULT);
define('PASSWORD_HASH_OPTIONS', ['cost' => 12]);

// Configurações de upload
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx']);
define('UPLOAD_PATH', STORAGE_DIR . '/uploads');

// Configurações de cache
define('CACHE_ENABLED', true);
define('CACHE_PATH', STORAGE_DIR . '/cache');
define('CACHE_DEFAULT_EXPIRY', 3600);

// Configurações de log
define('LOG_ENABLED', true);
define('LOG_PATH', STORAGE_DIR . '/logs');
define('LOG_LEVEL', DEBUG_MODE ? 'debug' : 'error');

// URLs da aplicação
define('BASE_URL', 'http://localhost');
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', BASE_URL . '/uploads');

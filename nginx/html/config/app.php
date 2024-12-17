<?php
// Configurações globais da aplicação

// Nome da aplicação
define('APP_NAME', 'Sistema');

// Versão da aplicação
define('APP_VERSION', '1.0.0');

// Ambiente (development, production)
define('APP_ENV', 'development');

// URL base da aplicação
define('APP_URL', 'http://localhost');

// Debug mode
define('DEBUG_MODE', APP_ENV === 'development');

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Mudar para 1 em produção com HTTPS
ini_set('session.gc_maxlifetime', 1800); // 30 minutos
ini_set('session.cookie_lifetime', 0); // Até o navegador fechar

// Configurações de erro (development)
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Configurações de segurança
define('CSRF_PROTECTION', true);
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 15); // minutos

// Diretórios da aplicação
define('APP_PATH', dirname(__DIR__) . '/app');
define('CONFIG_PATH', dirname(__DIR__) . '/config');
define('PUBLIC_PATH', dirname(__DIR__) . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// URLs da aplicação
define('BASE_URL', APP_URL);
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOAD_URL', BASE_URL . '/uploads');

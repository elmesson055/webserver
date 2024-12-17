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

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Mudar para 1 em produção com HTTPS

// Configurações de erro (development)
if (APP_ENV === 'development') {
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

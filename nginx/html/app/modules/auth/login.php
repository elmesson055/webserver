<?php
// Definir caminho base
if (!defined('BASE_PATH')) {
    define('BASE_PATH', 'C:/webserver/nginx/html');
}

// Carregar configurações
require_once(BASE_PATH . '/app/modules/auth/config/config.php');

// Redirecionar para a view correta
header('Location: views/login.php');
exit();
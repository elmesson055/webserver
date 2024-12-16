<?php
// Log para verificar se o arquivo está sendo carregado
error_log('Arquivo de configuração do banco de dados carregado em: ' . __FILE__);

// Configurações do banco de dados
$db_config = [
    'host' => 'localhost',
    'dbname' => 'logistica_transportes',
    'username' => 'logistica_admin',
    'password' => 'LOg1st1ca2024!',
    'port' => '3306',
    'charset' => 'utf8mb4'
];

// Definir constantes se ainda não estiverem definidas
if (!defined('DB_HOST')) define('DB_HOST', $db_config['host']);
if (!defined('DB_NAME')) define('DB_NAME', $db_config['dbname']);
if (!defined('DB_USER')) define('DB_USER', $db_config['username']);
if (!defined('DB_PASS')) define('DB_PASS', $db_config['password']);
if (!defined('DB_PORT')) define('DB_PORT', $db_config['port']);
if (!defined('DB_CHARSET')) define('DB_CHARSET', $db_config['charset']);

// Retornar a configuração
return $db_config;

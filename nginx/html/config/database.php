<?php
// Configurações do banco de dados
$db_config = [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'logistica_transportes',
    'username' => 'logistica_admin',
    'password' => 'LOg1st1ca2024!',
    'charset' => 'utf8mb4'
];

// Definir constantes apenas se não estiverem definidas
if (!defined('DB_HOST')) define('DB_HOST', $db_config['host']);
if (!defined('DB_NAME')) define('DB_NAME', $db_config['dbname']);
if (!defined('DB_USER')) define('DB_USER', $db_config['username']);
if (!defined('DB_PASS')) define('DB_PASS', $db_config['password']);
?>

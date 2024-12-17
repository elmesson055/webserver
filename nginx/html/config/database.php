<?php
// Configurações do banco de dados
$db_config = [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'logistica_transportes',
    'username' => 'root',  // Usando root para desenvolvimento local
    'password' => '',      // Senha vazia para desenvolvimento local
    'charset' => 'utf8mb4'
];

// Definir constantes apenas se não estiverem definidas
if (!defined('DB_HOST')) define('DB_HOST', $db_config['host']);
if (!defined('DB_PORT')) define('DB_PORT', $db_config['port']);
if (!defined('DB_NAME')) define('DB_NAME', $db_config['dbname']);
if (!defined('DB_USER')) define('DB_USER', $db_config['username']);
if (!defined('DB_PASS')) define('DB_PASS', $db_config['password']);
if (!defined('DB_CHARSET')) define('DB_CHARSET', $db_config['charset']);

// DSN para conexão PDO
if (!defined('DB_DSN')) {
    define('DB_DSN', sprintf(
        "mysql:host=%s;port=%d;dbname=%s;charset=%s",
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    ));
}
?>

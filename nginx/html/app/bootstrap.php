<?php
/**
 * Bootstrap da aplicação
 */

// Inicia a sessão se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carrega o autoloader do Composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Carrega as configurações
require_once dirname(__DIR__) . '/config/config.php';

// Carrega a configuração do banco de dados
$db_config = require_once dirname(__DIR__) . '/config/database.php';

// Conectar ao banco de dados
try {
    $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $db_config['username'], $db_config['password'], $options);
    return $pdo;
} catch (PDOException $e) {
    error_log("Erro ao conectar ao banco de dados: " . $e->getMessage());
    throw $e;
}

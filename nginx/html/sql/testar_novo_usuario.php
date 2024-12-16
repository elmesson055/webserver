<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Teste de Conexão com Novo Usuário ===\n\n";

// Carregar configurações
require_once dirname(dirname(__FILE__)) . '/config/database.php';

echo "Configurações carregadas:\n";
echo "Host: " . DB_HOST . "\n";
echo "Database: " . DB_NAME . "\n";
echo "User: " . DB_USER . "\n";
echo "Port: " . DB_PORT . "\n";
echo "Charset: " . DB_CHARSET . "\n\n";

try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    );
    
    echo "DSN: " . $dsn . "\n";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
    ];
    
    echo "Tentando conexão...\n";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "Conexão estabelecida!\n\n";
    
    // Testar consultas básicas
    $stmt = $pdo->query("SELECT VERSION()");
    echo "Versão MySQL: " . $stmt->fetchColumn() . "\n";
    
    $stmt = $pdo->query("SELECT CURRENT_USER()");
    echo "Usuário atual: " . $stmt->fetchColumn() . "\n";
    
    $stmt = $pdo->query("SHOW TABLES");
    echo "\nTabelas no banco:\n";
    while ($row = $stmt->fetch()) {
        echo "- " . $row[0] . "\n";
    }
    
} catch (PDOException $e) {
    echo "ERRO DE CONEXÃO:\n";
    echo "Mensagem: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
}

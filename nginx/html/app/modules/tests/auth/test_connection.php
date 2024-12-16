<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/database.php';

try {
    echo "Tentando conectar ao banco de dados...\n";
    echo "Host: " . DB_HOST . "\n";
    echo "Database: " . DB_NAME . "\n";
    echo "User: " . DB_USER . "\n";
    
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    );
    
    echo "DSN: " . $dsn . "\n\n";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "Conexão bem sucedida!\n";
    
    // Testar consulta
    $stmt = $db->query("SELECT VERSION() as version");
    $result = $stmt->fetch();
    echo "Versão do MySQL: " . $result['version'] . "\n";
    
} catch (PDOException $e) {
    echo "Erro ao conectar:\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "Mensagem: " . $e->getMessage() . "\n";
    exit(1);
}

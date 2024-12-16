<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Teste de Conexão com o Banco ===\n\n";

// Carregar configurações
require_once dirname(dirname(__FILE__)) . '/config/database.php';

// Mostrar configurações (sem a senha)
echo "Configurações:\n";
echo "Host: " . DB_HOST . "\n";
echo "Banco: " . DB_NAME . "\n";
echo "Usuário: " . DB_USER . "\n";
echo "Porta: " . DB_PORT . "\n";
echo "Charset: " . DB_CHARSET . "\n\n";

try {
    // Montar DSN
    $dsn = "mysql:host=" . DB_HOST . 
           ";port=" . DB_PORT . 
           ";dbname=" . DB_NAME . 
           ";charset=" . DB_CHARSET;
    
    echo "DSN: " . $dsn . "\n\n";
    
    // Tentar conexão
    echo "Tentando conectar...\n";
    $db = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "Conexão bem sucedida!\n\n";
    
    // Testar consulta
    echo "Testando consulta...\n";
    $stmt = $db->query("SELECT VERSION()");
    $version = $stmt->fetchColumn();
    echo "Versão do MySQL: " . $version . "\n";
    
} catch (PDOException $e) {
    echo "ERRO DE CONEXÃO:\n";
    echo $e->getMessage() . "\n\n";
    
    echo "Detalhes do erro:\n";
    print_r($e->errorInfo);
}
?>

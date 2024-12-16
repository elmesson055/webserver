<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Teste Detalhado de Conexão ===\n\n";

// Carregar configurações
require_once dirname(dirname(__FILE__)) . '/config/database.php';

// Informações do PHP
echo "Versão do PHP: " . PHP_VERSION . "\n";
echo "Extensões carregadas:\n";
print_r(get_loaded_extensions());
echo "\n";

// Informações do PDO
echo "\nDrivers PDO disponíveis:\n";
print_r(PDO::getAvailableDrivers());
echo "\n";

// Tentar conexão
echo "\nTentando conexão com o banco...\n";
try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    );
    
    echo "DSN: " . $dsn . "\n";
    echo "Usuário: " . DB_USER . "\n";
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "Conexão estabelecida!\n\n";
    
    // Testar consulta
    $stmt = $pdo->query("SELECT VERSION()");
    echo "Versão do MySQL: " . $stmt->fetchColumn() . "\n";
    
    // Testar autenticação
    $stmt = $pdo->query("SELECT CURRENT_USER()");
    echo "Usuário atual: " . $stmt->fetchColumn() . "\n";
    
} catch (PDOException $e) {
    echo "ERRO DE CONEXÃO:\n";
    echo "Mensagem: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
}
?>

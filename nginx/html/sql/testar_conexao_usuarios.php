<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Teste de Conexão e Listagem de Usuários ===\n\n";

// Carregar configurações
require_once dirname(dirname(__FILE__)) . '/config/database.php';

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
    
    // Listar usuários
    $stmt = $pdo->query("SELECT id, nome_usuario, email, status FROM usuarios");
    $usuarios = $stmt->fetchAll();
    echo "Usuários na tabela:\n";
    foreach ($usuarios as $usuario) {
        echo "- ID: " . $usuario['id'] . ", Nome: " . $usuario['nome_usuario'] . ", Email: " . $usuario['email'] . ", Status: " . $usuario['status'] . "\n";
    }
    
} catch (PDOException $e) {
    echo "ERRO DE CONEXÃO:\n";
    echo "Mensagem: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
}
?>

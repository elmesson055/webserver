<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/database.php';

try {
    // Tentar conexão direta
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    );
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    echo "Tentando conectar ao banco de dados...\n";
    echo "DSN: $dsn\n";
    echo "Usuário: " . DB_USER . "\n";
    
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "Conexão bem sucedida!\n\n";
    
    // Verificar estrutura da tabela usuarios
    echo "Estrutura da tabela usuarios:\n";
    $stmt = $db->query("DESCRIBE usuarios");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($columns);
    
    // Verificar usuários existentes
    echo "\nUsuários cadastrados:\n";
    $stmt = $db->query("SELECT id, nome_usuario, email, tipo_usuario, status FROM usuarios");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($users);
    
} catch (PDOException $e) {
    echo "Erro ao conectar: " . $e->getMessage() . "\n";
    exit(1);
}

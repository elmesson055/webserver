<?php
require_once __DIR__ . '/../app/classes/Database.php';

try {
    $db = Database::getConnection();
    
    // Listar todas as tabelas
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tabelas existentes:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
        
        // Mostrar estrutura de cada tabela
        $stmt = $db->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "  Colunas:\n";
        foreach ($columns as $column) {
            echo "    {$column['Field']} ({$column['Type']})\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}

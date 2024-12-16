<?php
require_once __DIR__ . '/../app/init.php';

echo "<h2>Verificação das Tabelas de Fornecedores</h2>";
echo "<pre>";

// Função para verificar se uma tabela existe
function tableExists($db, $tableName) {
    try {
        $result = $db->query("SELECT 1 FROM {$tableName} LIMIT 1");
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Verificar tabelas
$tables = [
    'Fornecedor',
    'FornecedorResponsavel',
    'Operador'
];

echo "=== Verificando existência das tabelas ===\n";
foreach ($tables as $table) {
    $exists = tableExists($db, $table);
    echo "$table: " . ($exists ? "Existe" : "Não existe") . "\n";
}

// Verificar estrutura da tabela Fornecedor
echo "\n=== Estrutura da tabela Fornecedor ===\n";
try {
    $stmt = $db->query("DESCRIBE Fornecedor");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']}: {$row['Type']} {$row['Null']} {$row['Key']} {$row['Default']}\n";
    }
} catch (Exception $e) {
    echo "Erro ao verificar estrutura da tabela Fornecedor: " . $e->getMessage() . "\n";
}

// Verificar estrutura da tabela FornecedorResponsavel
echo "\n=== Estrutura da tabela FornecedorResponsavel ===\n";
try {
    $stmt = $db->query("DESCRIBE FornecedorResponsavel");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']}: {$row['Type']} {$row['Null']} {$row['Key']} {$row['Default']}\n";
    }
} catch (Exception $e) {
    echo "Erro ao verificar estrutura da tabela FornecedorResponsavel: " . $e->getMessage() . "\n";
}

// Verificar chaves estrangeiras
echo "\n=== Chaves estrangeiras da tabela FornecedorResponsavel ===\n";
try {
    $stmt = $db->query("
        SELECT 
            CONSTRAINT_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_NAME = 'FornecedorResponsavel'
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Constraint: {$row['CONSTRAINT_NAME']}\n";
        echo "Coluna: {$row['COLUMN_NAME']}\n";
        echo "Referencia: {$row['REFERENCED_TABLE_NAME']}.{$row['REFERENCED_COLUMN_NAME']}\n\n";
    }
} catch (Exception $e) {
    echo "Erro ao verificar chaves estrangeiras: " . $e->getMessage() . "\n";
}

// Verificar se há dados nas tabelas
echo "\n=== Contagem de registros ===\n";
foreach ($tables as $table) {
    try {
        $stmt = $db->query("SELECT COUNT(*) as count FROM {$table}");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "$table: $count registros\n";
    } catch (Exception $e) {
        echo "$table: Erro ao contar registros - " . $e->getMessage() . "\n";
    }
}

echo "</pre>";

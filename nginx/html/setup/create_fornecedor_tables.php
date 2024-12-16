<?php
require_once __DIR__ . '/../app/init.php';

echo "<h2>Criação das Tabelas de Fornecedores</h2>";
echo "<pre>";

try {
    // Criar tabela Fornecedor
    $db->exec("
        CREATE TABLE IF NOT EXISTS Fornecedor (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            cnpj VARCHAR(18) NOT NULL,
            telefone VARCHAR(15) NOT NULL,
            email VARCHAR(255),
            observacao TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_cnpj (cnpj)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "Tabela Fornecedor criada com sucesso!\n";

    // Criar tabela FornecedorResponsavel
    $db->exec("
        CREATE TABLE IF NOT EXISTS FornecedorResponsavel (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fornecedor_id INT NOT NULL,
            operador_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_fornecedor_operador (fornecedor_id, operador_id),
            FOREIGN KEY (fornecedor_id) REFERENCES Fornecedor(id) ON DELETE CASCADE,
            FOREIGN KEY (operador_id) REFERENCES Operador(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "Tabela FornecedorResponsavel criada com sucesso!\n";

    // Verificar se a tabela Operador existe
    $stmt = $db->query("SHOW TABLES LIKE 'Operador'");
    if ($stmt->rowCount() == 0) {
        $db->exec("
            CREATE TABLE IF NOT EXISTS Operador (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                telefone VARCHAR(15),
                ativo BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY unique_email (email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "Tabela Operador criada com sucesso!\n";
    } else {
        echo "Tabela Operador já existe.\n";
    }

    // Verificar estrutura das tabelas
    echo "\n=== Estrutura da tabela Fornecedor ===\n";
    $stmt = $db->query("DESCRIBE Fornecedor");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']}: {$row['Type']} {$row['Null']} {$row['Key']} {$row['Default']}\n";
    }

    echo "\n=== Estrutura da tabela FornecedorResponsavel ===\n";
    $stmt = $db->query("DESCRIBE FornecedorResponsavel");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']}: {$row['Type']} {$row['Null']} {$row['Key']} {$row['Default']}\n";
    }

    echo "\n=== Estrutura da tabela Operador ===\n";
    $stmt = $db->query("DESCRIBE Operador");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']}: {$row['Type']} {$row['Null']} {$row['Key']} {$row['Default']}\n";
    }

    // Verificar chaves estrangeiras
    echo "\n=== Chaves estrangeiras da tabela FornecedorResponsavel ===\n";
    $stmt = $db->query("
        SELECT 
            CONSTRAINT_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_NAME = 'FornecedorResponsavel'
        AND REFERENCED_TABLE_NAME IS NOT NULL
        AND TABLE_SCHEMA = DATABASE()
    ");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Constraint: {$row['CONSTRAINT_NAME']}\n";
        echo "Coluna: {$row['COLUMN_NAME']}\n";
        echo "Referencia: {$row['REFERENCED_TABLE_NAME']}.{$row['REFERENCED_COLUMN_NAME']}\n\n";
    }

    echo "\nTodas as tabelas foram criadas e verificadas com sucesso!";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}

echo "</pre>";

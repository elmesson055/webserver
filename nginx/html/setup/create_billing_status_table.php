<?php
require_once __DIR__ . '/../app/init.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS billing_status (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $db->exec($sql);
    echo "Tabela billing_status criada com sucesso!\n";
} catch (PDOException $e) {
    die("Erro ao criar tabela: " . $e->getMessage() . "\n");
}

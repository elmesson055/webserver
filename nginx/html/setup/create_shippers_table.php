<?php
require_once '../config/database.php';

try {
    $createTable = "CREATE TABLE IF NOT EXISTS shippers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        cnpj VARCHAR(18) NOT NULL,
        phone VARCHAR(20),
        email VARCHAR(255),
        observation TEXT,
        active BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    $createOperatorsTable = "CREATE TABLE IF NOT EXISTS shipper_operators (
        id INT AUTO_INCREMENT PRIMARY KEY,
        shipper_id INT,
        operator_id INT,
        active BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (shipper_id) REFERENCES shippers(id),
        FOREIGN KEY (operator_id) REFERENCES users(id)
    )";

    $db->exec($createTable);
    $db->exec($createOperatorsTable);

    echo "Tabelas de embarcadores criadas com sucesso!\n";
} catch (PDOException $e) {
    die("Erro ao criar tabelas: " . $e->getMessage());
}

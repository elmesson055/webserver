<?php
require_once __DIR__ . '/../app/init.php';

try {
    // 1. Criar tabela de relacionamento com clientes
    $sql = "CREATE TABLE IF NOT EXISTS client_billing_status (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_id INT NOT NULL,
        billing_status_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        due_date DATE,
        payment_date DATE,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
        FOREIGN KEY (billing_status_id) REFERENCES billing_status(id) ON DELETE RESTRICT,
        INDEX idx_client (client_id),
        INDEX idx_status (billing_status_id),
        INDEX idx_due_date (due_date),
        INDEX idx_payment_date (payment_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $db->exec($sql);
    echo "Tabela client_billing_status criada com sucesso!\n";

    // 2. Criar tabela de histórico de status
    $sql = "CREATE TABLE IF NOT EXISTS billing_status_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_billing_status_id INT NOT NULL,
        old_status_id INT,
        new_status_id INT NOT NULL,
        changed_by INT NOT NULL,
        change_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        reason TEXT,
        FOREIGN KEY (client_billing_status_id) REFERENCES client_billing_status(id) ON DELETE CASCADE,
        FOREIGN KEY (old_status_id) REFERENCES billing_status(id) ON DELETE RESTRICT,
        FOREIGN KEY (new_status_id) REFERENCES billing_status(id) ON DELETE RESTRICT,
        FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE RESTRICT,
        INDEX idx_client_billing (client_billing_status_id),
        INDEX idx_change_date (change_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $db->exec($sql);
    echo "Tabela billing_status_history criada com sucesso!\n";

    // 3. Criar tabela de relacionamento com custos
    $sql = "CREATE TABLE IF NOT EXISTS cost_billing_status (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cost_id INT NOT NULL,
        billing_status_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (cost_id) REFERENCES costs(id) ON DELETE CASCADE,
        FOREIGN KEY (billing_status_id) REFERENCES billing_status(id) ON DELETE RESTRICT,
        INDEX idx_cost (cost_id),
        INDEX idx_billing_status (billing_status_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $db->exec($sql);
    echo "Tabela cost_billing_status criada com sucesso!\n";

} catch (PDOException $e) {
    die("Erro ao criar tabelas de relacionamento: " . $e->getMessage() . "\n");
}

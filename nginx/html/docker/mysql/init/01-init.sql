-- Criar banco de dados se não existir
CREATE DATABASE IF NOT EXISTS custo_extras;
USE custo_extras;

-- Criar tabela de usuários
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    department ENUM('Transportes', 'Custos', 'Financeiro') NOT NULL DEFAULT 'Custos',
    active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir usuário admin padrão se não existir
INSERT IGNORE INTO users (username, password, name, email, department)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin@exemplo.com', 'Custos');

-- Criar tabela de custos extras
CREATE TABLE IF NOT EXISTS custos_extras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    valor DECIMAL(10,2) NOT NULL,
    descricao TEXT NOT NULL,
    data_registro DATE NOT NULL,
    status ENUM('pendente', 'em_aprovacao', 'aprovado', 'rejeitado') NOT NULL DEFAULT 'pendente',
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir alguns dados de exemplo
INSERT INTO custos_extras (valor, descricao, data_registro, status, user_id)
SELECT 
    ROUND(RAND() * 1000 + 500, 2),
    CONCAT('Custo extra de teste ', n),
    DATE_SUB(CURRENT_DATE, INTERVAL FLOOR(RAND() * 30) DAY),
    ELT(FLOOR(RAND() * 4) + 1, 'pendente', 'em_aprovacao', 'aprovado', 'rejeitado'),
    1
FROM (
    SELECT a.N + b.N * 10 + 1 n
    FROM (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
         (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) b
    ORDER BY n LIMIT 50
) numbers
ON DUPLICATE KEY UPDATE id = id;

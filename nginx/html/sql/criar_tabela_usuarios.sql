-- Criar a tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nome_completo VARCHAR(100) NOT NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inserir o usuário admin
INSERT INTO usuarios (nome_usuario, email, password_hash, nome_completo, status)
VALUES (
    'admin_logistica',
    'admin@logistica.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- senha: password
    'Administrador do Sistema',
    'ativo'
);

-- Verificar a estrutura
DESCRIBE usuarios;

-- Verificar o usuário inserido
SELECT id, nome_usuario, email, nome_completo, status, data_criacao 
FROM usuarios;

-- Criar tabela de usuários se não existir
CREATE TABLE IF NOT EXISTS usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_usuario VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nome_completo VARCHAR(100) NOT NULL,
    tipo_usuario ENUM('admin', 'gerente', 'operador') NOT NULL,
    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
    ultimo_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inserir usuário admin de teste (senha: admin123)
INSERT INTO usuarios (
    nome_usuario, 
    email, 
    password_hash, 
    nome_completo, 
    tipo_usuario, 
    status
) VALUES (
    'admin',
    'admin@sistema.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- hash de 'admin123'
    'Administrador do Sistema',
    'admin',
    'ativo'
) ON DUPLICATE KEY UPDATE 
    email = VALUES(email),
    password_hash = VALUES(password_hash),
    nome_completo = VALUES(nome_completo),
    tipo_usuario = VALUES(tipo_usuario),
    status = VALUES(status);

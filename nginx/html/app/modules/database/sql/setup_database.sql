-- Criar banco de dados se não existir
CREATE DATABASE IF NOT EXISTS logistica_transportes
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Usar o banco de dados
USE logistica_transportes;

-- Criar usuário se não existir e conceder privilégios
CREATE USER IF NOT EXISTS 'sistema_user'@'localhost' IDENTIFIED BY 'Log@2024#Adm';
GRANT ALL PRIVILEGES ON logistica_transportes.* TO 'sistema_user'@'localhost';
FLUSH PRIVILEGES;

-- Criar tabela de usuários
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Criar tabela de funções (roles)
CREATE TABLE IF NOT EXISTS funcoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Criar tabela de permissões
CREATE TABLE IF NOT EXISTS permissoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criar tabela de relacionamento entre funções e permissões
CREATE TABLE IF NOT EXISTS funcao_permissao (
    funcao_id INT,
    permissao_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (funcao_id, permissao_id),
    FOREIGN KEY (funcao_id) REFERENCES funcoes(id),
    FOREIGN KEY (permissao_id) REFERENCES permissoes(id)
);

-- Criar tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(50) NOT NULL UNIQUE,
    sobrenome VARCHAR(100),
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    funcao_id INT,
    status ENUM('Ativo', 'Inativo', 'Bloqueado') DEFAULT 'Ativo',
    ultimo_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (funcao_id) REFERENCES funcoes(id)
);

-- Criar tabela de tentativas de login
CREATE TABLE IF NOT EXISTS tentativas_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    tentativas INT DEFAULT 1,
    bloqueado_ate DATETIME,
    data_tentativa TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Inserir função de administrador
INSERT INTO funcoes (nome, descricao) 
VALUES ('Administrador', 'Acesso total ao sistema')
ON DUPLICATE KEY UPDATE nome = nome;

-- Inserir permissões básicas
INSERT INTO permissoes (nome, descricao) VALUES 
('admin_access', 'Acesso ao painel administrativo'),
('user_manage', 'Gerenciar usuários'),
('role_manage', 'Gerenciar funções e permissões')
ON DUPLICATE KEY UPDATE nome = nome;

-- Associar permissões ao administrador
INSERT INTO funcao_permissao (funcao_id, permissao_id)
SELECT f.id, p.id
FROM funcoes f, permissoes p
WHERE f.nome = 'Administrador'
ON DUPLICATE KEY UPDATE funcao_id = funcao_id;

-- Criar ou atualizar tabela funcoes
CREATE TABLE IF NOT EXISTS funcoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Criar ou atualizar tabela permissoes
CREATE TABLE IF NOT EXISTS permissoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Criar ou atualizar tabela usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(100) NOT NULL,
    sobrenome VARCHAR(100),
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    funcao_id INT,
    ultimo_login DATETIME,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (funcao_id) REFERENCES funcoes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Criar ou atualizar tabela funcao_permissao
CREATE TABLE IF NOT EXISTS funcao_permissao (
    funcao_id INT NOT NULL,
    permissao_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (funcao_id, permissao_id),
    FOREIGN KEY (funcao_id) REFERENCES funcoes(id) ON DELETE CASCADE,
    FOREIGN KEY (permissao_id) REFERENCES permissoes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir permissões básicas se não existirem
INSERT IGNORE INTO permissoes (nome, descricao) VALUES
('Visualizar Usuários', 'Permite visualizar a lista de usuários'),
('Criar Usuários', 'Permite criar novos usuários'),
('Editar Usuários', 'Permite editar usuários existentes'),
('Excluir Usuários', 'Permite excluir usuários'),
('Visualizar Funções', 'Permite visualizar a lista de funções'),
('Criar Funções', 'Permite criar novas funções'),
('Editar Funções', 'Permite editar funções existentes'),
('Excluir Funções', 'Permite excluir funções'),
('Visualizar Permissões', 'Permite visualizar a lista de permissões'),
('Gerenciar Permissões', 'Permite gerenciar permissões das funções');

-- Inserir função de Administrador se não existir
INSERT IGNORE INTO funcoes (nome, descricao) VALUES
('Administrador', 'Acesso total ao sistema');

-- Associar todas as permissões ao Administrador
INSERT IGNORE INTO funcao_permissao (funcao_id, permissao_id)
SELECT 
    (SELECT id FROM funcoes WHERE nome = 'Administrador'),
    id
FROM permissoes;

-- Tabela de Funções (se não existir)
CREATE TABLE IF NOT EXISTS funcoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Permissões (se não existir)
CREATE TABLE IF NOT EXISTS permissoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Relacionamento entre Funções e Permissões (se não existir)
CREATE TABLE IF NOT EXISTS funcao_permissao (
    funcao_id INT,
    permissao_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (funcao_id, permissao_id),
    FOREIGN KEY (funcao_id) REFERENCES funcoes(id) ON DELETE CASCADE,
    FOREIGN KEY (permissao_id) REFERENCES permissoes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Usuários (se não existir)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    funcao_id INT,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (funcao_id) REFERENCES funcoes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir permissões básicas (se não existirem)
INSERT IGNORE INTO permissoes (nome, descricao, codigo) VALUES
('Visualizar Usuários', 'Permite visualizar a lista de usuários', 'usuarios.view'),
('Criar Usuários', 'Permite criar novos usuários', 'usuarios.create'),
('Editar Usuários', 'Permite editar usuários existentes', 'usuarios.edit'),
('Excluir Usuários', 'Permite excluir usuários', 'usuarios.delete'),
('Visualizar Funções', 'Permite visualizar a lista de funções', 'funcoes.view'),
('Criar Funções', 'Permite criar novas funções', 'funcoes.create'),
('Editar Funções', 'Permite editar funções existentes', 'funcoes.edit'),
('Excluir Funções', 'Permite excluir funções', 'funcoes.delete'),
('Visualizar Permissões', 'Permite visualizar a lista de permissões', 'permissoes.view'),
('Gerenciar Permissões', 'Permite gerenciar permissões das funções', 'permissoes.manage');

-- Inserir função de Administrador (se não existir)
INSERT IGNORE INTO funcoes (nome, descricao) VALUES
('Administrador', 'Acesso total ao sistema');

-- Associar todas as permissões ao Administrador
INSERT IGNORE INTO funcao_permissao (funcao_id, permissao_id)
SELECT 
    (SELECT id FROM funcoes WHERE nome = 'Administrador'),
    id
FROM permissoes;

-- Tabela de Usuários
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

-- Tabela de Funções
CREATE TABLE IF NOT EXISTS funcoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Permissões
CREATE TABLE IF NOT EXISTS permissoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Relacionamento entre Funções e Permissões
CREATE TABLE IF NOT EXISTS funcao_permissao (
    funcao_id INT,
    permissao_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (funcao_id, permissao_id),
    FOREIGN KEY (funcao_id) REFERENCES funcoes(id) ON DELETE CASCADE,
    FOREIGN KEY (permissao_id) REFERENCES permissoes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir permissões básicas
INSERT INTO permissoes (nome, descricao, codigo) VALUES
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

-- Inserir função de Administrador
INSERT INTO funcoes (nome, descricao) VALUES
('Administrador', 'Acesso total ao sistema');

-- Associar todas as permissões ao Administrador
INSERT INTO funcao_permissao (funcao_id, permissao_id)
SELECT 
    (SELECT id FROM funcoes WHERE nome = 'Administrador'),
    id
FROM permissoes;

-- Inserir usuário administrador padrão
INSERT INTO usuarios (nome, email, senha, funcao_id) VALUES
('Administrador', 'admin@sistema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- senha: password
    (SELECT id FROM funcoes WHERE nome = 'Administrador'));

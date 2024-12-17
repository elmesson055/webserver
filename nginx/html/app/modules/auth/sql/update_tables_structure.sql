-- Atualizar estrutura da tabela usuarios
ALTER TABLE usuarios
    CHANGE COLUMN IF EXISTS nome nome_usuario VARCHAR(100) NOT NULL,
    CHANGE COLUMN IF EXISTS senha password_hash VARCHAR(255) NOT NULL,
    ADD COLUMN IF NOT EXISTS sobrenome VARCHAR(100) AFTER nome_usuario,
    ADD COLUMN IF NOT EXISTS ultimo_login DATETIME DEFAULT NULL,
    MODIFY COLUMN status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    MODIFY COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Atualizar estrutura da tabela funcoes
ALTER TABLE funcoes
    ADD COLUMN IF NOT EXISTS ativo BOOLEAN DEFAULT TRUE,
    MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    MODIFY COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Atualizar estrutura da tabela permissoes
ALTER TABLE permissoes
    DROP COLUMN IF EXISTS codigo,
    DROP COLUMN IF EXISTS status,
    MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Criar tabela de histórico de funções
CREATE TABLE IF NOT EXISTS funcoes_historico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcao_id INT NOT NULL,
    campo_alterado VARCHAR(50) NOT NULL,
    valor_antigo TEXT,
    usuario_id INT,
    data_alteracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valor_novo TEXT,
    FOREIGN KEY (funcao_id) REFERENCES funcoes(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Criar tabela de tokens de redefinição de senha
CREATE TABLE IF NOT EXISTS tokens_redefinicao_senha (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    valido_ate DATETIME NOT NULL,
    usado BOOLEAN DEFAULT FALSE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Criar tabela de tentativas de login
CREATE TABLE IF NOT EXISTS tentativas_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    ip_address VARCHAR(45) NOT NULL,
    tentativas INT DEFAULT 1,
    bloqueado_ate DATETIME,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Criar tabela de permissões de usuário
CREATE TABLE IF NOT EXISTS usuario_permissoes (
    usuario_id INT NOT NULL,
    permissao_id INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (usuario_id, permissao_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (permissao_id) REFERENCES permissoes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

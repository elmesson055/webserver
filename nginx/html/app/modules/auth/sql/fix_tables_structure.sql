-- Verificar e remover tabela duplicada funcao_permissoes se existir
DROP TABLE IF EXISTS funcao_permissoes;

-- Atualizar estrutura da tabela usuarios
ALTER TABLE usuarios
    CHANGE COLUMN nome nome_usuario VARCHAR(100) NOT NULL,
    ADD COLUMN sobrenome VARCHAR(100) AFTER nome_usuario,
    CHANGE COLUMN senha password_hash VARCHAR(255) NOT NULL,
    ADD COLUMN ultimo_login DATETIME DEFAULT NULL,
    MODIFY COLUMN status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    MODIFY COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Atualizar estrutura da tabela funcoes
ALTER TABLE funcoes
    ADD COLUMN ativo BOOLEAN DEFAULT TRUE;

-- Atualizar estrutura da tabela permissoes
ALTER TABLE permissoes
    DROP COLUMN codigo,
    DROP COLUMN status;

-- Verificar e atualizar chaves estrangeiras em funcao_permissao
ALTER TABLE funcao_permissao
    DROP FOREIGN KEY IF EXISTS funcao_permissao_ibfk_1,
    DROP FOREIGN KEY IF EXISTS funcao_permissao_ibfk_2,
    ADD CONSTRAINT funcao_permissao_ibfk_1 FOREIGN KEY (funcao_id) REFERENCES funcoes(id) ON DELETE CASCADE,
    ADD CONSTRAINT funcao_permissao_ibfk_2 FOREIGN KEY (permissao_id) REFERENCES permissoes(id) ON DELETE CASCADE;

-- Verificar e atualizar chaves estrangeiras em usuario_permissoes
ALTER TABLE usuario_permissoes
    DROP FOREIGN KEY IF EXISTS usuario_permissoes_ibfk_1,
    DROP FOREIGN KEY IF EXISTS usuario_permissoes_ibfk_2,
    ADD CONSTRAINT usuario_permissoes_ibfk_1 FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    ADD CONSTRAINT usuario_permissoes_ibfk_2 FOREIGN KEY (permissao_id) REFERENCES permissoes(id) ON DELETE CASCADE;

-- Verificar e atualizar chaves estrangeiras em tokens_redefinicao_senha
ALTER TABLE tokens_redefinicao_senha
    DROP FOREIGN KEY IF EXISTS tokens_redefinicao_senha_ibfk_1,
    ADD CONSTRAINT tokens_redefinicao_senha_ibfk_1 FOREIGN KEY (usuario_id) REFERENCES usuarios(id);

-- Verificar e atualizar chaves estrangeiras em tentativas_login
ALTER TABLE tentativas_login
    DROP FOREIGN KEY IF EXISTS tentativas_login_ibfk_1,
    ADD CONSTRAINT tentativas_login_ibfk_1 FOREIGN KEY (usuario_id) REFERENCES usuarios(id);

-- Verificar e atualizar chaves estrangeiras em funcoes_historico
ALTER TABLE funcoes_historico
    DROP FOREIGN KEY IF EXISTS funcoes_historico_ibfk_1,
    DROP FOREIGN KEY IF EXISTS funcoes_historico_ibfk_2,
    ADD CONSTRAINT funcoes_historico_ibfk_1 FOREIGN KEY (funcao_id) REFERENCES funcoes(id),
    ADD CONSTRAINT funcoes_historico_ibfk_2 FOREIGN KEY (usuario_id) REFERENCES usuarios(id);

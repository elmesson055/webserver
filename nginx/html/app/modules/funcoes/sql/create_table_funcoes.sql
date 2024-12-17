-- Removendo tabelas na ordem correta
DROP TABLE IF EXISTS funcao_permissoes;
DROP TABLE IF EXISTS funcoes_historico;
DROP TABLE IF EXISTS funcoes;
DROP TABLE IF EXISTS permissoes;

-- Criando tabela de permissões
CREATE TABLE permissoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao VARCHAR(255),
    modulo VARCHAR(50),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Criando tabela de funções
CREATE TABLE funcoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Criando tabela de histórico de funções
CREATE TABLE funcoes_historico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcao_id INT NOT NULL,
    campo_alterado VARCHAR(50) NOT NULL,
    valor_antigo TEXT,
    valor_novo TEXT,
    usuario_id INT,
    data_alteracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (funcao_id) REFERENCES funcoes(id)
) ENGINE=InnoDB;

-- Criando tabela de relacionamento função-permissões
CREATE TABLE funcao_permissoes (
    funcao_id INT NOT NULL,
    permissao_id INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (funcao_id, permissao_id),
    FOREIGN KEY (funcao_id) REFERENCES funcoes(id) ON DELETE CASCADE,
    FOREIGN KEY (permissao_id) REFERENCES permissoes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Limpando dados existentes para evitar conflitos
DELETE FROM funcao_permissoes;
DELETE FROM permissoes;

-- Inserindo as permissões básicas
INSERT INTO permissoes (nome, descricao, modulo) VALUES 
('dashboard.view', 'Visualizar Dashboard', 'dashboard');

-- Permissões de Usuários
INSERT INTO permissoes (nome, descricao, modulo) VALUES 
('usuarios.view', 'Visualizar Usuários', 'usuarios'),
('usuarios.create', 'Criar Usuários', 'usuarios'),
('usuarios.edit', 'Editar Usuários', 'usuarios'),
('usuarios.delete', 'Excluir Usuários', 'usuarios');

-- Permissões de Funções
INSERT INTO permissoes (nome, descricao, modulo) VALUES 
('funcoes.view', 'Visualizar Funções', 'funcoes'),
('funcoes.create', 'Criar Funções', 'funcoes'),
('funcoes.edit', 'Editar Funções', 'funcoes'),
('funcoes.delete', 'Excluir Funções', 'funcoes');

-- Permissões de Embarcadores
INSERT INTO permissoes (nome, descricao, modulo) VALUES 
('embarcadores.view', 'Visualizar embarcadores', 'embarcadores'),
('embarcadores.create', 'Criar novos embarcadores', 'embarcadores'),
('embarcadores.edit', 'Editar embarcadores existentes', 'embarcadores'),
('embarcadores.delete', 'Excluir embarcadores', 'embarcadores'),
('embarcadores.export', 'Exportar dados de embarcadores', 'embarcadores');

-- Permissões de Fornecedores
INSERT INTO permissoes (nome, descricao, modulo) VALUES 
('fornecedores.view', 'Visualizar fornecedores', 'fornecedores'),
('fornecedores.create', 'Criar novos fornecedores', 'fornecedores'),
('fornecedores.edit', 'Editar fornecedores existentes', 'fornecedores'),
('fornecedores.delete', 'Excluir fornecedores', 'fornecedores'),
('fornecedores.export', 'Exportar dados de fornecedores', 'fornecedores');

-- Permissões SEFAZ
INSERT INTO permissoes (nome, descricao, modulo) VALUES 
('cte.emit', 'Emitir CT-e', 'sefaz'),
('cte.consult', 'Consultar CT-e', 'sefaz'),
('cte.cancel', 'Cancelar CT-e', 'sefaz'),
('cte.correct', 'Emitir carta de correção de CT-e', 'sefaz'),
('mdfe.emit', 'Emitir MDF-e', 'sefaz'),
('mdfe.close', 'Encerrar MDF-e', 'sefaz'),
('mdfe.cancel', 'Cancelar MDF-e', 'sefaz'),
('mdfe.driver', 'Incluir condutor em MDF-e', 'sefaz');

-- Permissões de Documentos
INSERT INTO permissoes (nome, descricao, modulo) VALUES 
('docs.view', 'Visualizar documentos fiscais', 'documentos'),
('docs.validate', 'Validar documentos fiscais', 'documentos'),
('docs.print', 'Imprimir documentos fiscais', 'documentos'),
('docs.download', 'Baixar documentos fiscais', 'documentos');

-- Permissões de Monitor
INSERT INTO permissoes (nome, descricao, modulo) VALUES 
('monitor.services', 'Monitorar status dos serviços', 'monitor'),
('monitor.contingency', 'Gerenciar contingência', 'monitor'),
('monitor.logs', 'Visualizar logs do sistema', 'monitor');

-- Inserindo funções básicas
INSERT INTO funcoes (nome, descricao, ativo) VALUES
('Administrador', 'Acesso total ao sistema', 1),
('Gerente', 'Gerenciamento geral do sistema', 1),
('Operador', 'Operações básicas do sistema', 1),
('Visualizador', 'Apenas visualização do sistema', 1);

-- Atribuindo permissões ao Administrador (ID 1)
INSERT INTO funcao_permissoes (funcao_id, permissao_id)
SELECT 1, id FROM permissoes;

-- Atribuindo permissões ao Gerente (ID 2)
INSERT INTO funcao_permissoes (funcao_id, permissao_id)
SELECT 2, id FROM permissoes 
WHERE nome LIKE '%.view' 
   OR nome LIKE '%.export'
   OR nome LIKE 'docs.%'
   OR nome LIKE 'cte.%'
   OR nome LIKE 'mdfe.%'
   OR nome LIKE 'monitor.%';

-- Atribuindo permissões ao Operador (ID 3)
INSERT INTO funcao_permissoes (funcao_id, permissao_id)
SELECT 3, id FROM permissoes 
WHERE nome LIKE '%.view' 
   OR nome LIKE '%.export'
   OR nome LIKE 'docs.%';

-- Atribuindo permissões ao Visualizador (ID 4)
INSERT INTO funcao_permissoes (funcao_id, permissao_id)
SELECT 4, id FROM permissoes 
WHERE nome LIKE '%.view';

-- Criando trigger para histórico de alterações
DELIMITER //
CREATE TRIGGER tr_funcoes_after_update
AFTER UPDATE ON funcoes
FOR EACH ROW
BEGIN
    IF NEW.nome != OLD.nome THEN
        INSERT INTO funcoes_historico (funcao_id, campo_alterado, valor_antigo, valor_novo)
        VALUES (NEW.id, 'nome', OLD.nome, NEW.nome);
    END IF;
    
    IF NEW.descricao != OLD.descricao THEN
        INSERT INTO funcoes_historico (funcao_id, campo_alterado, valor_antigo, valor_novo)
        VALUES (NEW.id, 'descricao', OLD.descricao, NEW.descricao);
    END IF;
    
    IF NEW.ativo != OLD.ativo THEN
        INSERT INTO funcoes_historico (funcao_id, campo_alterado, valor_antigo, valor_novo)
        VALUES (NEW.id, 'ativo', OLD.ativo, NEW.ativo);
    END IF;
END //

-- Criando trigger para histórico de exclusões
CREATE TRIGGER tr_funcoes_before_delete
BEFORE DELETE ON funcoes
FOR EACH ROW
BEGIN
    INSERT INTO funcoes_historico (funcao_id, campo_alterado, valor_antigo, valor_novo)
    VALUES (OLD.id, 'exclusao', 'registro_completo', NULL);
END //

DELIMITER ;

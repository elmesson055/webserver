-- Atualizar estrutura da tabela usuarios
ALTER TABLE usuarios
    ADD COLUMN IF NOT EXISTS nome_usuario VARCHAR(100),
    ADD COLUMN IF NOT EXISTS sobrenome VARCHAR(100),
    ADD COLUMN IF NOT EXISTS password_hash VARCHAR(255),
    ADD COLUMN IF NOT EXISTS ultimo_login DATETIME,
    ADD COLUMN IF NOT EXISTS status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo';

-- Atualizar dados da tabela usuarios (assumindo que 'nome' existe)
UPDATE usuarios 
SET nome_usuario = nome 
WHERE nome_usuario IS NULL AND nome IS NOT NULL;

-- Remover colunas antigas da tabela usuarios após migração
ALTER TABLE usuarios
    DROP COLUMN IF EXISTS nome,
    DROP COLUMN IF EXISTS senha;

-- Atualizar estrutura da tabela funcoes (verificar se ativo já existe)
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'logistica_transportes' 
    AND table_name = 'funcoes' 
    AND column_name = 'ativo';

SET @alter_funcoes = IF(@col_exists = 0, 
    'ALTER TABLE funcoes ADD COLUMN ativo BOOLEAN DEFAULT TRUE',
    'SELECT 1');
PREPARE alter_stmt FROM @alter_funcoes;
EXECUTE alter_stmt;
DEALLOCATE PREPARE alter_stmt;

-- Atualizar estrutura da tabela permissoes (verificar se codigo existe)
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'logistica_transportes' 
    AND table_name = 'permissoes' 
    AND column_name = 'codigo';

SET @alter_permissoes = IF(@col_exists > 0, 
    'ALTER TABLE permissoes DROP COLUMN codigo',
    'SELECT 1');
PREPARE alter_stmt FROM @alter_permissoes;
EXECUTE alter_stmt;
DEALLOCATE PREPARE alter_stmt;

-- Verificar e remover status se existir
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'logistica_transportes' 
    AND table_name = 'permissoes' 
    AND column_name = 'status';

SET @alter_permissoes_status = IF(@col_exists > 0, 
    'ALTER TABLE permissoes DROP COLUMN status',
    'SELECT 1');
PREPARE alter_stmt FROM @alter_permissoes_status;
EXECUTE alter_stmt;
DEALLOCATE PREPARE alter_stmt;

-- Atualizar foreign keys (primeiro removendo as existentes)
SELECT CONCAT('ALTER TABLE funcao_permissao DROP FOREIGN KEY ', CONSTRAINT_NAME, ';')
INTO @drop_fk
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'logistica_transportes'
    AND TABLE_NAME = 'funcao_permissao'
    AND REFERENCED_TABLE_NAME IS NOT NULL
LIMIT 1;

SET @drop_fk = IFNULL(@drop_fk, 'SELECT 1');
PREPARE alter_stmt FROM @drop_fk;
EXECUTE alter_stmt;
DEALLOCATE PREPARE alter_stmt;

-- Adicionar foreign keys corretas
ALTER TABLE funcao_permissao
    ADD CONSTRAINT fk_funcao_permissao_funcao FOREIGN KEY (funcao_id) REFERENCES funcoes(id) ON DELETE CASCADE,
    ADD CONSTRAINT fk_funcao_permissao_permissao FOREIGN KEY (permissao_id) REFERENCES permissoes(id) ON DELETE CASCADE;

-- Verificar e remover tabela duplicada
DROP TABLE IF EXISTS funcao_permissoes;

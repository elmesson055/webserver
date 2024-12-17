-- Verificar e adicionar colunas na tabela usuarios
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'logistica_transportes' 
    AND table_name = 'usuarios' 
    AND column_name = 'nome_usuario';

SET @alter_usuarios = IF(@col_exists = 0, 
    'ALTER TABLE usuarios ADD COLUMN nome_usuario VARCHAR(100)',
    'SELECT 1');
PREPARE alter_stmt FROM @alter_usuarios;
EXECUTE alter_stmt;
DEALLOCATE PREPARE alter_stmt;

-- Adicionar sobrenome
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'logistica_transportes' 
    AND table_name = 'usuarios' 
    AND column_name = 'sobrenome';

SET @alter_usuarios = IF(@col_exists = 0, 
    'ALTER TABLE usuarios ADD COLUMN sobrenome VARCHAR(100)',
    'SELECT 1');
PREPARE alter_stmt FROM @alter_usuarios;
EXECUTE alter_stmt;
DEALLOCATE PREPARE alter_stmt;

-- Adicionar password_hash
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'logistica_transportes' 
    AND table_name = 'usuarios' 
    AND column_name = 'password_hash';

SET @alter_usuarios = IF(@col_exists = 0, 
    'ALTER TABLE usuarios ADD COLUMN password_hash VARCHAR(255)',
    'SELECT 1');
PREPARE alter_stmt FROM @alter_usuarios;
EXECUTE alter_stmt;
DEALLOCATE PREPARE alter_stmt;

-- Adicionar ultimo_login
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'logistica_transportes' 
    AND table_name = 'usuarios' 
    AND column_name = 'ultimo_login';

SET @alter_usuarios = IF(@col_exists = 0, 
    'ALTER TABLE usuarios ADD COLUMN ultimo_login DATETIME',
    'SELECT 1');
PREPARE alter_stmt FROM @alter_usuarios;
EXECUTE alter_stmt;
DEALLOCATE PREPARE alter_stmt;

-- Verificar e remover coluna nome após migração
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'logistica_transportes' 
    AND table_name = 'usuarios' 
    AND column_name = 'nome';

SET @alter_usuarios = IF(@col_exists > 0, 
    'ALTER TABLE usuarios DROP COLUMN nome',
    'SELECT 1');
PREPARE alter_stmt FROM @alter_usuarios;
EXECUTE alter_stmt;
DEALLOCATE PREPARE alter_stmt;

-- Verificar e remover coluna senha após migração
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'logistica_transportes' 
    AND table_name = 'usuarios' 
    AND column_name = 'senha';

SET @alter_usuarios = IF(@col_exists > 0, 
    'ALTER TABLE usuarios DROP COLUMN senha',
    'SELECT 1');
PREPARE alter_stmt FROM @alter_usuarios;
EXECUTE alter_stmt;
DEALLOCATE PREPARE alter_stmt;

-- Atualizar estrutura da tabela funcoes
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

-- Verificar e remover colunas da tabela permissoes
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

SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'logistica_transportes' 
    AND table_name = 'permissoes' 
    AND column_name = 'status';

SET @alter_permissoes = IF(@col_exists > 0, 
    'ALTER TABLE permissoes DROP COLUMN status',
    'SELECT 1');
PREPARE alter_stmt FROM @alter_permissoes;
EXECUTE alter_stmt;
DEALLOCATE PREPARE alter_stmt;

-- Remover tabela duplicada se existir
DROP TABLE IF EXISTS funcao_permissoes;

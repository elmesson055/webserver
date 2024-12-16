-- Verificar estrutura da tabela
SHOW COLUMNS FROM usuarios;

-- Verificar função ADMIN
SELECT id, nome FROM funcoes WHERE nome = 'ADMIN';

-- Verificar usuário existente
SELECT 
    id,
    nome_usuario,
    email,
    nome,
    sobrenome,
    ativo,
    status,
    funcao_id,
    ultimo_login
FROM usuarios 
WHERE nome_usuario = 'admin_logistica';

-- Atualizar senha do usuário admin com hash testado
UPDATE usuarios 
SET 
    password_hash = '$2y$10$f7ye89B8Q9/Fr5LpI0pLj.iKcuQfYmuKY7sni5daZvHTprmIZBeQa'
WHERE nome_usuario = 'admin_logistica';

COMMIT;

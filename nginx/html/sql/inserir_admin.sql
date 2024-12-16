-- Inserir o usuário admin com a estrutura correta
INSERT INTO usuarios (
    nome_usuario,
    email,
    password_hash,
    nome,
    sobrenome,
    ativo,
    status,
    funcao_id
) VALUES (
    'admin_logistica',
    'admin@logistica.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- senha: password
    'Administrador',
    'do Sistema',
    1, -- ativo
    'ATIVO',
    1  -- assumindo que 1 é o ID para função de administrador
);

-- Verificar o usuário inserido
SELECT 
    id,
    nome_usuario,
    email,
    CONCAT(nome, ' ', sobrenome) as nome_completo,
    status,
    criado_em,
    ativo
FROM usuarios
WHERE nome_usuario = 'admin_logistica';

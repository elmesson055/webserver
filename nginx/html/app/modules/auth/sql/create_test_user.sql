-- Criar usuário de teste
INSERT INTO usuarios (
    nome_usuario,
    sobrenome,
    email,
    password_hash,
    funcao_id,
    status
) VALUES (
    'admin_logistica',
    'Sistema',
    'admin.logistica@sistema.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- senha: 123456
    (SELECT id FROM funcoes WHERE nome = 'Administrador' LIMIT 1),
    'Ativo'
);

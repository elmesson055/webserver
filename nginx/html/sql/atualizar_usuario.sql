-- Primeiro, vamos ver quais stored routines usam este usuário
SELECT ROUTINE_NAME, ROUTINE_TYPE, DEFINER 
FROM information_schema.routines 
WHERE ROUTINE_SCHEMA = 'logistica_transportes' 
AND DEFINER LIKE '%logistica_admin%';

-- Alterar a senha para ambos os usuários
ALTER USER 'logistica_admin'@'localhost' IDENTIFIED BY 'Log@2024#Adm';
ALTER USER 'logistica_admin'@'%' IDENTIFIED BY 'Log@2024#Adm';

-- Garantir que ambos têm as permissões corretas
GRANT ALL PRIVILEGES ON logistica_transportes.* TO 'logistica_admin'@'localhost';
GRANT ALL PRIVILEGES ON logistica_transportes.* TO 'logistica_admin'@'%';

-- Aplicar mudanças
FLUSH PRIVILEGES;

-- Verificar as permissões
SHOW GRANTS FOR 'logistica_admin'@'localhost';
SHOW GRANTS FOR 'logistica_admin'@'%';

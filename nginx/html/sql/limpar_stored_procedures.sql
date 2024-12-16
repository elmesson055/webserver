-- Primeiro, vamos ver quais stored procedures existem
SELECT ROUTINE_NAME, DEFINER, ROUTINE_TYPE
FROM information_schema.routines
WHERE ROUTINE_SCHEMA = 'logistica_transportes';

-- Depois, vamos ver quais views existem
SELECT TABLE_NAME, DEFINER, SECURITY_TYPE, VIEW_DEFINITION
FROM information_schema.views
WHERE TABLE_SCHEMA = 'logistica_transportes';

-- Agora vamos dropar todas as stored procedures
DROP PROCEDURE IF EXISTS logistica_transportes.sp_inserir_usuario;
DROP PROCEDURE IF EXISTS logistica_transportes.sp_atualizar_usuario;
DROP PROCEDURE IF EXISTS logistica_transportes.sp_deletar_usuario;
DROP PROCEDURE IF EXISTS logistica_transportes.sp_buscar_usuario;
DROP PROCEDURE IF EXISTS logistica_transportes.sp_listar_usuarios;
DROP PROCEDURE IF EXISTS logistica_transportes.sp_verificar_login;

-- E todas as views (se existirem)
DROP VIEW IF EXISTS logistica_transportes.vw_usuarios;
DROP VIEW IF EXISTS logistica_transportes.vw_usuarios_ativos;

-- Agora sim podemos tentar dropar o usuário
DROP USER IF EXISTS 'logistica_admin'@'localhost';
DROP USER IF EXISTS 'logistica_admin'@'127.0.0.1';
DROP USER IF EXISTS 'logistica_admin'@'%';

-- Criar o novo usuário
CREATE USER 'sistema_user'@'localhost' IDENTIFIED BY 'Log@2024#Adm';

-- Garantir que o banco existe
CREATE DATABASE IF NOT EXISTS logistica_transportes;

-- Dar todas as permissões necessárias
GRANT ALL PRIVILEGES ON logistica_transportes.* TO 'sistema_user'@'localhost';

-- Aplicar mudanças
FLUSH PRIVILEGES;

-- Verificar o novo usuário
SELECT User, Host, plugin 
FROM mysql.user 
WHERE User = 'sistema_user';

-- Verificar permissões
SHOW GRANTS FOR 'sistema_user'@'localhost';

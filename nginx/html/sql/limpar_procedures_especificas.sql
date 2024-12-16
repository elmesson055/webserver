-- Dropar as stored procedures específicas
DROP PROCEDURE IF EXISTS logistica_transportes.sp_processar_cross_docking;
DROP PROCEDURE IF EXISTS logistica_transportes.sp_validar_produto_controlado;

-- Agora sim podemos dropar o usuário
DROP USER IF EXISTS 'logistica_admin'@'localhost';
DROP USER IF EXISTS 'logistica_admin'@'127.0.0.1';
DROP USER IF EXISTS 'logistica_admin'@'%';

-- Criar o novo usuário
CREATE USER IF NOT EXISTS 'sistema_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'Log@2024#Adm';

-- Garantir que o banco existe
CREATE DATABASE IF NOT EXISTS logistica_transportes;

-- Dar todas as permissões necessárias
GRANT ALL PRIVILEGES ON logistica_transportes.* TO 'sistema_user'@'localhost';
GRANT FILE ON *.* TO 'sistema_user'@'localhost';

-- Aplicar mudanças
FLUSH PRIVILEGES;

-- Verificar o novo usuário
SELECT User, Host, plugin 
FROM mysql.user 
WHERE User IN ('sistema_user', 'logistica_admin');

-- Verificar permissões
SHOW GRANTS FOR 'sistema_user'@'localhost';

-- Mostrar todos os usuários atuais
SELECT User, Host, plugin, authentication_string 
FROM mysql.user;

-- Remover usuários antigos (ignorar erros se não puder remover)
DROP USER IF EXISTS 'logistica_admin'@'localhost';
DROP USER IF EXISTS 'logistica_admin'@'127.0.0.1';
DROP USER IF EXISTS 'logistica_admin'@'%';
DROP USER IF EXISTS 'logistica_user'@'localhost';
DROP USER IF EXISTS 'logistica_user'@'127.0.0.1';
DROP USER IF EXISTS 'logistica_user'@'%';

-- Criar um usuário completamente novo
CREATE USER 'sistema_user'@'localhost' IDENTIFIED BY 'Log@2024#Adm';

-- Garantir que o banco existe
CREATE DATABASE IF NOT EXISTS logistica_transportes;

-- Dar todas as permissões
GRANT ALL PRIVILEGES ON logistica_transportes.* TO 'sistema_user'@'localhost';
GRANT FILE ON *.* TO 'sistema_user'@'localhost';
GRANT SUPER ON *.* TO 'sistema_user'@'localhost';

-- Aplicar mudanças
FLUSH PRIVILEGES;

-- Verificar o novo usuário
SELECT User, Host, plugin 
FROM mysql.user 
WHERE User = 'sistema_user';

-- Verificar permissões
SHOW GRANTS FOR 'sistema_user'@'localhost';

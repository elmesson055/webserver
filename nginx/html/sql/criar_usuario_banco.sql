-- Remover usuário se existir (para garantir uma configuração limpa)
DROP USER IF EXISTS 'logistica_admin'@'localhost';

-- Criar usuário novamente
CREATE USER 'logistica_admin'@'localhost' IDENTIFIED BY 'Log@2024#Adm';

-- Garantir que o banco existe
CREATE DATABASE IF NOT EXISTS logistica_transportes;

-- Conceder todas as permissões no banco
GRANT ALL PRIVILEGES ON logistica_transportes.* TO 'logistica_admin'@'localhost';

-- Aplicar as mudanças
FLUSH PRIVILEGES;

-- Verificar se deu certo
SELECT User, Host FROM mysql.user WHERE User = 'logistica_admin';
SHOW GRANTS FOR 'logistica_admin'@'localhost';

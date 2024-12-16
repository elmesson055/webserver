-- Criar o banco de dados se não existir
CREATE DATABASE IF NOT EXISTS logistica_transportes
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Criar o usuário se não existir
CREATE USER IF NOT EXISTS 'logistica_admin'@'localhost' IDENTIFIED BY 'LOg1st1ca2024!';

-- Conceder todos os privilégios ao banco logistica_transportes
GRANT ALL PRIVILEGES ON logistica_transportes.* TO 'logistica_admin'@'localhost';

-- Recarregar privilégios
FLUSH PRIVILEGES;

-- Verificar se o usuário foi criado
SELECT User, Host FROM mysql.user WHERE User = 'logistica_admin';

-- Verificar privilégios do usuário
SHOW GRANTS FOR 'logistica_admin'@'localhost';

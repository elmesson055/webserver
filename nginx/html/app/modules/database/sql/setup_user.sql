-- Criar usuário se não existir
CREATE USER IF NOT EXISTS 'logistica_admin'@'localhost' IDENTIFIED BY 'LOg1st1ca2024!';

-- Conceder todos os privilégios ao banco de dados logistica_transportes
GRANT ALL PRIVILEGES ON logistica_transportes.* TO 'logistica_admin'@'localhost';

-- Recarregar privilégios
FLUSH PRIVILEGES;

-- Verificar se o usuário foi criado
SELECT User, Host FROM mysql.user WHERE User = 'logistica_admin';

-- Verificar privilégios do usuário
SHOW GRANTS FOR 'logistica_admin'@'localhost';

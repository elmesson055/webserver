-- Verificar se o usuário existe
SELECT User, Host FROM mysql.user WHERE User = 'logistica_admin';

-- Verificar permissões do usuário
SHOW GRANTS FOR 'logistica_admin'@'localhost';

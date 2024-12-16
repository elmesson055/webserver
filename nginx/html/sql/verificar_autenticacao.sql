-- Verificar o plugin de autenticação usado
SELECT User, Host, plugin 
FROM mysql.user 
WHERE User = 'logistica_admin';

-- Verificar se o usuário pode se conectar de 127.0.0.1
SELECT User, Host 
FROM mysql.user 
WHERE User = 'logistica_admin' 
AND (Host = '127.0.0.1' OR Host = '%' OR Host = 'localhost');

-- Criar usuário específico para 127.0.0.1 se não existir
CREATE USER IF NOT EXISTS 'logistica_admin'@'127.0.0.1' IDENTIFIED BY 'Log@2024#Adm';
GRANT ALL PRIVILEGES ON logistica_transportes.* TO 'logistica_admin'@'127.0.0.1';

-- Alterar método de autenticação para nativo se necessário
ALTER USER 'logistica_admin'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY 'Log@2024#Adm';
ALTER USER 'logistica_admin'@'localhost' IDENTIFIED WITH mysql_native_password BY 'Log@2024#Adm';
ALTER USER 'logistica_admin'@'%' IDENTIFIED WITH mysql_native_password BY 'Log@2024#Adm';

-- Aplicar mudanças
FLUSH PRIVILEGES;

-- Verificar novamente
SELECT User, Host, plugin 
FROM mysql.user 
WHERE User = 'logistica_admin';

-- Criar um usuário completamente novo com um nome diferente
CREATE USER 'logistica_user'@'127.0.0.1' IDENTIFIED WITH caching_sha2_password BY 'Log@2024#Adm';
GRANT ALL PRIVILEGES ON logistica_transportes.* TO 'logistica_user'@'127.0.0.1';

-- Definir a senha com o método SHA-2 diretamente
ALTER USER 'logistica_user'@'127.0.0.1' IDENTIFIED BY 'Log@2024#Adm';

FLUSH PRIVILEGES;

-- Verificar o novo usuário
SELECT User, Host, plugin 
FROM mysql.user 
WHERE User = 'logistica_user';

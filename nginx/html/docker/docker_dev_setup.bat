@echo off
setlocal

:: Script de configuração de ambiente de desenvolvimento

:: Nome do container PHP
set PHP_CONTAINER_NAME=php_custos

:: Configurar permissões
echo Configurando permissões de arquivos...
docker exec -it %PHP_CONTAINER_NAME% bash -c "chmod -R 755 /var/www/html"
docker exec -it %PHP_CONTAINER_NAME% bash -c "chown -R www-data:www-data /var/www/html"

:: Instalar dependências do projeto
echo Instalando dependências do Composer...
docker exec -it %PHP_CONTAINER_NAME% bash -c "cd /var/www/html && composer install"

:: Configurar variáveis de ambiente
echo Configurando variáveis de ambiente...
docker exec -it %PHP_CONTAINER_NAME% bash -c "cp /var/www/html/.env.example /var/www/html/.env"

:: Gerar chave da aplicação (se aplicável)
echo Gerando chave da aplicação...
docker exec -it %PHP_CONTAINER_NAME% bash -c "php artisan key:generate"

:: Executar migrações
echo Executando migrações de banco de dados...
docker exec -it %PHP_CONTAINER_NAME% bash -c "php artisan migrate"

echo Configuração de desenvolvimento concluída.
timeout /t 5 >nul

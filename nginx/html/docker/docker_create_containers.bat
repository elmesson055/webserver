@echo off
setlocal enabledelayedexpansion

:: Script para criar containers para o projeto Custo Extras

:: Definir variáveis
set PROJECT_NAME=custo-extras
set MYSQL_CONTAINER_NAME=mysql_custos
set PHP_CONTAINER_NAME=php_custos
set NETWORK_NAME=custo_extras_network

:: Verificar se o Docker está instalado
docker --version >nul 2>&1
if errorlevel 1 (
    echo Erro: Docker não está instalado.
    pause
    exit /b 1
)

:: Criar rede personalizada
echo Criando rede personalizada...
docker network create %NETWORK_NAME% 2>nul

:: Criar container MySQL
echo Criando container MySQL...
docker run -d ^
    --name %MYSQL_CONTAINER_NAME% ^
    --network %NETWORK_NAME% ^
    -e MYSQL_ROOT_PASSWORD=custo#123 ^
    -e MYSQL_DATABASE=custo_extras ^
    -e MYSQL_USER=custos ^
    -e MYSQL_PASSWORD=custo#123 ^
    -p 3306:3306 ^
    -v C:\custo-extras\database:/docker-entrypoint-initdb.d ^
    -v mysql_data:/var/lib/mysql ^
    mysql:8.0

:: Criar container PHP
echo Criando container PHP...
docker run -d ^
    --name %PHP_CONTAINER_NAME% ^
    --network %NETWORK_NAME% ^
    -p 8088:80 ^
    -v C:\custo-extras:/var/www/html ^
    php:8.1-apache

:: Configurações adicionais para o container PHP
echo Configurando container PHP...
docker exec %PHP_CONTAINER_NAME% docker-php-ext-install pdo pdo_mysql
docker exec %PHP_CONTAINER_NAME% a2enmod rewrite

:: Reiniciar containers para aplicar configurações
echo Reiniciando containers...
docker restart %MYSQL_CONTAINER_NAME%
docker restart %PHP_CONTAINER_NAME%

:: Verificar status dos containers
echo Verificando status dos containers...
docker ps

echo Containers criados com sucesso.
timeout /t 5 >nul

@echo off
setlocal

:: Script para configurar Redis no projeto Custo Extras

:: Nome do container Redis
set REDIS_CONTAINER_NAME=redis_custos
set NETWORK_NAME=custo_extras_network

:: Criar container Redis
echo Criando container Redis...
docker run -d ^
    --name %REDIS_CONTAINER_NAME% ^
    --network %NETWORK_NAME% ^
    -p 6379:6379 ^
    redis:6.2-alpine

:: Instalar extensão Redis no PHP
echo Instalando extensão Redis no PHP...
docker exec -it php_custos bash -c "pecl install redis && docker-php-ext-enable redis"

:: Reiniciar container PHP para aplicar extensão
echo Reiniciando container PHP...
docker restart php_custos

:: Verificar status dos containers
echo Verificando status dos containers...
docker ps | findstr "redis"

echo Configuração do Redis concluída.
timeout /t 5 >nul

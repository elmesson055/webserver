@echo off
setlocal

:: Script de parada dos containers Docker para Custo Extras

:: Parar containers
echo Parando containers do Custo Extras...
docker stop mysql_custos
docker stop php_custos

:: Verificar status dos containers
echo Verificando status dos containers...
docker ps

echo Containers parados.
timeout /t 3 >nul

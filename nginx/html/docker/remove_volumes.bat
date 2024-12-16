@echo off
setlocal EnableDelayedExpansion

echo ========================================
echo    Removendo Volumes do Docker
echo ========================================
echo.

:: Parar todos os containers primeiro
echo Parando todos os containers...
docker-compose -f "%~dp0\..\docker-compose.yml" down

:: Listar volumes antes da remoção
echo.
echo Volumes antes da remoção:
docker volume ls | findstr "custos"

:: Remover volumes específicos do projeto
echo.
echo Removendo volumes...
for %%v in (custos_mysql_data custos_redis_data) do (
    echo Removendo volume: %%v
    docker volume rm %%v 2>nul
)

:: Remover volumes órfãos
echo.
echo Removendo volumes órfãos...
docker volume prune -f

:: Listar volumes após a remoção
echo.
echo Volumes após a remoção:
docker volume ls | findstr "custos"

echo.
echo ========================================
echo    Volumes Removidos com Sucesso
echo ========================================
echo.
echo Para recriar os containers e volumes:
echo 1. Execute deploy.bat
echo.

pause

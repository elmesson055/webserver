@echo off
echo Parando e removendo todos os containers do projeto...

:: Lista de containers do projeto
set CONTAINERS=custo_extras_web custo_extras_db custo_extras_backup composer_service

:: Parar todos os containers
echo.
echo Parando containers...
docker stop %CONTAINERS%

:: Remover todos os containers
echo.
echo Removendo containers...
docker rm %CONTAINERS%

:: Remover volumes não utilizados
echo.
echo Removendo volumes não utilizados...
docker volume prune -f

:: Remover redes não utilizadas
echo.
echo Removendo redes não utilizadas...
docker network prune -f

echo.
echo Limpeza concluída!
echo.
echo Para verificar se todos os containers foram removidos, execute: docker ps -a
pause

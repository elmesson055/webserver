@echo off
echo Removendo TODOS os containers, imagens e volumes do Docker...

:: Parar todos os containers em execução
echo.
echo Parando todos os containers em execução...
for /f "tokens=*" %%i in ('docker ps -q') do docker stop %%i

:: Remover todos os containers
echo.
echo Removendo todos os containers...
for /f "tokens=*" %%i in ('docker ps -aq') do docker rm %%i

:: Remover todas as imagens
echo.
echo Removendo todas as imagens...
for /f "tokens=*" %%i in ('docker images -q') do docker rmi -f %%i

:: Remover todos os volumes
echo.
echo Removendo todos os volumes...
docker volume prune -f

:: Remover todas as redes não utilizadas
echo.
echo Removendo todas as redes não utilizadas...
docker network prune -f

:: Remover todos os builds em cache
echo.
echo Removendo cache de builds...
docker builder prune -f

echo.
echo Limpeza completa concluída!
echo.
echo Para verificar o estado atual do Docker, execute:
echo docker ps -a
echo docker images
echo docker volume ls
pause

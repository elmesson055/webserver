@echo off
setlocal EnableDelayedExpansion

echo ========================================
echo    Removendo todas as imagens Docker
echo ========================================
echo.

:: Listar todas as imagens antes
echo Imagens atuais:
echo --------------
docker images
echo.

:: Perguntar se quer continuar
set /p confirm="Tem certeza que deseja remover TODAS as imagens? (S/N) "
if /i not "!confirm!"=="S" (
    echo.
    echo Operação cancelada pelo usuário.
    goto :EOF
)

:: Parar todos os containers em execução
echo.
echo Parando todos os containers em execução...
for /f "tokens=*" %%i in ('docker ps -q') do (
    echo Parando container: %%i
    docker stop %%i
)

:: Remover todos os containers
echo.
echo Removendo todos os containers...
for /f "tokens=*" %%i in ('docker ps -aq') do (
    echo Removendo container: %%i
    docker rm -f %%i
)

:: Remover todas as imagens
echo.
echo Removendo todas as imagens...
for /f "tokens=*" %%i in ('docker images -q') do (
    echo Removendo imagem: %%i
    docker rmi -f %%i
)

:: Remover imagens dangling (sem tag)
echo.
echo Removendo imagens dangling...
docker image prune -f

:: Mostrar status final
echo.
echo ========================================
echo              Resultado
echo ========================================
echo.
echo Containers restantes:
docker ps -a
echo.
echo Imagens restantes:
docker images
echo.
echo Limpeza concluída!
echo.
pause

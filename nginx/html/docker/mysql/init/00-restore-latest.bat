@echo off
setlocal EnableDelayedExpansion

:: Configurações
set DB_USER=custos
set DB_PASS=custo#123
set DB_NAME=custo_extras
set SQL_DIR=C:\backup_mysql_custo-extras\sql

:: Aguardar o MySQL iniciar (tenta 30 vezes, esperando 2 segundos entre cada tentativa)
echo Aguardando MySQL iniciar...
set /a attempts=0
:WAIT_MYSQL
docker exec custo_extras_db mysqladmin ping -h"localhost" >nul 2>&1
if !errorlevel! neq 0 (
    set /a attempts+=1
    if !attempts! lss 30 (
        timeout /t 2 /nobreak >nul
        goto WAIT_MYSQL
    ) else (
        echo Tempo limite excedido aguardando MySQL.
        exit /b 1
    )
)

:: Encontrar o arquivo restore mais recente
set "latest_restore="
set "latest_date=0"

echo Procurando arquivos restore em: %SQL_DIR%
for %%F in ("%SQL_DIR%\restore_*.sql") do (
    set "file_date=%%~nF"
    set "file_date=!file_date:restore_=!"
    if "!file_date!" gtr "!latest_date!" (
        set "latest_date=!file_date!"
        set "latest_restore=%%F"
    )
)

:: Restaurar o backup mais recente
if defined latest_restore (
    echo.
    echo Encontrado arquivo para restore: !latest_restore!
    echo Restaurando backup...
    docker exec -i custo_extras_db mysql -u%DB_USER% -p%DB_PASS% %DB_NAME% < "!latest_restore!"
    if !errorlevel! equ 0 (
        echo.
        echo Restauração concluída com sucesso!
    ) else (
        echo.
        echo Erro ao restaurar backup.
        exit /b 1
    )
) else (
    echo.
    echo Nenhum arquivo restore_*.sql encontrado em: %SQL_DIR%
    exit /b 1
)

exit /b 0

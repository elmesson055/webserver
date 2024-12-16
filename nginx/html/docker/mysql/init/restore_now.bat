@echo off
setlocal EnableDelayedExpansion

:: Configurações
set DB_USER=custos
set DB_PASS=custo#123
set DB_NAME=custo_extras
set SQL_DIR=C:\backup_mysql_custo-extras\sql

:: Verificar se o MySQL está rodando
echo Verificando se o MySQL está rodando...
docker exec custo_extras_db mysqladmin ping -h"localhost" >nul 2>&1
if %errorlevel% neq 0 (
    echo MySQL não está rodando. Inicie os containers primeiro.
    echo Execute: docker-compose up -d
    exit /b 1
)

:: Encontrar o backup mais recente
set "latest_backup="
set "latest_date=0"

echo Procurando backups em: %SQL_DIR%
for %%F in ("%SQL_DIR%\*.sql") do (
    set "file_date=%%~tF"
    if "!file_date!" gtr "!latest_date!" (
        set "latest_date=!file_date!"
        set "latest_backup=%%F"
    )
)

:: Restaurar o backup mais recente
if defined latest_backup (
    echo.
    echo Encontrado backup mais recente: !latest_backup!
    echo.
    set /p confirm="Deseja restaurar este backup? (S/N) "
    if /i "!confirm!"=="S" (
        echo.
        echo Restaurando backup...
        docker exec -i custo_extras_db mysql -u%DB_USER% -p%DB_PASS% %DB_NAME% < "!latest_backup!"
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
        echo Operação cancelada pelo usuário.
    )
) else (
    echo.
    echo Nenhum backup SQL encontrado em: %SQL_DIR%
    exit /b 1
)

echo.
pause

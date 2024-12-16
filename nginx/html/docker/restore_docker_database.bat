@echo off
setlocal enabledelayedexpansion

:: Configurações
set BACKUP_DIR=C:\backup_mysql_custo-extras\backups
set MYSQL_CONTAINER=mysql_custos

:: Listar backups disponíveis
echo Backups disponíveis:
for %%F in ("%BACKUP_DIR%\*.tar.gz") do (
    echo %%~nxF
)

:: Solicitar nome do backup
set /p BACKUP_FILE="Digite o nome do arquivo de backup para restaurar (com .tar.gz): "
set FULL_BACKUP_PATH=%BACKUP_DIR%\%BACKUP_FILE%

:: Extrair nome do banco de dados
for /f "tokens=1 delims=_" %%D in ("%BACKUP_FILE%") do set DB_NAME=%%D

:: Copiar backup para o container
docker cp "%FULL_BACKUP_PATH%" %MYSQL_CONTAINER%:/backup/%BACKUP_FILE%

:: Executar script de restauração dentro do container
docker exec -it %MYSQL_CONTAINER% bash /backup/docker_restore_database.sh -d %DB_NAME% -f /backup/%BACKUP_FILE%

:: Limpar arquivo copiado
docker exec %MYSQL_CONTAINER% rm /backup/%BACKUP_FILE%

echo Restauração concluída.
timeout /t 5 >nul

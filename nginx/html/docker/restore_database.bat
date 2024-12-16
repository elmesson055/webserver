@echo off
setlocal enabledelayedexpansion

:: Configurações de restauração
set BACKUP_DIR=C:\backup_mysql_custo-extras\backups
set MYSQL_CONTAINER=mysql_custos
set MYSQL_USER=custos
set MYSQL_PASSWORD=custo#123

:: Listar backups disponíveis
echo Backups disponíveis:
for %%F in ("%BACKUP_DIR%\*.tar.gz") do (
    echo %%~nxF
)

:: Solicitar nome do backup
set /p BACKUP_FILE="Digite o nome do arquivo de backup para restaurar (com .tar.gz): "
set FULL_BACKUP_PATH=%BACKUP_DIR%\%BACKUP_FILE%

:: Verificar se o arquivo existe
if not exist "%FULL_BACKUP_PATH%" (
    echo Arquivo de backup não encontrado.
    timeout /t 3 >nul
    exit /b
)

:: Extrair nome do banco de dados
for /f "tokens=1 delims=_" %%D in ("%BACKUP_FILE%") do set DB_NAME=%%D

:: Descompactar backup
tar -xzvf "%FULL_BACKUP_PATH%" -C "%BACKUP_DIR%"

:: Encontrar o arquivo SQL extraído
for %%F in ("%BACKUP_DIR%\%DB_NAME%*.sql") do set SQL_FILE=%%F

:: Restaurar banco de dados
echo Restaurando banco de dados %DB_NAME%...
docker exec -i %MYSQL_CONTAINER% mysql -u %MYSQL_USER% -p%MYSQL_PASSWORD% %DB_NAME% < "%SQL_FILE%"

if %errorlevel% equ 0 (
    echo Backup restaurado com sucesso.
) else (
    echo Falha na restauração do backup.
)

:: Limpar arquivo extraído
del "%SQL_FILE%"

timeout /t 3 >nul

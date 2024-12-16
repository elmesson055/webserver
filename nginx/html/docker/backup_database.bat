@echo off
setlocal enabledelayedexpansion

:: Configurações de backup
set BACKUP_DIR=C:\backup_mysql_custo-extras\backups
set MYSQL_CONTAINER=mysql_custos
set MYSQL_USER=custos
set MYSQL_PASSWORD=custo#123
set DATABASES=custo_extras mysql

:: Criar diretório de backup se não existir
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

:: Obter data e hora atual com milissegundos para garantir unicidade
for /f "tokens=1-3 delims=/ " %%a in ("%date%") do (
    set BACKUP_DATE=%%c%%a%%b
)
for /f "tokens=1-4 delims=:. " %%a in ("%time%") do (
    set BACKUP_TIME=%%a%%b%%c%%d
)

:: Loop para fazer backup de cada banco de dados
for %%D in (%DATABASES%) do (
    set BACKUP_FILE=%BACKUP_DIR%\%%D_%BACKUP_DATE%_%BACKUP_TIME%.sql
    
    echo Fazendo backup do banco de dados %%D...
    docker exec %MYSQL_CONTAINER% mysqldump -u %MYSQL_USER% -p%MYSQL_PASSWORD% %%D > "!BACKUP_FILE!"
    
    if %errorlevel% equ 0 (
        echo Backup de %%D concluído com sucesso.
        
        :: Comprimir backup usando comando nativo
        tar -czvf "!BACKUP_FILE!.tar.gz" "!BACKUP_FILE!"
        del "!BACKUP_FILE!"
        
        echo Backup de %%D compactado.
    ) else (
        echo Falha no backup de %%D.
    )
)

:: Listar backups
echo Backups disponíveis:
dir "%BACKUP_DIR%"

:: Manter apenas os 12 backups mais recentes
for /f "skip=12 eol=: delims=" %%F in ('dir /b /o-d "%BACKUP_DIR%\*.tar.gz"') do @del "%BACKUP_DIR%\%%F"

echo Backup concluído.
timeout /t 3 >nul

@echo off
setlocal EnableDelayedExpansion

:: Configurações
set BACKUP_ROOT=C:\backup_mysql_custo-extras
set EMERGENCY_DIR=%BACKUP_ROOT%\emergency
set SNAPSHOT_DIR=%BACKUP_ROOT%\snapshots
set TIMESTAMP=%date:~6,4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set TIMESTAMP=%TIMESTAMP: =0%
set LOG_FILE=%~dp0\..\Logs\backup_%TIMESTAMP%.log

:: Criar diretórios necessários
if not exist "%EMERGENCY_DIR%" mkdir "%EMERGENCY_DIR%"
if not exist "%SNAPSHOT_DIR%" mkdir "%SNAPSHOT_DIR%"
if not exist "%~dp0\..\Logs" mkdir "%~dp0\..\Logs"

echo ========================================
echo    Backup em Tempo Real - Custo Extras
echo    Data: %date% - Hora: %time%
echo ========================================

:: Função para log
call :log "Iniciando backup em tempo real"

:: Criar snapshot do sistema atual
echo Criando snapshot do sistema...
call :log "Criando snapshot do sistema"

:: 1. Backup do banco de dados
set DB_SNAPSHOT=%SNAPSHOT_DIR%\db_snapshot_%TIMESTAMP%.sql
docker exec custos_db mysqldump -ucustos -pcusto#123 custo_extras > "%DB_SNAPSHOT%"
if %errorlevel% equ 0 (
    call :log "Snapshot do banco criado: %DB_SNAPSHOT%"
) else (
    call :log "ERRO: Falha ao criar snapshot do banco"
    goto :error
)

:: 2. Backup dos volumes
echo Criando backup dos volumes...
set VOLUME_SNAPSHOT=%SNAPSHOT_DIR%\volumes_%TIMESTAMP%.tar
docker run --rm --volumes-from custos_db -v %SNAPSHOT_DIR%:/backup alpine tar cvf /backup/volumes_%TIMESTAMP%.tar /var/lib/mysql
if %errorlevel% equ 0 (
    call :log "Snapshot dos volumes criado: %VOLUME_SNAPSHOT%"
) else (
    call :log "ERRO: Falha ao criar snapshot dos volumes"
    goto :error
)

:: 3. Backup das configurações
echo Copiando configurações...
set CONFIG_SNAPSHOT=%SNAPSHOT_DIR%\config_%TIMESTAMP%
mkdir "%CONFIG_SNAPSHOT%"
xcopy /E /I /Y "%~dp0\..\docker" "%CONFIG_SNAPSHOT%\docker"
copy "%~dp0\..\docker-compose.yml" "%CONFIG_SNAPSHOT%"
if %errorlevel% equ 0 (
    call :log "Snapshot das configurações criado: %CONFIG_SNAPSHOT%"
) else (
    call :log "ERRO: Falha ao criar snapshot das configurações"
    goto :error
)

:: Criar arquivo de índice do snapshot
echo Criando índice do snapshot...
set INDEX_FILE=%SNAPSHOT_DIR%\snapshot_index.txt
echo Snapshot criado em: %date% %time% > "%INDEX_FILE%"
echo DB_SNAPSHOT=%DB_SNAPSHOT% >> "%INDEX_FILE%"
echo VOLUME_SNAPSHOT=%VOLUME_SNAPSHOT% >> "%INDEX_FILE%"
echo CONFIG_SNAPSHOT=%CONFIG_SNAPSHOT% >> "%INDEX_FILE%"

:: Backup bem-sucedido
echo.
echo ========================================
echo    Backup Concluído com Sucesso
echo ========================================
call :log "Backup concluído com sucesso"
echo.
echo Snapshots disponíveis em: %SNAPSHOT_DIR%
echo Logs disponíveis em: %LOG_FILE%
goto :end

:error
echo.
echo ========================================
echo    Erro Durante o Backup
echo ========================================
call :log "Backup finalizado com erros"
echo Verifique os logs em: %LOG_FILE%

:end
endlocal
exit /b 0

:log
echo [%date% %time%] %~1 >> "%LOG_FILE%"
echo %~1
exit /b 0

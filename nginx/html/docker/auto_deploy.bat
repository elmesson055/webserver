@echo off
setlocal EnableDelayedExpansion

:: Definir variáveis globais
set PROJECT_ROOT=%~dp0\..
set BACKUP_ROOT=C:\backup_mysql_custo-extras
set TIMESTAMP=%date:~6,4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set TIMESTAMP=%TIMESTAMP: =0%
set EMERGENCY_BACKUP=%BACKUP_ROOT%\emergency\backup_%TIMESTAMP%.sql
set LOG_FILE=%PROJECT_ROOT%\Logs\deploy_%TIMESTAMP%.log

echo ========================================
echo    Auto Deploy - Sistema Custo Extras
echo    Data: %date% - Hora: %time%
echo ========================================
echo.

:: Criar diretórios necessários
echo Criando diretórios necessários...
if not exist "%BACKUP_ROOT%\backups" mkdir "%BACKUP_ROOT%\backups"
if not exist "%BACKUP_ROOT%\sql" mkdir "%BACKUP_ROOT%\sql"
if not exist "%BACKUP_ROOT%\emergency" mkdir "%BACKUP_ROOT%\emergency"
if not exist "%PROJECT_ROOT%\Logs" mkdir "%PROJECT_ROOT%\Logs"

:: Função para registrar logs
call :log "Iniciando processo de deploy"

:: Verificar se o Docker está rodando
echo Verificando status do Docker...
docker info > nul 2>&1
if %errorlevel% neq 0 (
    call :log "ERRO: Docker não está rodando!"
    echo ERRO: Por favor, inicie o Docker Desktop e tente novamente.
    goto :error
)

:: Backup de emergência antes de parar os containers
echo Criando backup de emergência...
if exist "%PROJECT_ROOT%\docker-compose.yml" (
    docker-compose -f "%PROJECT_ROOT%\docker-compose.yml" ps -q custos_db > nul 2>&1
    if !errorlevel! equ 0 (
        call :log "Criando backup de emergência"
        docker exec custos_db mysqldump -ucustos -pcusto#123 custo_extras > "%EMERGENCY_BACKUP%"
        if !errorlevel! equ 0 (
            call :log "Backup de emergência criado com sucesso: %EMERGENCY_BACKUP%"
        ) else (
            call :log "AVISO: Não foi possível criar backup de emergência"
        )
    )
)

:: Criar backup em tempo real antes do deploy
echo Criando backup em tempo real...
call "%~dp0\realtime_backup.bat"
if %errorlevel% neq 0 (
    call :log "ERRO: Falha ao criar backup em tempo real"
    goto :error
)

:: Verificar espaço em disco
echo Verificando espaço em disco...
for /f "tokens=3" %%a in ('dir /-c /w "%SystemDrive%" ^| find "bytes free"') do set FREE_SPACE=%%a
if %FREE_SPACE% LSS 5368709120 (
    call :log "ERRO: Espaço insuficiente em disco (menos de 5GB livres)"
    goto :error
)

:: Parar containers existentes
echo Parando containers existentes...
call :log "Parando containers existentes"
docker-compose -f "%PROJECT_ROOT%\docker-compose.yml" down

:: Limpar recursos não utilizados
echo Limpando recursos não utilizados...
call :log "Limpando recursos Docker não utilizados"
docker system prune -f

:: Remover containers antigos com prefixo custos_
echo Removendo containers antigos...
for /f "tokens=*" %%c in ('docker ps -a -q --filter "name=custos_*"') do (
    docker rm -f %%c
)

:: Pull das imagens mais recentes
echo Atualizando imagens...
call :log "Atualizando imagens Docker"
docker-compose -f "%PROJECT_ROOT%\docker-compose.yml" pull

:: Construir e iniciar containers
echo Construindo e iniciando containers...
call :log "Iniciando containers"
docker-compose -f "%PROJECT_ROOT%\docker-compose.yml" up -d --build

:: Aguardar containers iniciarem
echo Aguardando containers iniciarem...
timeout /t 20 /nobreak > nul

:: Verificar status dos containers
echo Verificando status dos containers...
set FAILED_CONTAINERS=0
for %%s in (web db redis backup) do (
    docker ps -q -f name=custos_%%s > nul 2>&1
    if !errorlevel! neq 0 (
        set /a FAILED_CONTAINERS+=1
        call :log "ERRO: Container custos_%%s não está rodando"
    )
)

if %FAILED_CONTAINERS% gtr 0 (
    call :log "ERRO: %FAILED_CONTAINERS% container(s) falharam ao iniciar"
    goto :restore
)

:: Instalar dependências
echo Instalando dependências...
call :log "Instalando dependências do Composer"
docker exec custos_web bash -c "cd /var/www/html && composer install"

:: Verificar conexão com Redis
echo Verificando conexão com Redis...
docker exec custos_web bash -c "php -r \"try { \$redis = new Redis(); \$redis->connect('redis', 6379); echo 'Redis OK'; } catch (Exception \$e) { exit(1); }\"" > nul 2>&1
if %errorlevel% neq 0 (
    call :log "ERRO: Falha na conexão com Redis"
    goto :error
)

:: Verificar conexão com MySQL
echo Verificando conexão com MySQL...
docker exec custos_web bash -c "php -r \"try { new PDO('mysql:host=db;dbname=custo_extras', 'custos', 'custo#123'); echo 'MySQL OK'; } catch (PDOException \$e) { exit(1); }\"" > nul 2>&1
if %errorlevel% neq 0 (
    call :log "ERRO: Falha na conexão com MySQL"
    goto :error
)

:: Deploy concluído com sucesso
echo.
echo ========================================
echo        Deploy Concluído com Sucesso
echo ========================================
call :log "Deploy concluído com sucesso"
echo.
echo Serviços disponíveis em:
echo - Web: http://localhost
echo - MySQL: localhost:3307
echo - Redis: localhost:6379
echo.
echo Logs disponíveis em: %LOG_FILE%
echo.
goto :end

:restore
:: Procedimento de restauração em caso de falha
echo.
echo ========================================
echo    Iniciando Procedimento de Restauração
echo ========================================
call :log "Iniciando procedimento de restauração"

:: Tentar restaurar do backup de emergência
if exist "%EMERGENCY_BACKUP%" (
    echo Restaurando do backup de emergência...
    call :log "Restaurando do backup de emergência"
    docker exec -i custos_db mysql -ucustos -pcusto#123 custo_extras < "%EMERGENCY_BACKUP%"
)

:error
echo.
echo ========================================
echo    Erro Durante o Deploy
echo ========================================
call :log "Deploy finalizado com erros"
echo Verifique os logs em: %LOG_FILE%
echo.

:end
endlocal
exit /b 0

:log
echo [%date% %time%] %~1 >> "%LOG_FILE%"
echo %~1
exit /b 0

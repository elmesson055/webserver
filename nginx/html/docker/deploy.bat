@echo off
setlocal EnableDelayedExpansion

echo ========================================
echo    Deploy do Sistema Custo Extras
echo ========================================
echo.

:: Definir diretório raiz do projeto
set PROJECT_ROOT=%~dp0\..
set CACHE_DIR=%PROJECT_ROOT%\storage\cache\deploy
set COMPOSER_CACHE_DIR=%CACHE_DIR%\composer
set NPM_CACHE_DIR=%CACHE_DIR%\npm

:: Criar diretórios de cache se não existirem
if not exist "%CACHE_DIR%" mkdir "%CACHE_DIR%"
if not exist "%COMPOSER_CACHE_DIR%" mkdir "%COMPOSER_CACHE_DIR%"
if not exist "%NPM_CACHE_DIR%" mkdir "%NPM_CACHE_DIR%"

:: Verificar hash dos arquivos de dependência
set DEPS_HASH_FILE=%CACHE_DIR%\deps.hash
set CURRENT_HASH=
for %%f in (composer.json composer.lock package.json package-lock.json) do (
    if exist "%%f" set /p TEMP_HASH=<"%%f" && set "CURRENT_HASH=!CURRENT_HASH!!TEMP_HASH!"
)

set CACHED_HASH=
if exist "%DEPS_HASH_FILE%" set /p CACHED_HASH=<"%DEPS_HASH_FILE%"

:: Parar containers existentes
echo Parando containers existentes...
call "%PROJECT_ROOT%\DOCKER\docker_stop.bat"

:: Remover containers antigos
echo.
echo Removendo containers antigos...
call "%PROJECT_ROOT%\DOCKER\remove_containers.bat"

:: Usar cache do build se disponível
if exist "%CACHE_DIR%\docker-cache" (
    echo Usando cache do Docker...
    docker load -i "%CACHE_DIR%\docker-cache"
)

:: Construir e iniciar containers com cache
echo Construindo e iniciando containers...
cd "%PROJECT_ROOT%"
docker-compose build --cache-from custos_web:latest
docker-compose up -d --build

:: Instalar dependências apenas se necessário
if not "%CURRENT_HASH%"=="%CACHED_HASH%" (
    echo Instalando dependências...
    docker exec custos_web bash -c "COMPOSER_CACHE_DIR=/var/cache/composer composer install --prefer-dist --no-dev"
    docker exec custos_web bash -c "npm ci --cache /var/cache/npm --prefer-offline"
    echo !CURRENT_HASH!>"%DEPS_HASH_FILE%"
) else (
    echo Usando dependências em cache...
)

:: Salvar cache do Docker
docker save custos_web:latest -o "%CACHE_DIR%\docker-cache"

:: Limpar caches antigos
forfiles /P "%CACHE_DIR%" /M *.* /D -7 /C "cmd /c del @path" 2>nul

:: Aguardar containers iniciarem
echo.
echo Aguardando containers iniciarem...
timeout /t 10 /nobreak > nul

:: Verificar status dos containers
echo.
echo Verificando status dos containers...
echo.
echo Status dos Containers:
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}" | findstr "custos_"

:: Restaurar último backup se existir
echo.
echo Verificando backup mais recente...
if exist "C:\backup_mysql_custo-extras\sql\restore_*.sql" (
    echo Restaurando último backup...
    call "%PROJECT_ROOT%\DOCKER\restore_docker_database.bat"
)

:: Verificar logs por erros
echo.
echo Verificando logs por erros...
echo.
echo Logs do Web Server:
docker logs custos_web --tail 10
echo.
echo Logs do MySQL:
docker logs custos_db --tail 10
echo.
echo Logs do Redis:
docker logs custos_redis --tail 10
echo.
echo Logs do Backup:
docker logs custos_backup --tail 10

echo.
echo ========================================
echo             Deploy Concluído
echo ========================================
echo.
echo O sistema está disponível em:
echo - Web: http://localhost
echo - MySQL: localhost:3307
echo - Redis: localhost:6379
echo.
echo Credenciais MySQL:
echo - Usuário: custos
echo - Senha: custo#123
echo - Banco: custo_extras
echo.
echo Para verificar os logs use:
echo docker logs custos_web     # Logs do Apache/PHP
echo docker logs custos_db      # Logs do MySQL
echo docker logs custos_redis   # Logs do Redis
echo docker logs custos_backup  # Logs do Backup
echo.

:: Voltar para o diretório original
cd "%~dp0"

pause

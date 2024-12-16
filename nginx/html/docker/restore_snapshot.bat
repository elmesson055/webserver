@echo off
setlocal EnableDelayedExpansion

set BACKUP_ROOT=C:\backup_mysql_custo-extras
set SNAPSHOT_DIR=%BACKUP_ROOT%\snapshots
set TIMESTAMP=20241208_141137

echo ========================================
echo    Restauração Direta - Custo Extras
echo    Data: %date% - Hora: %time%
echo ========================================

:: Parar containers
echo Parando containers...
docker-compose -f "%~dp0\..\docker-compose.yml" down

:: Restaurar banco de dados
echo Restaurando banco de dados...
docker-compose -f "%~dp0\..\docker-compose.yml" up -d db
timeout /t 10 /nobreak > nul
docker exec -i custos_db mysql -ucustos -pcusto#123 custo_extras < "%SNAPSHOT_DIR%\db_snapshot_%TIMESTAMP%.sql"

:: Restaurar volumes
echo Restaurando volumes...
docker volume rm custos_mysql_data
docker volume create custos_mysql_data
docker run --rm -v custos_mysql_data:/var/lib/mysql -v %SNAPSHOT_DIR%:/backup alpine sh -c "cd /var/lib/mysql && tar xvf /backup/volumes_%TIMESTAMP%.tar --strip-components=3"

:: Iniciar todos os containers
echo Iniciando containers...
docker-compose -f "%~dp0\..\docker-compose.yml" up -d

echo.
echo ========================================
echo    Restauração Concluída
echo ========================================

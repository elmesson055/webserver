@ECHO OFF

REM Script para iniciar servidor web de desenvolvimento (Nginx + PHP FCGI)

REM Definição de variáveis
SET "NGINX_DIR=C:\webserver\nginx"
SET "PHP_DIR=C:\webserver\php-8"
SET PHP_FCGI_MAX_REQUESTS=0

REM Validar se os diretórios e executáveis existem
IF NOT EXIST "%NGINX_DIR%\nginx.exe" (
    ECHO [ERRO] Nginx não encontrado em %NGINX_DIR%
    EXIT /B 1
)
IF NOT EXIST "%PHP_DIR%\php-cgi.exe" (
    ECHO [ERRO] PHP-CGI não encontrado em %PHP_DIR%
    EXIT /B 1
)

REM Iniciar serviço Nginx
ECHO Iniciando Nginx...
cd %NGINX_DIR%
start nginx.exe || (
    ECHO [ERRO] Falha ao iniciar Nginx.
    EXIT /B 1
)

REM Iniciar serviço PHP FCGI
ECHO Iniciando PHP FastCGI...
cd %PHP_DIR%
start php-cgi.exe -b 127.0.0.1:9000 || (
    ECHO [ERRO] Falha ao iniciar PHP FastCGI.
    EXIT /B 1
)

ECHO Ambiente de desenvolvimento iniciado com sucesso!

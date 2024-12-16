@ECHO OFF

PUSHD "C:\bin"

ECHO Iniciando o PHP 8 com FastCGI...

RunHiddenConsole.exe "C:\webserver\php-8\php-cgi.exe" -b 127.0.0.1:9000

ECHO Iniciando o Nginx...

RunHiddenConsole.exe "C:\webserver\nginx\nginx.exe"

POPD

EXIT /b
@ECHO OFF

REM [COMMENT]: script para parar serviços.

TASKKILL /F /IM nginx.exe
#TASKKILL /F /IM mysqld.exe
TASKKILL /F /IM php-cgi.exe

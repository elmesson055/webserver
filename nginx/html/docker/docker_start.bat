@echo off
setlocal

:: Script de inicialização dos containers Docker para Custo Extras

:: Navegar para o diretório do projeto
cd C:\custo-extras

:: Iniciar containers
echo Iniciando containers do Custo Extras...
docker-compose up -d

:: Verificar status dos containers
echo Verificando status dos containers...
docker ps

echo Containers iniciados.
timeout /t 3 >nul

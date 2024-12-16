@echo off
setlocal

:: Script de reinicialização dos containers Docker para Custo Extras

:: Parar todos os containers relacionados ao projeto
echo Parando containers existentes...
docker stop mysql_custos
docker stop php_custos

:: Remover containers antigos
echo Removendo containers antigos...
docker rm mysql_custos
docker rm php_custos

:: Limpar volumes (opcional, descomentar se quiser limpar dados)
:: docker volume prune -f

:: Recriar e iniciar containers
echo Recriando e iniciando containers...
cd C:\custo-extras
docker-compose up -d

:: Verificar status dos containers
echo Verificando status dos containers...
docker ps

echo Reinicialização concluída.
timeout /t 5 >nul

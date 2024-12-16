#!/bin/bash

# Aguardar MySQL
until nc -z -v -w30 mysql 3306
do
  echo "Aguardando conexão com MySQL..."
  sleep 1
done
echo "MySQL está pronto!"

# Aguardar Memcached
until nc -z -v -w30 memcached 11211
do
  echo "Aguardando conexão com Memcached..."
  sleep 1
done
echo "Memcached está pronto!"

# Aguardar Redis
until nc -z -v -w30 redis 6379
do
  echo "Aguardando conexão com Redis..."
  sleep 1
done
echo "Redis está pronto!"

# Instalar dependências do Composer se necessário
if [ ! -d "vendor" ]; then
    composer install --no-interaction
fi

# Executar migrações do banco de dados
echo "Executando migrações do banco de dados..."
php database/migrate.php

# Iniciar Apache em segundo plano
apache2-foreground

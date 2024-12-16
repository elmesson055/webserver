#!/bin/bash

# Criar diretório de erros se não existir
mkdir -p /var/www/html/public/views/errors

# Copiar arquivos de erro
cp -r /var/www/html/public/views/errors.bak/* /var/www/html/public/views/errors/

# Executar o entrypoint original do Apache
docker-php-entrypoint apache2-foreground

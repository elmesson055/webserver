#!/bin/bash

# Criar diretórios se não existirem
mkdir -p /var/www/html/public/assets/css
mkdir -p /var/www/html/public/assets/js

# Baixar Bootstrap CSS
curl -L https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css -o /var/www/html/public/assets/css/bootstrap.min.css

# Baixar Bootstrap JS
curl -L https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js -o /var/www/html/public/assets/js/bootstrap.bundle.min.js

# Definir permissões
chown -R www-data:www-data /var/www/html/public/assets
chmod -R 755 /var/www/html/public/assets
